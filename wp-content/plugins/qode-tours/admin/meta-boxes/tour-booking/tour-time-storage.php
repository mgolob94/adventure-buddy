<?php

namespace QodeTours\Admin\MetaBoxes\TourBooking;

/**
 * Class TourTimeStorage
 * @package QodeTours\Admin\MetaBoxes\TourBooking
 */
class TourTimeStorage {
	const AJAX_REQUEST = 'ajax';
	const SAVE_POST_REQUEST = 'save_post';

	/**
	 * @var private instance of current class
	 */
	private static $instance;

	/**
	 * Private constuct because of Singletone
	 */
	private function __construct() {
	}

	/**
	 * Private sleep because of Singletone
	 */
	public function __wakeup() {
	}

	/**
	 * Private clone because of Singletone
	 */
	private function __clone() {
	}

	/**
	 * Returns current instance of class
	 * @return TourTimeStorage
	 */
	public static function getInstance() {
		if(self::$instance == null) {
			return new self;
		}

		return self::$instance;
	}

	/**
	 * TourTimeStorage constructor.
	 */
	public function initialize() {
		add_action('save_post_tour-item', array($this, 'handleSavePostAction'), 10, 3);
	}
	
	public function handleSavePostAction($post_id, $post, $update) {
		$nonces_array = array();
		$meta_boxes   = bridge_qode_framework()->qodeMetaBoxes->getMetaBoxesByScope( $post->post_type );

		if ( is_array( $meta_boxes ) && count( $meta_boxes ) ) {
			foreach ( $meta_boxes as $meta_box ) {
				$nonces_array[] = 'qode_meta_box_' . $meta_box->name . '_save';
			}
		}

		if ( is_array( $nonces_array ) && count( $nonces_array ) ) {
			foreach ( $nonces_array as $nonce ) {
				if ( ! isset( $_POST[ $nonce ] ) || ! wp_verify_nonce( $_POST[ $nonce ], $nonce ) ) {
					return;
				}
			}
		}

		$postTypes = apply_filters( 'bridge_qode_filter_meta_box_post_types_save', array( 'post', 'page' ) );
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		
		if ( ! isset( $_POST['_wpnonce'] ) ) {
			return;
		}
		
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		
		if ( ! in_array( $post->post_type, $postTypes ) ) {
			return;
		}
		
		$this->storeTourTime($post_id, $this->getPeriodsData($_POST, self::SAVE_POST_REQUEST));
	}

	/**
	 * @param $data
	 *
	 * @return bool
	 */
	public function storeTourTime($post_id, $data) {
		global $wpdb;

		$allTourDates     = $this->getTourDates($post_id);
		$updatedTourDates = array();
		$updatedTourTimes = array();

		if(is_array($data) && count($data)) {
			foreach($data as $item) {
				$tourDate = array();

				$tourDate['id']                = empty($item['id']) ? false : $item['id'];
				$tourDate['start_date']        = date('Y-m-d', strtotime($item['start_date']));
				$tourDate['end_date']          = date('Y-m-d', strtotime($item['end_date']));
				$tourDate['days']              = maybe_serialize($item['days']);
				$tourDate['number_of_tickets'] = $item['number_of_tickets'];
				$tourDate['price_change']      = $item['price_change'];
				$tourDate['tour_id']           = $post_id;

				$success = $wpdb->replace($wpdb->prefix.'tour_dates', $tourDate, array(
					'%d',
					'%s',
					'%s',
					'%s',
					'%d',
					'%s',
					'%d'
				));

				if(!$success) {
					return false;
				}

				if(!empty($tourDate['id'])) {
					$updatedTourDates[$tourDate['id']] = $tourDate;
				}

				$dateId = $wpdb->insert_id;

				if(array_key_exists('tour_time', $item) && (is_array($item['tour_time']) && count($item['tour_time']))) {
					foreach($item['tour_time'] as $time) {
						$tourTime = array();

						$tourTime['tour_date_id'] = $dateId;
						$tourTime['time']         = $time['time'];
						$tourTime['id']           = empty($time['id']) ? false : $time['id'];

						if(!empty($tourTime['id'])) {
							$updatedTourTimes[$tourTime['id']] = $tourTime;
						}

						$wpdb->replace($wpdb->prefix.'tour_times', $tourTime, array(
							'%d',
							'%s'
						));
					}
				}
			}
		}

		if(is_array($updatedTourTimes) && (is_array($allTourDates) && count($allTourDates))) {
			$allTourTimes = $this->extractTourTimes($allTourDates);

			$timesToDelete = array_diff_key($allTourTimes, $updatedTourTimes);

			if(is_array($timesToDelete) && count($timesToDelete)) {
				$idsToDelete = array_keys($timesToDelete);

				$placeholders = array_fill(0, count($idsToDelete), '%d');
				$placeholders = implode(', ', $placeholders);

				$wpdb->get_results(
					$wpdb->prepare(
						"DELETE FROM {$wpdb->prefix}tour_times WHERE id IN ( {$placeholders} )",
						$idsToDelete
					)
				);
			}
		}

		//delete tour dates from DB that were deleted on form
		if((is_array($updatedTourDates)) && (is_array($allTourDates)) && count($allTourDates) ) {
			$datesToDelete = array_diff_key($allTourDates, $updatedTourDates);

			if(is_array($datesToDelete) && count($datesToDelete)) {
				$idsToDelete = array_keys($datesToDelete);

				$placeholders = array_fill(0, count($idsToDelete), '%d');
				$placeholders = implode(', ', $placeholders);

				$wpdb->get_results(
					$wpdb->prepare(
						"DELETE FROM {$wpdb->prefix}tour_dates 
						 WHERE id IN ( {$placeholders} )",
						$idsToDelete
					)
				);

				$wpdb->get_results(
					$wpdb->prepare(
						"DELETE FROM {$wpdb->prefix}tour_times
						 WHERE tour_date_id IN ( %f )",
						implode(', ', $idsToDelete)
					)
				);
			}
		}

		return true;
	}

	/**
	 * @param $request
	 * @param $requestType
	 *
	 * @return bool
	 */
	private function getPeriodsData($request, $requestType) {
		switch($requestType) {
			case self::AJAX_REQUEST:
				if(empty($request['periods'])) {
					return false;
				}

				parse_str($request['periods'], $data);

				return $data['tour_booking'];

				break;
			case self::SAVE_POST_REQUEST:
				if(empty($request['tour_booking'])) {
					return false;
				}

				return $request['tour_booking'];

				break;
		}
	}

	/**
	 * @param $tourId
	 *
	 * @param bool $excludePastPeriods
	 *
	 * @return array|bool
	 */
	public function getTourDates($tourId, $excludePastPeriods = false) {
		global $wpdb;

		$sql = "SELECT {$wpdb->prefix}tour_dates.*, {$wpdb->prefix}tour_times.id as time_id, {$wpdb->prefix}tour_times.time 
				FROM {$wpdb->prefix}tour_dates 
				LEFT JOIN {$wpdb->prefix}tour_times ON {$wpdb->prefix}tour_dates.id = {$wpdb->prefix}tour_times.tour_date_id 
				WHERE {$wpdb->prefix}tour_dates.tour_id = %d";

		if($excludePastPeriods) {
			$sql .= " AND NOW() <= {$wpdb->prefix}tour_dates.end_date";
		}

		$result = $wpdb->get_results(
			$wpdb->prepare($sql, $tourId)
		);

		if(empty($result)) {
			return false;
		}

		$formatedResult = $this->formatTourDates($result);

		return $formatedResult;
	}

	/**
	 * Loops through DB result and formates tour dates
	 * in a way that each array item has times property
	 *
	 * @param $result
	 *
	 * @return array
	 */
	private function formatTourDates($result) {
		$formatedResults = array();

		foreach($result as $item) {
			$item->days = maybe_unserialize($item->days);

			if(!array_key_exists($item->id, $formatedResults)) {
				$timeInstance = array();

				if($item->time_id && $item->time) {
					$timeInstance['id']   = $item->time_id;
					$timeInstance['time'] = $item->time;

					$item->times = array($timeInstance);
				}

				unset($item->time_id);
				unset($item->time);

				$formatedResults[$item->id] = $item;
			} else {

				if($item->time_id && $item->time) {
					$timeInstance = array(
						'id'   => $item->time_id,
						'time' => $item->time
					);

					$formatedResults[$item->id]->times[] = $timeInstance;
				}
			}
		}

		return $formatedResults;
	}

	/**
	 * Loops through tour dates array and extracts only tour times
	 *
	 * @param $tourDates
	 *
	 * @return array|bool
	 */
	private function extractTourTimes($tourDates) {
		$tourTimes = array();

		if(!(is_array($tourDates) && count($tourDates))) {
			return false;
		}

		foreach($tourDates as $tourDate) {
			if(empty($tourDate->times) || !(is_array($tourDate->times) && count($tourDate->times))) {
				continue;
			}

			foreach($tourDate->times as $time) {
				$tourTimes[$time['id']] = $time;
			}
		}

		return $tourTimes;
	}

	public function getAvaiableDays($periods) {
		if(empty($periods) || !(is_array($periods) && count($periods))) {
			return false;
		}

		$availableDates = array();
		$currentDate = date('Y-m-d');

		foreach($periods as $period) {
			$startDate = $period->start_date;
			$endDate = $period->end_date;

			$days = $period->days;

			foreach($days as $day) {
				//if day empty because of the checkbox do not calculate it
				if ($day === '') {
					continue;
				}
				for($i = strtotime($day, strtotime($startDate)); $i <= strtotime($endDate); $i = strtotime('+1 week', $i)) {
					if($i >= strtotime($currentDate)) {
						$availableDates[] = date('Y-m-d', $i);
					}
				}
			}
		}

		return $availableDates;
	}

	public function getStartDateWithTimes($periods) {
		if(empty($periods) || !(is_array($periods) && count($periods))) {
			return false;
		}

		$datesWithTimes = array();

		foreach($periods as $period) {
			$member = array(
				'periodId' => $period->id,
				'startDate' => $period->start_date,
				'endDate' => $period->end_date
			);

			if(!empty($period->times)) {
				$member['times'] = $period->times;
			}

			$datesWithTimes[] = $member;
		}

		return $datesWithTimes;
	}

	public function getTourPeriodFromDate($id, $date) {
		global $wpdb;

		if(!$date || !$id) {
			return false;
		}

		$date = date('Y-m-d', strtotime($date));

		$sql = "SELECT * FROM {$wpdb->prefix}tour_dates
				WHERE DATE('%s') >= {$wpdb->prefix}tour_dates.start_date
				AND DATE('%s') <= {$wpdb->prefix}tour_dates.end_date
				AND {$wpdb->prefix}tour_dates.tour_id = '%d'";

		$results = $wpdb->get_results(
			$wpdb->prepare($sql, array(
				$date,
				$date,
				$id
			))
		);

		$lastQuery = $wpdb->last_query;

		if(!$results) {
			return false;
		}

		$result = array_shift($results);

		return $result;
	}

	public function getNumberOfTicketsForDate($tourId, $date) {
		$period = $this->getTourPeriodFromDate($tourId, $date);

		if(!$period || empty($period->number_of_tickets)) {
			return false;
		}

		return $period->number_of_tickets;
	}
}

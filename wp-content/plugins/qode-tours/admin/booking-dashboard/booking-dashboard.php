<?php

namespace QodeTours\Admin\BookingDashboard;

class BookingSubmenuPage {

	const APPROVED = 'Approved';
	const CANCELED = 'Canceled';

	/**
	 * @var private instance of current class
	 */
	private static $instance;

	/**
	 * @var string Page Title
	 */
	private $page_title;

	/**
	 * @var string Page Slug
	 */
	private $page_slug;

	/**
	 * @var string User Permissions to access page
	 */
	private $permissions;

	/**
	 * @var string menu where page will be registered
	 */
	private $menu;

	/**
	 * Private constuct because of Singletone
	 */
	private function __construct() {
		$this->page_title  = esc_html__( 'Booking', 'qode-tours' );
		$this->menu        = 'edit.php?post_type=tour-item';
		$this->permissions = 'manage_options';
		$this->page_slug   = 'qode-tours-booking';

		add_action( 'admin_menu', array( $this, 'registerSubmenuPage' ) );
		add_action( 'wp_ajax_qodeToursChangeBookingStatus', array( $this, 'qodeToursChangeBookingStatus' ) );
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
	 * @return BookingSubmenuPage
	 */
	public static function getInstance() {
		if ( self::$instance == null ) {
			return new self;
		}

		return self::$instance;
	}

	/**
	 * Register Submenu Page
	 */
	public function registerSubmenuPage() {

		add_submenu_page(
			$this->menu,
			$this->page_title,
			$this->page_title,
			$this->permissions,
			$this->page_slug,
			array( $this, 'renderSubmenuPage' )
		);

	}

	/**
	 * Render Submenu Page
	 */
	public function renderSubmenuPage() {

		$bookings_table = new ToursBookingTable();

		if(!empty($_GET['booking-id'])) {
			echo 'here';
		} else {		?>
		<div class="wrap">
			<div class="qode-booking-dashboard">
				<h2><?php esc_html_e('Qode Tours Bookings', 'qode-tours'); ?></h2>
				<div class="qode-booking-dash-notice"></div>
				<div class="qode-booking-form-holder">
					<form method="post">
						<?php
						$bookings_table->prepare_items();
						$bookings_table->display();
						?>
					</form>
				</div>
			</div>
		</div>
		<?php
		}
	}

	/**
	 * Approve Booking
	 *
	 * Ajax Callable
	 */
	public function qodeToursChangeBookingStatus() {

		if ( ! isset( $_POST ) ) {
			return;
		}

		$to         = $_POST['newStatus'];
		$booking_id = $_POST['bookingId'];

		if ( $to == 'approve' ) {
			$new_status = self::APPROVED;
		} elseif ( $to == 'cancel' ) {
			$new_status = self::CANCELED;
		}

		global $wpdb;
		$table = $wpdb->prefix . 'tour_bookings';

		$update = $wpdb->update(
			$table,
			array(
				'status' => $new_status
			),
			array(
				'id' => $booking_id
			)
		);

		if ( $update ) {

			$this->qodeToursSendApproveEmail($booking_id, $to);
			$response = array(
				'message' => esc_html__( 'Booking Status successfully changed!', 'qode-tours' ),
				'status'  => 'success',
			);
		} else {
			$response = array(
				'message' => esc_html__( 'Booking Status cannot be changed!', 'qode-tours' ),
				'status'  => 'error'
			);
		}

		$response = json_encode( $response );
		exit( $response );

	}

	/**
	 * Approve Booking Email
	 *
	 * Ajax Callable
	 */
	public function qodeToursSendApproveEmail($booking_id, $status) {
		$booking = $this->getBookingById($booking_id);

		$mail_to = $booking['user_email'];
		$tour_id = $booking['tour_id'];
		$tour_title = get_the_title($tour_id);
		$name = get_bloginfo('name');
		$email = get_bloginfo('admin_email');
		$booking_time_html = '';
		if(!empty($booking['booking_time'])) {
			$booking_time_html =  $booking['booking_time'] . 'h';
		}


		if ( $status == 'approve' ) {
			$subject  = esc_html__('Booking For', 'qode-tours') . ' '. $tour_title . ' '. esc_html__('on', 'qode-tours') . ' ' . $name .' '. esc_html__('confirmed', 'qode-tours');
			$message = esc_html__('We have succesfully received your payment for','qode-tours') .  ' '. $tour_title . ' '. esc_html__('on', 'qode-tours') . ' ' . $name;
		} elseif ( $status == 'cancel' ) {
			$subject  = esc_html__('Booking For', 'qode-tours') . ' '. $tour_title . ' '. esc_html__('on', 'qode-tours') . ' ' . $name .' '. esc_html__('canceled', 'qode-tours');
			$message = esc_html__('There was an error with your payment for','qode-tours') .  ' '. $tour_title . ' '. esc_html__('on', 'qode-tours') . ' ' . $name .' '. esc_html__('Please contact the administrator for further information', 'qode-tours');
		}

		$headers = array(
			'From: ' . $name . ' <' . $email . '>',
			'Reply-To: ' . $name . ' <' . $email . '>',
		);

		$messageTemplate = esc_html__('From', 'qode-tours'). ': ' . $name . "\r\n";
		$messageTemplate .= esc_html__('Message', 'qode-tours') . ': ' . $message . "\r\n\n";
		$messageTemplate .= esc_html__('Number of Tickets', 'qode-tours') . ': ' . $booking['amount'] . "\r\n\n";
		$messageTemplate .= esc_html__('Price', 'qode-tours') . ': ' . $booking['price'] . "\r\n\n";
		$messageTemplate .= esc_html__('Departure Date', 'qode-tours') . ': ' . date(get_option('date_format'), strtotime($booking['booking_date'])) . $booking_time_html . "\r\n\n";

		wp_mail(
			$mail_to, //Mail To
			$subject, //Subject
			$messageTemplate, //Message
			$headers //Additional Headers
		);
	}

	public function getBookingById($id) {
		global $wpdb;

		$table_name = $wpdb->prefix.'tour_bookings';

		$sql = "SELECT * FROM ".$table_name." WHERE id = ". $id;

		$result = $wpdb->get_row($sql,ARRAY_A);

		return $result;
	}
}

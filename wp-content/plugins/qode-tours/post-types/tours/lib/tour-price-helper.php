<?php

namespace QodeTours\CPT\Tours\Lib;

use QodeTours\Admin\MetaBoxes\TourBooking\TourTimeStorage;

/**
 * Class TourPriceHelper
 */
class TourPriceHelper {
	/**
	 *
	 */
	const CURRENCY_SYMBOL = '$';
	/**
	 *
	 */
	const CURRENCY_POSITION = 'left';

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
	 * @return ShortcodeLoader
	 */
	public static function getInstance() {
		if(self::$instance === null) {
			return new self;
		}

		return self::$instance;
	}

	/**
	 * @param null $id
	 * @param bool $formatted
	 *
	 * @return bool|mixed|string
	 */
	public function getOriginalPrice($id = null, $formatted = true) {
		$id = empty($id) ? get_the_ID() : $id;

		$priceField = get_post_meta($id, 'qode_tours_price', true);

		if(empty($priceField)) {
			return false;
		}

		return $formatted ? $this->formatPrice($priceField) : $priceField;
	}

	/**
	 * @param $id
	 * @param bool $formatted
	 *
	 * @return bool|mixed|string
	 */
	public function getDiscountPrice($id, $formatted = true) {
		$id = empty($id) ? get_the_ID() : $id;

		$priceField = get_post_meta($id, 'qode_tours_discount_price', true);

		if(empty($priceField)) {
			return false;
		}

		return $formatted ? $this->formatPrice($priceField) : $priceField;
	}

	/**
	 * @param $id
	 * @param $date
	 * @param bool $formatted
	 *
	 * @return bool|mixed|string
	 */
	public function getPeriodPrice($id, $date, $formatted = false) {
		if(!$id) {
			return false;
		}

		$priceWithDiscount = $this->getPriceWithDiscount($id, $formatted);

		if(!$date && $id) {
			return $priceWithDiscount;
		}

		$period = TourTimeStorage::getInstance()->getTourPeriodFromDate($id, $date);

		if(!$period) {
			return $priceWithDiscount;
		}

		$priceChange   = $period->price_change;
		$originalPrice = $this->getOriginalPrice($id, false);

		if(!$priceChange) {
			return $priceWithDiscount;
		}

		$calculatedPrice = $this->calculatePriceChange($originalPrice, $priceChange);

		return $formatted ? $this->formatPrice($calculatedPrice) : $calculatedPrice;
	}

	/**
	 * @param $originalPrice
	 * @param $priceChange
	 *
	 * @return bool
	 */
	public function calculatePriceChange($originalPrice, $priceChange) {
		if(!$priceChange) {
			return $originalPrice;
		}

		$percentageBased = qode_tours_string_ends_with($priceChange, '%');

		if($percentageBased) {
			return $this->calculatePricePercentage($originalPrice, $priceChange);
		}

		$additionBased = qode_tours_string_starts_with($priceChange, '+');

		if($additionBased) {
			return $this->calculatePriceAddition($originalPrice, $priceChange);
		}

		$substractionBased = qode_tours_string_starts_with($priceChange, '-');

		if($substractionBased) {
			return $this->calculatePriceSubstraction($originalPrice, $priceChange);
		}

		return is_numeric($priceChange) ? $priceChange : $originalPrice;
	}

	/**
	 * @param $price
	 * @param $percentage
	 *
	 * @return bool
	 */
	public function calculatePricePercentage($price, $percentage) {
		if(!$price || !$percentage) {
			return false;
		}

		$percentageNumber = strstr(trim($percentage), '%', true);

		if(!is_numeric($percentageNumber)) {
			return $price;
		}

		return $price * ($percentageNumber / 100);
	}

	/**
	 * @param $price
	 * @param $addition
	 *
	 * @return bool
	 */
	public function calculatePriceAddition($price, $addition) {
		if(!$price || !$addition) {
			return false;
		}

		$additionNumber = substr(trim($addition), -(strlen($addition) - 1));

		if(!is_numeric($addition)) {
			return $price;
		}

		return $price + $additionNumber;
	}

	/**
	 * @param $price
	 * @param $substraction
	 *
	 * @return bool
	 */
	public function calculatePriceSubstraction($price, $substraction) {
		if(!$price || !$substraction) {
			return false;
		}

		$substractionNumber = substr(trim($substraction), -(strlen($substraction) - 1));

		if(!is_numeric($substractionNumber)) {
			return $price;
		}

		if($substractionNumber >= $price) {
			return $price;
		}

		return $price - $substractionNumber;
	}

	/**
	 * @param $id
	 *
	 * @param bool $formatted
	 *
	 * @return bool|mixed|string
	 */
	public function getPriceWithDiscount($id, $formatted = true) {
		$discountPrice = $this->getDiscountPrice($id, $formatted);

		if(!$discountPrice) {
			return $this->getOriginalPrice($id, $formatted);
		}

		return $discountPrice;
	}

	/**
	 * @param $price
	 *
	 * @return bool|string
	 */
	public function formatPrice($price) {
		if(empty($price)) {
			return false;
		}

		$price = round( floatval( $price ), 2);

		$currencySymbol   = apply_filters('qode_tours_currency_symbol', $this->getCurrencySymbol());
		$currencyPosition = apply_filters('qode_tours_currency_position', $this->getCurrencyPosition());

		$formattedPrice = $currencyPosition === 'left' ? $currencySymbol.$price : $price.$currencySymbol;

		return apply_filters('qode_tours_format_price', $formattedPrice, $price, $currencyPosition, $currencySymbol);
	}

	public function getRawPrice($price) {
		if(!$price) {
			return false;
		}

		$currencySymbol   = apply_filters('qode_tours_currency_symbol', $this->getCurrencySymbol());
		$currencySymbolLength = strlen($currencySymbol);
		$currencyPosition = apply_filters('qode_tours_currency_position', $this->getCurrencyPosition());
		$rawPrice = 0;

		switch($currencyPosition) {
			case 'left':
				$rawPrice = substr($price, $currencySymbolLength, strlen($price) - $currencySymbolLength);
				break;
			case 'right':
				$rawPrice = substr($price, 0, strlen($price) - $currencySymbolLength);
				break;
		}

		return $rawPrice;
	}

	/**
	 * @return bool|mixed|string|void
	 */
	public function getCurrencySymbol() {
		if(!qode_tours_theme_installed()) {
			return self::CURRENCY_SYMBOL;
		}

		$symbol = bridge_qode_options()->getOptionValue('tours_currency_symbol');

		if(!$symbol) {
			return self::CURRENCY_SYMBOL;
		}

		return apply_filters('qode_tours_currency_symbol', $symbol);
	}

	/**
	 * @return bool|mixed|string|void
	 */
	public function getCurrencyPosition() {
		if(!qode_tours_theme_installed()) {
			return self::CURRENCY_POSITION;
		}

		$position = bridge_qode_options()->getOptionValue('tours_currency_symbol_position');

		if(!$position) {
			return self::CURRENCY_POSITION;
		}

		return apply_filters('qode_tours_currency_position', $position);
	}

	/**
	 * @return bool
	 */
	public function getMinPrice() {
		$edgePrices = $this->getEdgePrices();

		if(!$edgePrices) {
			return false;
		}

		return $edgePrices->min_price;
	}

	/**
	 * @return bool
	 */
	public function getMaxPrice() {
		$edgePrices = $this->getEdgePrices();

		if(!$edgePrices) {
			return false;
		}

		return $edgePrices->max_price;
	}

	/**
	 * @return array|bool|null|object
	 */
	public function getEdgePrices() {
		global $wpdb;
		
		if(qode_tours_is_wpml_installed()) {
			$lang = ICL_LANGUAGE_CODE;
			
			$sql = "SELECT MAX(CAST(pm.meta_value AS UNSIGNED)) as max_price,
					MIN(CAST(pm.meta_value AS UNSIGNED)) as min_price
					FROM {$wpdb->prefix}postmeta pm
					LEFT JOIN {$wpdb->prefix}posts p ON p.ID = pm.post_id 
					LEFT JOIN {$wpdb->prefix}icl_translations icl_t ON icl_t.element_id = p.ID
					WHERE pm.meta_key IN ('qode_tours_price', 'qode_tours_discount_price')
				    AND icl_t.language_code='$lang'";
		} else {
			$sql = "SELECT MAX(CAST(pm.meta_value AS UNSIGNED)) as max_price,
					MIN(CAST(pm.meta_value AS UNSIGNED)) as min_price 
					FROM {$wpdb->prefix}postmeta pm 
					WHERE pm.meta_key IN ('qode_tours_price', 'qode_tours_discount_price')";
		}

		$result = $wpdb->get_results($sql);

		if(!$result) {
			return false;
		}

		return array_shift($result);
	}
}

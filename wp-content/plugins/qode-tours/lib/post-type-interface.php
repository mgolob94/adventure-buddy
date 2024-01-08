<?php
namespace QodeTours\Lib;

/**
 * interface PostTypeInterface
 * @package QodeTours\Lib;
 */
interface PostTypeInterface {
	/**
	 * @return string
	 */
	public function getBase();

	/**
	 * Registers custom post type with WordPress
	 */
	public function register();
}
<?php
namespace QodeTours\CPT\Destination;

use QodeTours\Lib;

/**
 * Class DestinationsRegister
 * @package QodeTours\CPT\Destinations
 */
class DestinationsRegister implements Lib\PostTypeInterface {
	/**
	 * @var string
	 */
	private $base;
	/**
	 * @var string
	 */
	private $taxBase;

	public function __construct() {
		$this->base    = 'destinations';

		add_filter('single_template', array($this, 'registerSingleTemplate'));
	}

	/**
	 * Registers listing-item single template if one does'nt exists in theme.
	 * Hooked to single_template filter
	 *
	 * @param $single string current template
	 *
	 * @return string string changed template
	 */
	public function registerSingleTemplate($single) {
		global $post;

		if($post->post_type == $this->base) {
			if(!file_exists(get_template_directory().'/single-'.$this->base.'.php')) {
				return QODE_TOURS_CPT_PATH.'/destinations/templates/single-'.$this->base.'.php';
			}
		}

		return $single;
	}

	/**
	 * @return string
	 */
	public function getBase() {
		return $this->base;
	}

	/**
	 * Registers custom post type with WordPress
	 */

	public function register() {
		$this->registerPostType();
	}

	/**
	 * Registers custom post type with WordPress
	 */
	private function registerPostType() {
		global $qode_Framework;

		$menuPosition = 12;
		$menuIcon     = 'dashicons-admin-post';

		$slug         = $this->base;

		register_post_type($this->base,
			array(
				'labels'        => array(
					'name'          => esc_html__('Qode Destination', 'qode-tours'),
					'menu_name'     => esc_html__('Qode Destination', 'qode-tours'),
					'all_items'     => esc_html__('Destination Items', 'qode-tours'),
					'add_new'       => esc_html__('Add New Destination Item', 'qode-tours'),
					'singular_name' => esc_html__('Destination Item', 'qode-tours'),
					'add_item'      => esc_html__('New Destination Item', 'qode-tours'),
					'add_new_item'  => esc_html__('Add New Destination Item', 'qode-tours'),
					'edit_item'     => esc_html__('Edit Destination Item', 'qode-tours')
				),
				'public'        => true,
				'has_archive'   => true,
				'rewrite'       => array('slug' => $slug),
				'menu_position' => $menuPosition,
				'show_ui'       => true,
				'show_in_menu'  => true,
				'supports'      => array(
					'author',
					'title',
					'editor',
					'thumbnail',
					'excerpt',
					'page-attributes',
					'comments'
				),
				'menu_icon'     => $menuIcon
			)
		);
	}
}
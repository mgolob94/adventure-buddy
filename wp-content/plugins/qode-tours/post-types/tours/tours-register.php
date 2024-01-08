<?php
namespace QodeTours\CPT\Tours;

use QodeTours\Lib;

/**
 * Class ToursRegister
 * @package QodeTours\CPT\Tours
 */
class ToursRegister implements Lib\PostTypeInterface {
    /**
     * @var string
     */
    private $base;
    /**
     * @var string
     */
    private $taxBase;

    public function __construct() {
        $this->base    = 'tour-item';
        $this->taxBase = 'tour-category';
        add_filter('single_template', array($this, 'registerSingleTemplate'));

        add_action('admin_menu', array($this, 'removeReviewCriteriaMetaBox'));
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
        $this->registerTax();
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
            if(!file_exists(get_template_directory().'/single-tour-item.php')) {
                return QODE_TOURS_CPT_PATH.'/tours/templates/single-'.$this->base.'.php';
            }
        }

        return $single;
    }

    /**
     * Registers custom post type with WordPress
     */
    private function registerPostType() {
        global $qode_Framework;

        $menuPosition = 11;
        $menuIcon     = 'dashicons-palmtree';
	    
        $slug = $this->base;

        register_post_type($this->base,
            array(
                'labels'        => array(
                    'name'          => esc_html__('Qode Tour', 'qode-tours'),
                    'menu_name'     => esc_html__('Qode Tour', 'qode-tours'),
                    'all_items'     => esc_html__('Tour Items', 'qode-tours'),
                    'add_new'       => esc_html__('Add New Tour Item', 'qode-tours'),
                    'singular_name' => esc_html__('Tour Item', 'qode-tours'),
                    'add_item'      => esc_html__('New Tour Item', 'qode-tours'),
                    'add_new_item'  => esc_html__('Add New Tour Item', 'qode-tours'),
                    'edit_item'     => esc_html__('Edit Tour Item', 'qode-tours')
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

    /**
     * Registers custom taxonomy with WordPress
     */
    private function registerTax() {
        $labels = array(
            'name'              => esc_html__('Tours Categories', 'qode-tours'),
            'singular_name'     => esc_html__('Tour Category', 'qode-tours'),
            'search_items'      => esc_html__('Search Tours Categories', 'qode-tours'),
            'all_items'         => esc_html__('All Tours Categories', 'qode-tours'),
            'parent_item'       => esc_html__('Parent Tour Category', 'qode-tours'),
            'parent_item_colon' => esc_html__('Parent Tour Category:', 'qode-tours'),
            'edit_item'         => esc_html__('Edit Tour Category', 'qode-tours'),
            'update_item'       => esc_html__('Update Tour Category', 'qode-tours'),
            'add_new_item'      => esc_html__('Add New Tour Category', 'qode-tours'),
            'new_item_name'     => esc_html__('New Tour Category Name', 'qode-tours'),
            'menu_name'         => esc_html__('Tours Categories', 'qode-tours'),
        );

        register_taxonomy($this->taxBase, array($this->base), array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'query_var'         => true,
            'show_admin_column' => true,
            'rewrite'           => array('slug' => 'tour-category'),
        ));

        register_taxonomy('review-criteria', array($this->base), array(
            'hierarchical'      => true,
            'show_ui'           => true,
            'labels'            => array(
                'name'              => esc_html__('Review Criteria', 'qode-tours'),
                'singular_name'     => esc_html__('Review Criterion', 'qode-tours'),
                'search_items'      => esc_html__('Search Review Criteria', 'qode-tours'),
                'all_items'         => esc_html__('All Review Criteria', 'qode-tours'),
                'parent_item'       => esc_html__('Parent Review Criterion', 'qode-tours'),
                'parent_item_colon' => esc_html__('Parent Review Criterion:', 'qode-tours'),
                'edit_item'         => esc_html__('Edit Review Criterion', 'qode-tours'),
                'update_item'       => esc_html__('Update Review Criterion', 'qode-tours'),
                'add_new_item'      => esc_html__('Add New Review Criterion', 'qode-tours'),
                'new_item_name'     => esc_html__('New Review Criterion Name', 'qode-tours'),
                'menu_name'         => esc_html__('Review Criteria', 'qode-tours'),
            ),
            'query_var'         => true,
            'show_admin_column' => false,
        ));

        $attributes_labels = array(
            'name'              => esc_html__('Tours Attributes', 'qode-tours'),
            'singular_name'     => esc_html__('Tour Attribute', 'qode-tours'),
            'search_items'      => esc_html__('Search Tours Attributes', 'qode-tours'),
            'all_items'         => esc_html__('All Tours Attributes', 'qode-tours'),
            'parent_item'       => esc_html__('Parent Tour Attribute', 'qode-tours'),
            'parent_item_colon' => esc_html__('Parent Tour Attribute:', 'qode-tours'),
            'edit_item'         => esc_html__('Edit Tour Attribute', 'qode-tours'),
            'update_item'       => esc_html__('Update Tour Attribute', 'qode-tours'),
            'add_new_item'      => esc_html__('Add New Tour Attribute', 'qode-tours'),
            'new_item_name'     => esc_html__('New Tour Attribute Name', 'qode-tours'),
            'menu_name'         => esc_html__('Tours Attributes', 'qode-tours'),
        );

        register_taxonomy('tour-attribute', array($this->base), array(
            'hierarchical'      => true,
            'show_ui'           => true,
            'labels'            => $attributes_labels,
            'query_var'         => true,
            'show_admin_column' => false,
        ));
    }

    public function removeReviewCriteriaMetaBox() {
        //remove review criteria meta box from tour single page,
        //because we don't want user to check review criteria for each tour
        remove_meta_box('review-criteriadiv', $this->base, 'side');
    }
}
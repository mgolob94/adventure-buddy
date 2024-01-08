<?php
$twitterShortcode = new QodeTwitterShortcode();
add_shortcode($twitterShortcode->getBase(), array($twitterShortcode, 'render'));

if(!function_exists('qode_twitter_feed_include_elementor_shortcodes')) {
    function qode_twitter_feed_include_elementor_shortcodes() {
        if ( defined('ELEMENTOR_VERSION') ) {
            include_once 'elementor-qode-twitter-feed.php';
        }
    }

    add_action('bridge_core_load_elementor_shortcodes_from_plugins', 'qode_twitter_feed_include_elementor_shortcodes');
}
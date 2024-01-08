<?php

if ( ! function_exists( 'qode_include_reviews_shortcodes_files' ) ) {
    /**
     * Loades all shortcodes by going through all folders that are placed directly in shortcodes folder
     */
    function qode_include_reviews_shortcodes_files() {

        foreach ( glob( QODE_FRAMEWORK_MODULES_ROOT_DIR . '/reviews/shortcodes/*/load.php' ) as $shortcode_load ) {
            include_once $shortcode_load;
        }
    }

    add_action( 'bridge_qode_action_include_shortcodes_file', 'qode_include_reviews_shortcodes_files' );
}
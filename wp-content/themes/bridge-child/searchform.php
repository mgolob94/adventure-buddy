<?php
ob_start(); // Start output buffering

$categories = get_terms('product_cat', array('hide_empty' => false));
$locations = get_terms('product_location', array('hide_empty' => false));

?>

<!-- Begin Custom Search Form -->
<div class="custom-search-form-wrapper">
    <form role="search" method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>">
        <div class="flex justify-center search-form__wrapper">
            <div class="flex flex-col search-form__item">
                <label for="category"><?php _e('Aktivnost', 'text-domain'); ?></label>
                <select name="category" id="category">
                    <option value=""><?php _e('All Categories', 'text-domain'); ?></option>
                    <?php
                    foreach ($categories as $category) {
                        echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="flex flex-col search-form__item">
                <label for="location"><?php _e('Kraj:', 'text-domain'); ?></label>
                <select name="location" id="location">
                    <option value=""><?php _e('All Locations', 'text-domain'); ?></option>
                    <?php
                    foreach ($locations as $location) {
                        echo '<option value="' . esc_attr($location->slug) . '">' . esc_html($location->name) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="flex flex-col search-form__item">
                <label for="start_date"><?php _e('Od:', 'text-domain'); ?></label>
                <input type="date" name="start_date" id="start_date">
            </div>
            <div class="flex flex-col search-form__item">
                <label for="end_date"><?php _e('Do:', 'text-domain'); ?></label>
                <input type="date" name="end_date" id="end_date">
                <input type="hidden" name="post_type" value="product">
                <input type="hidden" name="booking_search" value="1">
            </div>
            <div class="flex flex-col justify-center">
                <button type="submit" id="searchsubmit"><img src="<?php echo get_stylesheet_directory_uri(); ?>/loupe.png" /></button>
            </div>
        </div>
    </form>
</div>

<!-- End Custom Search Form -->

<?php
$form_output = ob_get_clean(); // Capture the output and store in a variable
?>

<!-- Place the captured output where you want it to appear -->
<div class="custom-search-form-wrapper">
    <?php echo $form_output; ?>
</div>
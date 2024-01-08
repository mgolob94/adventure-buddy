<?php
    $new_reviews = $this_shortcode->getTopReviews($params);
?>
<div class="qode-top-reviews-carousel-holder <?php if(!empty($enable_shadow) && $enable_shadow == 'yes'){ echo ' qode-enabled-shadow'; } ?> ">
    <?php if(is_array($new_reviews) && count($new_reviews)) : ?>
        <div class="qode-top-reviews-carousel-inner">
            <?php if(!empty($title)) { ?>
                <<?php echo esc_attr($title_tag); ?> class="qode-top-reviews-carousel-title" <?php echo qode_tours_inline_style($title_style);?>><?php echo esc_html($title); ?></<?php echo esc_attr($title_tag); ?>>
            <?php } ?>

            <div class="qode-top-reviews-carousel qode-owl-slider">
                <?php foreach($new_reviews as $review) {
                    $params['comment'] = $review;
                    $item_params = $this_shortcode->generateItemParams($params);
                    echo qode_tours_get_tour_module_template_part( 'top-reviews-carousel/templates/top-reviews-carousel-item', 'tours', 'shortcodes', '', $item_params);
                }
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>
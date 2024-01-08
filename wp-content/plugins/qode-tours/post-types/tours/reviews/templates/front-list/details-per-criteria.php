<?php if(is_array($post_ratings) && count($post_ratings)) { ?>
    <?php $average_rating_total = qode_get_total_average_rating($post_ratings); ?>
    <div class="qode-reviews-list-info qode-reviews-per-criteria">
        <div class="qode-item-reviews-display-wrapper clearfix">
            <?php if(!empty($title)) { ?>
                <h3 class="qode-item-review-title"><?php echo esc_html($title); ?></h3>
            <?php } ?>

            <?php if(!empty($subtitle)) { ?>
                <p class="qode-item-review-subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php } ?>

            <div class="qode-grid-row">
                <div class="qode-grid-col-3">
                    <div class="qode-item-reviews-average-wrapper">
                        <div class="qode-item-reviews-average-rating">
                            <?php echo esc_html(qode_reviews_format_rating_output($average_rating_total)); ?>
                        </div>
                        <div class="qode-item-reviews-verbal-description">
                            <span class="qode-item-reviews-rating-description">
                                <?php echo esc_html(qode_reviews_get_description_for_rating($average_rating_total)); ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="qode-grid-col-9">
                    <div class="qode-rating-percentage-wrapper">
                        <?php
                        foreach($post_ratings as $rating) {
                            $percentage = qode_post_average_rating_per_criteria($rating);
                            echo do_shortcode( '[progress_bar percent="' . $percentage . '" title="' . $rating['label'] . '"]' );
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }
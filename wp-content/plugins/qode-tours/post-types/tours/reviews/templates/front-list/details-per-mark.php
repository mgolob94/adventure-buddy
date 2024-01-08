<div class="qode-reviews-list-info qode-reviews-per-mark">
    <?php foreach($post_ratings as $rating) { ?>
        <?php
            $average_rating = qode_post_average_rating($rating);
            $rating_count = $rating['count'];
            $rating_count_label = $rating_count === 1 ? esc_html__('Rating', 'qode-tours') : esc_html__('Ratings', 'qode-tours');
            $rating_marks = $rating['marks'];
        ?>
        <div class="qode-grid-row">
            <div class="qode-grid-col-4">
                <div class="qode-reviews-criteria-title-holder">
                    <span class="qode-review-criteria-name"><?php echo $rating['label']; ?></span>
                </div>
                <div class="qode-reviews-number-wrapper">
                    <span class="qode-reviews-number"><?php echo esc_html($average_rating); ?></span>
                    <span class="qode-stars-wrapper">
                        <span class="qode-stars">
                            <?php
                            for ( $i = 1; $i <= $average_rating; $i ++ ) { ?>
                                <i class="fa fa-star" aria-hidden="true"></i>
                            <?php } ?>
                        </span>
                        <span class="qode-reviews-count">
                            <?php echo esc_html__( 'Rated', 'qode-tours' ) . ' ' . $average_rating . ' ' . esc_html__( 'out of', 'qode-tours' ) . ' ' . $rating_count . ' ' . $rating_count_label; ?>
                        </span>
                    </span>
                </div>
            </div>
            <div class="qode-grid-col-8">
                <div class="qode-rating-percentage-wrapper">
                    <?php
                    foreach ( $rating_marks as $item => $value ) {
                        $percentage = $rating_count == 0 ? 0 : round( ( $value / $rating_count ) * 100 );
                        echo do_shortcode( '[progress_bar percent="' . $percentage . '" title="' . $item . esc_html__( ' stars', 'qode-tours' ) . '"]' );
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

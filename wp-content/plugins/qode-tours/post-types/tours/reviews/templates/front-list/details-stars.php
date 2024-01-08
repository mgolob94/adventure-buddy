<span class="qode-stars">
    <?php foreach($post_ratings as $rating) { ?>
        <span class="qode-stars-wrapper-inner">
            <span class="qode-stars-items">
                <?php
                $review_rating = qode_post_average_rating($rating);
                for ($i = 1; $i <= $review_rating; $i++) { ?>
                    <i class="fa fa-star" aria-hidden="true"></i>
                <?php } ?>
            </span>
            <span class="qode-stars-label">
                <?php echo esc_html($rating['label']); ?>
            </span>
        </span>
    <?php } ?>
</span>
<a itemprop="url" class="qode-post-info-comments" href="<?php comments_link(); ?>" target="_self">
    <span class="qode-reviews-number">
        <?php echo esc_html($rating_number); ?>
    </span>
    <span class="qode-reviews-label">
        <?php echo esc_html($rating_label); ?>
    </span>
</a>
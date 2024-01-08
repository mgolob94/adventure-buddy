<div class="qode-comment-form-rating">
    <label><?php echo esc_html($label); ?><span class="required">*</span></label>
    <span class="qode-comment-rating-box">
        <?php for ( $i = 1; $i <= QODE_REVIEWS_MAX_RATING; $i ++ ) { ?>
            <span class="qode-star-rating" data-value="<?php echo esc_attr( $i ); ?>"></span>
        <?php } ?>
        <input type="hidden" name="<?php echo esc_attr($key); ?>" class="qode-rating" value="3">
    </span>
</div>
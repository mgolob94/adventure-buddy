<li>
    <div class="<?php echo esc_attr( $comment_class ); ?>">
        <?php if ( ! $is_pingback_comment ) { ?>
            <div class="qode-comment-image"> <?php echo bridge_qode_kses_img( get_avatar( $comment, 'thumbnail' ) ); ?> </div>
        <?php } ?>
        <div class="qode-comment-text">
            <div class="qode-comment-info">
                <h5 class="qode-comment-name vcard">
                    <?php echo wp_kses_post( get_comment_author_link( $comment->comment_ID) ); ?>
                </h5>
            </div>
            <?php if ( ! $is_pingback_comment ) { ?>
                <div class="qode-text-holder" id="comment-<?php echo esc_attr($comment->comment_ID); ?>">
                    <div class="qode-review-title">
                        <span><?php echo esc_html( $review_title ); ?></span>
                    </div>
                    <?php comment_text($comment->comment_ID); ?>
                </div>
                <div class="qode-review-rating">
                    <?php foreach($rating_criteria as $rating) { ?>
                        <?php if(!isset($rating['show']) || (isset($rating['show']) && $rating['show'])) { ?>
                            <span class="qode-rating-inner">
                                <span class="qode-rating-label">
                                    <?php echo esc_html($rating['label']); ?>
                                </span>
                                <span class="qode-rating-value">
                                    <?php
                                        $review_rating = get_comment_meta( $comment->comment_ID, $rating['key'], true );
                                        for ( $i = 1; $i <= $review_rating; $i ++ ) { ?>
                                        <i class="icon_star" aria-hidden="true"></i>
                                    <?php }
                                        while( $i <= QODE_REVIEWS_MAX_RATING ){
                                            ?>
                                            <i class="icon_star_alt" aria-hidden="true"></i>
                                            <?php
                                            $i++;
                                        }
                                    ?>
                                </span>
                            </span>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
<!-- li is closed by wordpress after comment rendering -->
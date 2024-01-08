<?php
if (!isset($title_tag) || $title_tag == ''){
	$title_tag = 'h4';
}

$id = get_the_ID();
$image_size = get_post_meta($id, 'qode_tours_masonry_dimensions', true);
$image_dimension = qode_tours_get_image_size_param(array('image_size' => $image_size));
$item_classes = array(
    'qode-tours-masonry-item',
    'qode-tours-row-item',
    'qode-item-space',
    qode_tours_get_tour_rating_class(),
    qode_tours_get_tour_masonry_class()
);
?>
<div <?php post_class($item_classes); ?>>
    <div class="qode-tours-gim-holder-inner" <?php echo qode_tours_inline_style($description_border_color); ?>>
        <div class="qode-tours-gim-content-outer">
            <div class="qode-tours-gim-content-inner">
                <div class="qode-tours-gim-title-holder">
                    <<?php echo esc_attr($title_tag);?> class="qode-tour-title">
                        <?php the_title(); ?>
                    </<?php echo esc_attr($title_tag);?>>
                    <span class="qode-tours-gallery-item-price-holder">
                        <?php echo qode_tours_get_tour_price_html(); ?>
                    </span>
                </div>
                <?php if(qode_tours_get_tour_excerpt()) : ?>
                    <div class="qode-tours-gim-excerpt">
                        <div class="qode-tours-gim-excerpt-inner">
                            <?php echo qode_tours_get_tour_excerpt($text_length); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="qode-tours-gim-button">
                    <?php echo qode_tours_get_tour_button(); ?>
                </div>
            </div>
        </div>
        <a class="qode-tours-masonry-item-link" href="<?php the_permalink(); ?>"></a>
    </div>
</div>
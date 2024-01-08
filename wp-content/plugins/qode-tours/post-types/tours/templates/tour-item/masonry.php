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
    <div class="qode-tour-item-inner">
        <div class="qode-tours-gim-holder-inner">
            <div class="qode-tours-gim-image">
                <?php echo qode_tours_get_tour_image_html($image_dimension, true); ?>
            </div>
            <div class="qode-tours-gim-content-holder" <?php echo qode_tours_inline_style($background_hover_color); ?>>
                <div class="qode-tours-gim-content-outer">
                    <div class="qode-tours-gim-content-inner">
                        <div class="qode-gim-title-holder">
                            <<?php echo esc_attr($title_tag);?> class="qode-tour-title">
                                <?php the_title(); ?>
                            </<?php echo esc_attr($title_tag);?>>
                        </div>
                        <div class="qode-tours-gim-price-holder">
                            <?php echo qode_tours_get_tour_price_html(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <a class="qode-tours-masonry-item-link" href="<?php the_permalink(); ?>"></a>
        </div>
    </div>
</div>
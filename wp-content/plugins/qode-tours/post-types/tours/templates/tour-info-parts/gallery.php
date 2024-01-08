<?php
$gallery_excerpt   = get_post_meta(get_the_ID(), 'qode_tours_gallery_excerpt', true);
$image_gallery_val = get_post_meta(get_the_ID(), 'qode_tours_gallery_images', true);

if($image_gallery_val !== '') : ?>

    <div class="qode-tour-gallery-item-holder">

        <h4 class="qode-gallery-title">
            <?php esc_html_e('Our gallery', 'qode-tours'); ?>
        </h4>

        <p class="qode-tour-gallery-item-excerpt">
            <?php echo wp_kses_post($gallery_excerpt); ?>
        </p>

        <div class="qode-tour-gallery clearfix">
            <?php
            $image_gallery_array = explode(',', $image_gallery_val);
            if(isset($image_gallery_array) && count($image_gallery_array)) : ?>

                <?php for($i = 0; $i < 6; $i++) : ?>
                    <?php if(isset($image_gallery_array[$i])) : ?>
                        <div class="qode-tour-gallery-item">
                            <a href="<?php echo wp_get_attachment_url($image_gallery_array[$i]) ?>" data-rel="prettyPhoto[gallery_excerpt_pretty_photo]">
                                <?php echo wp_get_attachment_image($image_gallery_array[$i], 'portfolio-landscape'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endfor; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
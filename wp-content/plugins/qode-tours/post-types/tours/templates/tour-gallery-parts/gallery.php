<?php
$image_gallery_val = get_post_meta(get_the_ID(), 'qode_tours_gallery_images', true);

if($image_gallery_val !== "") { ?>
    <div class="qode-tour-masonry-gallery-holder">
        <div class="qode-tour-masonry-gallery clearfix">
	        <div class="qode-tour-grid-sizer"></div>
	        <div class="qode-tour-grid-gutter"></div>
            <?php
            if($image_gallery_val != '') {
                $image_gallery_array = explode(',', $image_gallery_val);
            }

            if(isset($image_gallery_array) && count($image_gallery_array) != 0) {
                foreach($image_gallery_array as $gimg_id) {
	                $image_size = get_post_meta($gimg_id, 'tours_gallery_masonry_image_size', true);
                    $image_classs = !empty($image_size) ? $image_size : 'qode-default-masonry-item';
                    ?>
                    <div class="qode-tour-gallery-item <?php echo esc_attr($image_classs); ?>">
	                    <div class="qode-tour-gallery-item-inner">
		                    <a href="<?php echo wp_get_attachment_url($gimg_id) ?>" data-rel="prettyPhoto[gallery_pretty_photo]">
			                    <?php echo wp_get_attachment_image($gimg_id, 'full'); ?>
		                    </a>
	                    </div>
                    </div>
                <?php }
            }
            ?>
        </div>
    </div>
<?php } ?>
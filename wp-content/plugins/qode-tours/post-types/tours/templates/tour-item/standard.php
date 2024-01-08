<?php
if (!isset($title_tag) || $title_tag == ''){
	$title_tag = 'h4';
}

if (!isset($thumb_size) || $thumb_size == ''){
	$thumb_size = 'full';
}
?>
<div <?php post_class('qode-tours-standard-item qode-tours-row-item qode-item-space'); ?>>
	<div class="qode-tour-item-inner">
		<?php if(has_post_thumbnail()) : ?>
			<div class="qode-tours-standard-item-image-holder">
				<a href="<?php the_permalink(); ?>">
					<?php echo qode_tours_get_tour_image_html($thumb_size); ?>
				</a>
				<?php if(qode_tours_get_tour_label_html()) : ?>
					<span class="qode-tours-standard-item-label-holder">
						<?php echo qode_tours_get_tour_label_html(); ?>
					</span>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="qode-tours-standard-item-content-holder">
			<div class="qode-tours-standard-item-content-inner">
				<div class="qode-tours-standard-item-title-price-holder">
					<<?php echo esc_attr($title_tag);?> class="qode-tour-title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</<?php echo esc_attr($title_tag);?>>
					<span class="qode-tours-standard-item-price-holder">
						<?php echo qode_tours_get_tour_price_html(); ?>
					</span>
				</div>

            <?php if(qode_tours_get_tour_rating_html()) : ?>
                <div class="qode-tours-standard-item-bottom-item">
                    <?php echo qode_tours_get_tour_rating_html(); ?>
                </div>
            <?php endif; ?>

				<?php if(qode_tours_get_tour_excerpt()) : ?>
					<div class="qode-tours-standard-item-excerpt">
						<?php echo qode_tours_get_tour_excerpt($text_length); ?>
					</div>
				<?php endif; ?>
			</div>
			<div class="qode-tours-standard-item-bottom-content">
				<?php if(qode_tours_get_tour_duration()) : ?>
					<div class="qode-tours-standard-item-bottom-item">
						<?php echo qode_tours_get_tour_duration_html(); ?>
					</div>
				<?php endif; ?>

				<?php if(qode_tours_get_tour_min_age()) : ?>
					<div class="qode-tours-standard-item-bottom-item">
						<?php echo qode_tours_get_tour_min_age_html(); ?>
					</div>
				<?php endif; ?>
				<?php if(qode_tours_get_tour_destination_html()) : ?>
					<div class="qode-tours-standard-item-bottom-item">
						<?php echo qode_tours_get_tour_destination_html(); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
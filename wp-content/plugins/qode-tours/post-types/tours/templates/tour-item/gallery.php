<?php
if (!isset($title_tag) || $title_tag == ''){
	$title_tag = 'h4';
}
?>
<div <?php post_class(array('qode-tours-gallery-item qode-tours-row-item qode-item-space',qode_tours_get_tour_rating_class())); ?>>
	<div class="qode-tour-item-inner">
		<?php if(has_post_thumbnail()) : ?>
			<div class="qode-tours-gallery-item-image-holder">
				<?php if(qode_tours_get_tour_label_html()) : ?>
					<span class="qode-tours-gallery-item-label-holder">
						<?php echo qode_tours_get_tour_label_html(); ?>
					</span>
				<?php endif; ?>
				
				<div class="qode-tours-gallery-item-image">
					<?php echo qode_tours_get_tour_image_html($thumb_size); ?>
					
					<div class="qode-tours-gallery-item-content-holder">
						<div class="qode-tours-gallery-item-content-inner">
							<div class="qode-tours-gallery-title-holder">
								<<?php echo esc_attr($title_tag);?> class="qode-tour-title">
									<?php the_title(); ?>
								</<?php echo esc_attr($title_tag);?>>
								<span class="qode-tours-gallery-item-price-holder">
								<?php echo qode_tours_get_tour_price_html(); ?>
							</span>
							</div>
							<?php if(qode_tours_get_tour_excerpt()) : ?>
								<div class="qode-tours-gallery-item-excerpt">
									<div class="qode-tours-gallery-item-excerpt-inner">
										<?php echo qode_tours_get_tour_excerpt($text_length); ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<a class="qode-tours-gallery-item-link" href="<?php the_permalink(); ?>"></a>
			</div>
		<?php endif; ?>
	</div>
</div>
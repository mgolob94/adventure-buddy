<div <?php post_class('qode-tours-list-item qode-tours-row-item qode-item-space'); ?>>
	<div class="qode-tours-list-item-table">
		<?php if(has_post_thumbnail()) : ?>
			<div class="qode-tours-list-item-image-holder">
				<div class="qode-tours-list-item-image-holder-inner">
					<a href="<?php the_permalink(); ?>" style="background-image: url(<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>);">
						<?php the_post_thumbnail($thumb_size); ?>
					</a>
					<?php if(qode_tours_get_tour_label_html()) : ?>
						<span class="qode-tours-standard-item-label-holder">
							<?php echo qode_tours_get_tour_label_html(); ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="qode-tours-list-item-content-holder">
			<div class="qode-tours-list-item-title-price-holder">
				<h3 class="qode-tour-title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h3>
			</div>

            <?php if(qode_tours_get_tour_rating_html()) : ?>
                <div class="qode-tours-standard-item-bottom-item">
                    <?php echo qode_tours_get_tour_rating_html(); ?>
                </div>
            <?php endif; ?>

			<?php if(qode_tours_get_tour_excerpt()) : ?>
				<div class="qode-tours-list-item-excerpt">
					<p><?php echo qode_tours_get_tour_excerpt($text_length); ?></p>
				</div>
			<?php endif; ?>
			
			<div class="qode-tours-list-item-price-holder">
				<div class="qode-tours-list-item-price">
					<?php echo qode_tours_get_tour_price_html(); ?>
					<span class="qode-tours-list-price-label"><?php esc_html_e('/ per person', 'qode-tours'); ?></span>
				</div>
				
				<?php if(qode_tours_theme_installed()) : ?>
					<?php //echo qode_get_social_share_html(); ?>
				<?php endif; ?>
			</div>

			<div class="qode-tours-list-item-bottom-content">
				<?php if(qode_tours_get_tour_duration()) : ?>
					<div class="qode-tours-list-item-bottom-item">
						<?php echo qode_tours_get_tour_duration_html(); ?>
					</div>
				<?php endif; ?>

				<?php if(qode_tours_get_tour_min_age()) : ?>
					<div class="qode-tours-list-item-bottom-item">
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
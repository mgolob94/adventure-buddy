<div class="qode-tours-destination-grid">
	<?php if($query->have_posts()) : ?>
		<div <?php qode_tours_class_attribute($classes);?>>
	        <div class="qode-tours-row-inner-holder qode-outer-space">
				<?php while($query->have_posts()) : ?>
					<?php $query->the_post(); ?>

					<?php if(has_post_thumbnail()) : ?>
						<div <?php post_class('qode-tours-destination-grid-item qode-tours-row-item qode-item-space'); ?>>
							<div class="qode-tours-destination-item-holder">
								<a href="<?php the_permalink() ?>">
									<div class="qode-tours-destination-item-image">
										<?php the_post_thumbnail($thumb_size); ?>
									</div>

									<div class="qode-tours-destination-item-content">
										<div class="qode-tours-destination-item-content-inner">
											<<?php echo esc_attr($title_tag);?> class="qode-tours-destination-item-title"><?php the_title(); ?></<?php echo esc_attr($title_tag);?>>
										</div>
									</div>
								</a>
							</div>
						</div>
					<?php endif; ?>
				<?php endwhile; ?>
			</div>
		</div>

		<?php wp_reset_postdata(); ?>

	<?php else: ?>
		<p><?php esc_html_e('No destinations matched your criteria.', 'qode-tours'); ?></p>
	<?php endif; ?>
</div>
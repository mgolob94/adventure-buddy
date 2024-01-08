<div class="qode-tour-type-list-holder">
	<?php if(is_array($tour_types) && count($tour_types)) : ?>
		<div <?php qode_tours_class_attribute($list_classes);?>>
			<div class="qode-tours-row-inner-holder">
				<?php foreach($tour_types as $tour_type) : ?>
					<?php
					$type_icon = $caller->getTypeIcon($tour_type);
					$type_min_price = $caller->getTypeMinPrice($tour_type);
					?>
					<div class="qode-tours-type-item qode-tours-row-item">
						<a href="<?php echo esc_url(get_term_link($tour_type)); ?>">
							<?php if($type_icon) : ?>
							<span class="qode-tour-type-icon">
								<?php print $type_icon; ?>
							</span>
							<?php endif; ?>

							<h5 class="qode-tour-type-name">
								<?php echo esc_html($tour_type->name); ?>
							</h5>

							<?php if(!empty($type_min_price)) : ?>
								<span class="qode-tour-type-min-price-holder">
								<span class="qode-tour-type-min-price-label">
									<?php esc_html_e('From', 'qode-tours'); ?>
								</span>
								<span class="qode-tour-type-min-price">
									<?php echo qode_tours_price_helper()->formatPrice($type_min_price); ?>
								</span>
							</span>
							<?php endif; ?>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</div>
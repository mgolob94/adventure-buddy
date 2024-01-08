<div <?php qode_tours_class_attribute($filter_class); ?>>
	<?php if($filter_type === 'vertical') : ?>
		<?php echo qode_tours_get_search_main_filters_html($show_tour_types, $number_of_tour_types); ?>
	<?php else: ?>
		<?php
			$tour_types = get_terms(array(
				'taxonomy' => 'tour-category'
			));
		?>

		<?php if($display_container_inner) : ?>
			<div class="container_inner">
		<?php endif; ?>
		
		<div class="qode-tours-search-horizontal-filters-holder">
			<form action="<?php echo esc_url(qode_tours_get_search_page_url()); ?>" method="GET">
				<div class="qode-tours-filters-fields-holder">
					<div class="qode-tours-filter-field-holder qode-tours-filter-col">
						<div class="qode-tours-input-with-icon">
							<span class="qode-tours-input-icon">
								<span class="dripicons-location"></span>
							</span>
							<input type="text" value="" class="qode-tours-destination-search" name="destination" placeholder="<?php esc_attr_e('Where to?', 'qode-tours'); ?>">
						</div>
					</div>
					
					<div class="qode-tours-filter-field-holder qode-tours-filter-col">
						<div class="qode-tours-input-with-icon">
							<span class="qode-tours-input-icon">
								<span class="dripicons-calendar"></span>
							</span>
							<select class="qode-tours-select-placeholder" name="month">
								<option value=""><?php esc_html_e('Month', 'qode-tours'); ?></option>
								<option value="1"><?php esc_html_e('January', 'qode-tours'); ?></option>
								<option value="2"><?php esc_html_e('February', 'qode-tours'); ?></option>
								<option value="3"><?php esc_html_e('March', 'qode-tours'); ?></option>
								<option value="4"><?php esc_html_e('April', 'qode-tours'); ?></option>
								<option value="5"><?php esc_html_e('May', 'qode-tours'); ?></option>
								<option value="6"><?php esc_html_e('June', 'qode-tours'); ?></option>
								<option value="7"><?php esc_html_e('July', 'qode-tours'); ?></option>
								<option value="8"><?php esc_html_e('August', 'qode-tours'); ?></option>
								<option value="9"><?php esc_html_e('September', 'qode-tours'); ?></option>
								<option value="10"><?php esc_html_e('October', 'qode-tours'); ?></option>
								<option value="11"><?php esc_html_e('November', 'qode-tours'); ?></option>
								<option value="12"><?php esc_html_e('December', 'qode-tours'); ?></option>
							</select>
						</div>
					</div>
					
					<div class="qode-tours-filter-field-holder qode-tours-filter-col">
						<div class="qode-tours-input-with-icon">
							<span class="qode-tours-input-icon">
								<span class="dripicons-pin"></span>
							</span>
							<select class="qode-tours-select-placeholder" name="type[]">
								<option value=""><?php esc_html_e('Travel Type','qode-tours'); ?></option>
								<?php if(is_array($tour_types) && count($tour_types)) : ?>
									<?php foreach($tour_types as $tour_type) : ?>
										<option value="<?php echo esc_attr($tour_type->slug); ?>"><?php echo esc_html($tour_type->name); ?></option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>
					</div>

					<div class="qode-tours-filter-field-holder qode-tours-filter-submit-field-holder qode-tours-filter-col">
						<?php if(qode_tours_theme_installed()) : ?>
							<?php echo bridge_qode_execute_shortcode('button', array(
								'html_type'  => 'button',
								'input_name' => 'qode_tours_search_submit',
								'text'       => esc_attr__('FIND NOW', 'qode-tours'),
								'custom_attrs' => array(
								'data-searching-label' => esc_attr__('Searching...', 'qode-tours')
								)
							)); ?>
						<?php else: ?>
							<input type="submit" data-searching-label="<?php esc_attr_e('Searching...', 'qode-tours'); ?>" name="qode_tours_search_submit" value="<?php esc_attr_e('FIND NOW', 'qode-tours') ?>">
						<?php endif; ?>
					</div>
					
					<?php if(qode_tours_is_wpml_installed()) { ?>
						<?php
							$lang = ICL_LANGUAGE_CODE;
						?>
						<input type="hidden" name="lang" value="<?php echo esc_attr($lang); ?>">
					<?php } ?>
				</div>
			</form>
		</div>

		<?php if($display_container_inner) : ?>
			</div>
		<?php endif; ?>

	<?php endif; ?>
</div>
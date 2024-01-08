<div class="qode-tours-search-main-filters-holder qode-boxed-widget">
	<form action="<?php echo esc_url(qode_tours_get_search_page_url()); ?>" method="GET">
		<div class="qode-tours-search-main-filters-title">
			<h4><?php esc_html_e('Find Your Destination', 'qode-tours'); ?></h4>
		</div>

		<input type="hidden" name="order_by" value="<?php echo esc_attr(qode_tours_search()->getOrderBy()); ?>">
		<input type="hidden" name="order_type" value="<?php echo esc_attr(qode_tours_search()->getOrderType()); ?>">
		<input type="hidden" name="view_type" value="<?php echo esc_attr(qode_tours_search()->getViewType()); ?>">
		<input type="hidden" name="page" value="<?php echo esc_attr($current_page); ?>">

		<div class="qode-tours-search-main-filters-fields">
			<div class="qode-tours-input-with-icon">
				<span class="qode-tours-input-icon">
					<span class="dripicons-search"></span>
				</span>
				<input class="qode-tours-keyword-search" value="<?php echo esc_attr($keyword); ?>" type="text" name="keyword" placeholder="<?php esc_attr_e('Search Tour', 'qode-tours'); ?>">
			</div>
			<div class="qode-tours-input-with-icon">
				<span class="qode-tours-input-icon">
					<span class="dripicons-map"></span>
				</span>
				<input type="text" value="<?php echo esc_attr($destination); ?>" class="qode-tours-destination-search" name="destination" placeholder="<?php esc_attr_e('Where To?', 'qode-tours'); ?>">
			</div>
			<div class="qode-tours-input-with-icon">
				<span class="qode-tours-input-icon">
					<span class="dripicons-calendar"></span>
				</span>
				<select name="month" class="qode-tours-select-placeholder">
					<?php foreach($months as $month_value => $month_label) : ?>
						<?php $selected = $month_value === (int) $chosen_month ? 'selected' : ''; ?>

						<option <?php echo esc_attr($selected); ?> value="<?php echo esc_attr($month_value); ?>"><?php echo esc_html($month_label); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="qode-tours-range-input"></div>

			<div class="qode-tours-input-with-icon">
				<input type="text" class="qode-tours-price-range-field"
					data-currency-symbol-position="<?php echo esc_attr($currency_position); ?>"
					data-currency-symbol="<?php echo esc_attr($currency_symbol); ?>"
					data-min-price="<?php echo esc_attr($min_price); ?>"
					data-max-price="<?php echo esc_attr($max_price); ?>"
					data-chosen-min-price="<?php echo esc_attr($chosen_min_price); ?>"
					data-chosen-max-price="<?php echo esc_attr($chosen_max_price); ?>"
					placeholder="<?php esc_attr_e('Price Range', 'qode-tours'); ?>">
				<input type="hidden" name="min_price">
				<input type="hidden" name="max_price">
			</div>


			<?php if(is_array($tour_types) && count($tour_types) && $show_tour_types) : ?>
				<?php foreach($tour_types as $type) : ?>
					<?php
					$checked = in_array($type->slug, $checked_types);
					$checked_attr = $checked ? 'checked' : '';
					?>

					<div class="qode-tours-type-filter-item">
						<input <?php echo esc_attr($checked_attr); ?> type="checkbox" id="qode-tour-type-filter-<?php echo esc_attr($type->slug); ?>" name="type[]" value="<?php echo esc_attr($type->slug); ?>">
						<label for="qode-tour-type-filter-<?php echo esc_attr($type->slug); ?>">
						<span>
							<?php echo esc_html($type->name); ?>
						</span>
						</label>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if(qode_tours_theme_installed()) : ?>
				<?php echo bridge_qode_execute_shortcode('button', array(
					'html_type'    => 'button',
                    'custom_class' => 'qode-tours-search-submit',
					'input_name'   => 'qode_tours_search_submit',
					'text'         => esc_attr__('Search', 'qode-tours'),
					'custom_attrs' => array(
						'data-searching-label' => esc_attr__('Searching...', 'qode-tours')
					)
				)); ?>
			<?php else: ?>
				<input type="submit" name="qode_tours_search_submit" value="<?php esc_attr_e('Search', 'qode-tours') ?>" class="qode-btn qode-btn-medium qode-btn-solid" data-searching-label="<?php esc_attr_e('Searching...', 'qode-tours'); ?>">
			<?php endif; ?>
			
			<?php if(qode_tours_is_wpml_installed()) { ?>
				<?php
					$lang = ICL_LANGUAGE_CODE;
				?>
				<input type="hidden" name="lang" value="<?php echo esc_attr($lang); ?>">
			<?php } ?>
		</div>
	</form>
</div>
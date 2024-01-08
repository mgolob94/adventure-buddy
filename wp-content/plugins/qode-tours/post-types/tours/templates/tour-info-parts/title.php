<?php
$title = get_the_title();
?>

<div class="qode-info-section-part qode-tour-item-title-holder">
	<?php if($title !== '') : ?>
		<h3 class="qode-tour-item-title">
			<?php echo esc_html($title) ?>
		</h3>
	<?php endif; ?>

	<div class="qode-tour-item-price-holder">
		<span class="qode-tour-item-price">
			<?php echo qode_tours_get_tour_price_html(get_the_ID()); ?>
		</span>

		<span class="qode-tour-item-price-text">
			<?php esc_html_e('per person', 'qode-tours'); ?>
		</span>
	</div>
</div>

<?php if(qode_tours_get_tour_duration()) : ?>
	<div class="qode-tours-single-info-item">
		<?php echo qode_tours_get_tour_duration_html(); ?>
	</div>
<?php endif; ?>

<?php if(qode_tours_get_tour_min_age()) : ?>
	<div class="qode-tours-single-info-item">
		<?php echo qode_tours_get_tour_min_age_html(get_the_ID(), true); ?>
	</div>
<?php endif; ?>

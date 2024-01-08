<div class="container">
	<div class="container_inner clearfix">
		<div class="<?php echo esc_attr($holder_class) ?>">
		    <div class="qode-grid-row-medium-gutter">
	            <div class="qode-grid-col-9">
	                <?php echo qode_tours_get_tour_module_template_part('single/single-item', 'tours', 'templates', '', $params); ?>
	            </div>
	            <div class="qode-grid-col-3">
	                <aside class="qode-sidebar">
	                    <div class="widget qode-tours-booking-form-holder">
	                        <?php if(qode_tours_is_tour_bookable()) : ?>
	                            <?php echo qode_tours_get_tour_module_template_part('single/booking-form', 'tours', 'templates', '', $params); ?>
	                        <?php endif; ?>
	                    </div>

	                    <?php dynamic_sidebar('tour-single-sidebar'); ?>
	                </aside>
	            </div>
	        </div>
        </div>
    </div>
</div>
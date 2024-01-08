<div class="qode-tours-carousel-holder qode-carousel-pagination">
	<div <?php qode_tours_class_attribute($list_classes);?>>
	    <?php if($query->have_posts()) : ?>
	        <div class="qode-tours-carousel qode-tours-row-inner-holder qode-owl-slider" <?php echo qode_tours_get_inline_attrs($carousel_data); ?>>

	            <?php while($query->have_posts()) : ?>
	                <?php $query->the_post(); ?>
                    <?php $caller->getItemTemplate($tour_type, $params); ?>
	            <?php endwhile; ?>

	        </div>
	    <?php else: ?>
	        <p><?php esc_html_e('No tours match your criteria', 'qode-tours'); ?></p>
	    <?php endif; ?>
	    <?php wp_reset_postdata(); ?>
	</div>
</div>
<?php while($query->have_posts()) : ?>
	<?php $query->the_post(); ?>
	<?php $caller->getItemTemplate($tour_type, $params); ?>
<?php endwhile; ?>
<?php wp_reset_postdata(); ?>
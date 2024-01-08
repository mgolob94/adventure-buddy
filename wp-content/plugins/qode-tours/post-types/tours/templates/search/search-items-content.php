<?php if(is_array($tours_list) && count($tours_list)) : ?>
		<?php foreach($tours_list as $tour_item) :
			global $post;

			$post = $tour_item;
			setup_postdata($tour_item); ?><!--
		--><?php echo qode_tours_get_tour_module_template_part('templates/tour-item/'.$type, 'tours', '', '', array(
				'thumb_size' => $thumb_size,
				'text_length' => $text_length,
				'title_style' => ''
			));?><!--
		--><?php endforeach; ?>
<?php else: ?>
	<p><?php esc_html_e('We haven\'t found any tour that matches you\'re criteria', 'qode-tours'); ?></p>
<?php endif; ?>

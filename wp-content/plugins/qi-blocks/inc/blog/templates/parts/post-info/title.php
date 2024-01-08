<?php
$title_tag = isset( $titleTag ) && ! empty( $titleTag ) ? $titleTag : 'h1';
?>
<<?php echo esc_attr( $title_tag ); ?> class="qodef-e-title">
	<a class="qodef-e-title-link" href="<?php the_permalink(); ?>">
		<?php the_title(); ?>
	</a>
</<?php echo esc_attr( $title_tag ); ?>>

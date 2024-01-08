<?php
$qode_sidebar_layout = bridge_qode_sidebar_layout();

get_header();
bridge_qode_get_title();
get_template_part( 'slider' );
do_action('qode_before_main_content');
?>

<div class="container qode-default-page-template">
	<?php do_action( 'qode_after_container_open' ); ?>
	
	<div class="container_inner clearfix">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<div class="qode-grid-row">
				<div <?php echo bridge_qode_get_content_sidebar_class(); ?>>
					<?php
						the_content();
						do_action( 'qode_page_after_content' );
					?>
				</div>
				<?php if ( $qode_sidebar_layout !== '' ) { ?>
					<div <?php echo bridge_qode_get_sidebar_holder_class(); ?>>
						<?php get_sidebar(); ?>
					</div>
				<?php } ?>
			</div>
		<?php endwhile; endif; ?>
	</div>
	
	<?php do_action( 'qode_before_container_close' ); ?>
</div>

<?php get_footer(); ?>
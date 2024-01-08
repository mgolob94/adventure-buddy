<?php get_header(); ?>
<?php bridge_qode_get_title(); ?>
<?php do_action('qode_before_main_content'); ?>
<div class="qode-tours-checkout-page-holder">
	<?php if(have_posts()) : while(have_posts()) :  the_post(); ?>
		<div class="qode-tours-checkout-page-content">
			<?php the_content(); ?>
		</div>

		<?php echo qode_tours_get_checkout_page_content(); ?>
	<?php endwhile; endif; ?>
</div>
<?php get_footer(); ?>

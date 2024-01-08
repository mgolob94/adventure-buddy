<?php get_header();
bridge_qode_get_title();
get_template_part( 'slider' );
do_action('qode_before_main_content'); ?>
	<div class="qode-tours-search-page-holder">
		<div class="container">
			<div class="container_inner">
				<div class="qode-grid-row">
					<div class="qode-grid-col-9">
						<?php echo qode_tours_get_search_ordering_html(); ?>

						<?php echo qode_tours_get_search_page_content_html(); ?>

						<?php echo qode_tours_get_search_pagination(); ?>
					</div>
					<div class="qode-grid-col-3">
						<aside class="qode-sidebar">
							<div class="widget qode-tours-main-search-filters">
								<?php echo qode_tours_get_search_main_filters_html(); ?>
							</div>
							<?php dynamic_sidebar('tour-search-sidebar'); ?>
						</aside>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php get_footer(); ?>

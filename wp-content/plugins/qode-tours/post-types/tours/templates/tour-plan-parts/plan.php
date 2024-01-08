<?php

$tour_plan = get_post_meta(get_the_ID(), 'qode_tour_plan_repeater', true);

if(is_array($tour_plan) && count($tour_plan)) {
	
    foreach ($tour_plan as $i => $tour_plan_item) { ?>

        <div class="qode-info-section-part qode-tour-item-plan-part clearfix">
            <div class="qode-route-top-holder">
            	<div class="qode-route-id"><?php echo($i + 1); ?></div>
                <span class="qode-line-between-icons">
                    <span class="qode-line-between-icons-inner"></span>
                </span>
	            <h5 class="qode-tour-item-plan-part-title">
	                <?php echo esc_attr($tour_plan_item['plan_section_title']); ?>
	            </h5>
            </div>
            <div class="qode-tour-item-plan-part-description">
                <?php
                    echo do_shortcode($tour_plan_item['plan_section_description']);
                ?>
            </div>

        </div>

    <?php }

}
<?php
extract($tour_sections);
?>

<article class="qode-tour-item-wrapper qode-tabs qode-advanced-tabs qode-horizontal qode-tab-text">

    <ul class="qode-tabs-nav clearfix">
        <?php foreach($tour_sections as $section) {

            if($section['value'] === 'yes') { ?>

                <li class="qode-tour-nav-item">

                    <a href="#tab-<?php echo esc_attr($section['id']) ?>" >

                        <span class="qode-tour-nav-section-icon <?php echo esc_attr($section['icon']) ?>"></span>

						<span class="qode-tour-nav-section-title">
							<?php echo esc_html($section['title']) ?>
						</span>

                    </a>
                </li>

            <?php }

        }; ?>
    </ul>


    <?php if($show_info_section['value'] === 'yes') { ?>

        <div class="qode-tour-item-section qode-advanced-tab-container qode-tab-container" id="tab-<?php echo esc_attr($show_info_section['id']) ?>">

            <?php qode_tours_get_tour_info_part('tour-info-parts/title'); ?>
            <?php qode_tours_get_tour_info_part('tour-info-parts/rating'); ?>
            <?php qode_tours_get_tour_info_part('tour-info-parts/excerpt'); ?>
            <?php qode_tours_get_tour_info_part('tour-info-parts/content'); ?>

            <div class="qode-tour-item-short-info">
                <?php qode_tours_get_tour_info_part('tour-info-parts/years'); ?>
                <?php qode_tours_get_tour_info_part('tour-info-parts/destination'); ?>
                <?php qode_tours_get_tour_info_part('tour-info-parts/categories'); ?>
            </div>

            <?php qode_tours_get_tour_info_part('tour-info-parts/main-info'); ?>
            <?php qode_tours_get_tour_info_part('tour-info-parts/gallery'); ?>

        </div>

    <?php } ?>

    <?php if($show_tour_plan_section['value'] === 'yes') { ?>

        <div class="qode-tour-item-section qode-advanced-tab-container qode-tab-container" id="tab-<?php echo esc_attr($show_tour_plan_section['id']) ?>">
            <?php qode_tours_get_tour_info_part('tour-plan-parts/plan'); ?>
        </div>

    <?php } ?>

    <?php if($show_location_section['value'] === 'yes') { ?>

        <div class="qode-tour-item-section qode-advanced-tab-container qode-tab-container" id="tab-<?php echo esc_attr($show_location_section['id']) ?>">
            <?php qode_tours_get_tour_info_part('tour-location-parts/location'); ?>
        </div>

    <?php } ?>

    <?php if($show_gallery_section['value'] === 'yes') { ?>

        <div class="qode-tour-item-section qode-advanced-tab-container qode-tab-container" id="tab-<?php echo esc_attr($show_gallery_section['id']) ?>">
            <?php qode_tours_get_tour_info_part('tour-gallery-parts/gallery'); ?>
        </div>

    <?php } ?>

    <?php if($show_review_section['value'] === 'yes') { ?>

        <div class="qode-tour-item-section qode-advanced-tab-container qode-tab-container" id="tab-<?php echo esc_attr($show_review_section['id']) ?>">
            <?php qode_tours_get_tour_info_part('tour-review-parts/reviews'); ?>
        </div>

    <?php } ?>

    <?php if($show_custom_section_1['value'] === 'yes') { ?>

        <div class="qode-tour-item-section qode-advanced-tab-container qode-tab-container" id="tab-<?php echo esc_attr($show_custom_section_1['id']) ?>">
            <?php qode_tours_get_tour_info_part('tour-custom1-parts/custom'); ?>
        </div>

    <?php } ?>

    <?php if($show_custom_section_2['value'] === 'yes') { ?>

        <div class="qode-tour-item-section qode-advanced-tab-container qode-tab-container" id="tab-<?php echo esc_attr($show_custom_section_2['id']) ?>">
            <?php qode_tours_get_tour_info_part('tour-custom2-parts/custom'); ?>
        </div>

    <?php } ?>


</article>



<?php
$custom_2_excerpt = get_post_meta(get_the_ID(), 'qode_tours_custom_section2_excerpt', true);
$custom_2_content = get_post_meta(get_the_ID(), 'qode_tours_custom_section2_content', true);
?>
<div class="qode-info-section-part">
    <?php if(!empty($custom_2_excerpt)) { ?>
        <?php echo esc_html($custom_2_excerpt); ?>
    <?php }
    
    if(!empty($custom_2_content)) { ?>
        <?php echo do_shortcode($custom_2_content); ?>
    <?php } ?>
</div>

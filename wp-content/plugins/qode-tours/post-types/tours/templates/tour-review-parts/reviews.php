<?php

$display_comments = bridge_qode_options()->getOptionValue('display_reviews_comments');
$template_option = bridge_qode_options()->getOptionValue('review_section_template');

$holder_classes = 'qode-tours-reviews-list-top';
if(!empty($template_option)){
    $holder_classes .= ' qode-tours-review-' . $template_option . '-template';
}

if(!empty($display_comments) && $display_comments == 'no'){
    $holder_classes .= ' qode-tours-review-hidden-comments';
}

?>

<div class="<?php echo $holder_classes ?>">
    <?php

        if($template_option != 'default'){
            echo qode_list_review_details($template_option);
        }

        comments_template('', true);
    ?>
</div>

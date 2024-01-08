<?php
if(get_the_excerpt() !== '') : ?>
    <div class="qode-info-section-part qode-tour-item-excerpt">
        <?php the_excerpt(); ?>
    </div>
<?php endif; ?>
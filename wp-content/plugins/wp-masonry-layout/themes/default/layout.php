<div class="wmle_item_holder <?php echo $shortcodeData['wmlo_columns'] ?>" style="display:none;">
    <div class="wmle_item">
        <?php if ( has_post_thumbnail() ) :?>
            <div class="wpme_image">
                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail($shortcodeData['wmlo_image_size']); ?></a>
            </div>
        <?php endif; ?>
    </div><!-- EOF wmle_item_holder -->
</div><!-- EOF wmle_item_holder -->
<article id="<?php echo $slug; ?>" <?php post_class('child cell', $ID); ?>>
    <div class="image" style="background-image:url('<?php echo $image; ?>')">
        <a class="link" href="<?php echo get_permalink($ID); ?>"><span class="h2"><?php echo $title; ?></span></a>
    </div>
</article>

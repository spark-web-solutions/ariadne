<?php
/**
 * Tile card
 * @var $ID integer Post ID
 * @var $slug string Tile slug
 * @var $image string Image URL
 * @var $title string Title
 */
?>
<article id="<?php echo $slug; ?>" <?php post_class('child cell', $ID); ?>>
    <div class="image" style="background-image:url('<?php echo $image; ?>')">
        <a href="<?php echo get_permalink($ID); ?>"><span class="h2"><?php echo $title; ?></span></a>
    </div>
</article>

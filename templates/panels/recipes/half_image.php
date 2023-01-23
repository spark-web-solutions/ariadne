<?php
/**
 * @var WP_Post $panel
 */
$meta = spark_get_post_meta($panel->ID);
if (!empty($meta["image"])) {
    $image = wp_get_attachment_image_src($meta["image"], 'full');
}
?>
<div class="cell image-<?php echo $meta["image_pos"]; ?>">
	<div class="image" style="background-image: url(<?php echo $image[0]; ?>); background-size:cover;"></div>
    <div class="content">
<?php
spark_panel_title($panel);
spark_panel_content($panel);
if (!empty($meta["destination"])) {
?>
        <p class="action-button"><a href="<?php echo $meta["destination"]; ?>" class="button button-border font-weight-600"><?php echo $meta["action_text"]; ?></a></p>
<?php
}
?>
    </div>
</div>

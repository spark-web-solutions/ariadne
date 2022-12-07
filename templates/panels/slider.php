<?php
/**
 * @var WP_Post $wrapper
 * @var WP_Post[] $children
 */
$panel_name = spark_get_post_meta($wrapper->ID, 'panel_name');
$flavour = spark_get_post_meta($wrapper->ID, 'flavour');
$bg_style = '';
$bg_colour = spark_get_post_meta($wrapper->ID, 'bg_colour');
if (is_numeric($bg_colour)) {
    $bg_colour = spark_get_theme_mod('colour'.$bg_colour);
}
if (!empty($bg_colour)) {
    $bg_style .= 'background-color: '.$bg_colour.';';
}
?>
<div id="row-panel-<?php echo $wrapper->ID; ?>" class="<?php echo $panel_name.' panel-'.$wrapper->ID; ?> clearfix" style="<?php echo $bg_style; ?>">
	<div class="panel-slider">
<?php
$slides = array();
foreach ($children as $panel) {
	ob_start();
	locate_template('/templates/panels/banner.php', true, false);
    $slides[] = ob_get_clean();
}
spark_generate_carousel($slides);
?>
	</div>
<?php
if (current_user_can('edit_pages') && $wrapper->post_parent == 0) {
?>
    <div class="edit-panel">
        <a title="Edit Panel" target="_edit_panel" href="/wp-admin/post.php?post=<?php echo $wrapper->ID; ?>&action=edit"><i class="la la-edit" aria-hidden="true"></i> <?php echo $wrapper->menu_order; ?></a>
    </div>
<?php
}
?>
</div>

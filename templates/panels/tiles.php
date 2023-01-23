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
$small_count = spark_get_post_meta($wrapper->ID, 'num_per_row_small');
$medium_count = spark_get_post_meta($wrapper->ID, 'num_per_row_medium');
$large_count = spark_get_post_meta($wrapper->ID, 'num_per_row_large');
?>
<div id="row-panel-<?php echo $wrapper->ID; ?>" class="panel-tiles <?php echo $panel_name.' panel-'.$wrapper->ID; ?> clearfix grid-x grid-padding-x small-up-<?php echo $small_count; ?> medium-up-<?php echo $medium_count; ?> large-up-<?php echo $large_count; ?>" style="<?php echo $bg_style; ?>">
<?php
foreach ($children as $panel) {
?>
    <div class="cell tile">
<?php
	locate_template('/templates/panels/banner.php', true, false);
?>
    </div>
<?php
}
if (current_user_can('edit_pages') && $wrapper->post_parent == 0) {
?>
    <div class="edit-panel">
        <a title="Edit Panel" target="_edit_panel" href="/wp-admin/post.php?post=<?php echo $wrapper->ID; ?>&action=edit"><i class="la la-edit" aria-hidden="true"></i> <?php echo $wrapper->menu_order; ?></a>
    </div>
<?php
}
?>
</div>

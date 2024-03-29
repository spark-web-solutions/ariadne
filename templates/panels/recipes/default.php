<?php
/**
 * @var WP_Post $panel
 */
?>
<div class="cell">
    <div class="wrapper">
<?php
$destination = get_post_meta($panel->ID, 'destination', true);
if (!empty($destination)) {
    echo '        <a class="link" href="'.$destination.'">'."\n";
}
spark_panel_title($panel);
spark_panel_content($panel);
if (!empty(get_post_meta($panel->ID, 'action_text', true))) {
?>
    <p class="button"><?php echo get_post_meta($panel->ID, 'action_text', true); ?></p>
<?php
}
if (!empty($destination)) {
    echo '        </a>'."\n";
}
?>
    </div>
</div>

<?php
/**
 * @var WP_Post $panel
 */
?>
<div class="cell">
    <div class="wrapper">
        <a class="link" data-open="video-modal-<?php echo $panel->ID; ?>"><i class="las la-play-circle la-5x" aria-hidden="true"></i></a>
    </div>
    <div class="small reveal" id="video-modal-<?php echo $panel->ID; ?>" data-reveal data-reset-on-close="true">
        <div class='flex-video'><iframe src='<?php echo get_post_meta($panel->ID, 'video', true); ?>' frameborder='0' allowfullscreen></iframe></div>
        <a class="close-button" data-close aria-label="Close reveal">
            <span aria-hidden="true">&times;</span>
        </a>
    </div>
</div>

<?php
/**
 * Gets the URL for the post featured image. Works with Multisite Featured Image plugin as well as native WP.
 * @param string $size
 * @param int|WP_Post $post Optional. Default global $post.
 * @param boolean $with_fallback Optional. Whether to return default featured image if none set for selected post. Default false.
 * @return boolean|NULL|string False on error, null if no thumbnail, else string image URL
 */
function spark_get_featured_image_url($size = 'single-post-thumbnail', $post = null, $with_fallback = false) {
    if (is_null($post)) {
        global $post;
    } else {
        $post = get_post($post);
    }
    if (!($post instanceof WP_Post)) {
        return false;
    }
    if (!has_post_thumbnail($post->ID) && !$with_fallback) {
        return null;
    }
    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $size);
    $imgURL = $image[0];
    if (empty($imgURL)) {
        $images = maybe_unserialize(get_post_meta($post->ID, '_ibenic_mufimg_image', true));
        if ($images && is_array($images) && count($images) > 0) {
            $image = "";
            if (isset($images[$size])) {
                $image = $images[$size];
            } else {
                $image = $images["full"];
            }
            // Setting the URL
            $imgURL = $image["url"];
        } else {
            // Backward compatibility if saved as one URL
            $imgURL = get_post_meta($post->ID, '_ibenic_mufimg_src', true);
        }
    }

    if (empty($imgURL) && $with_fallback) {
        $imgURL = spark_get_theme_mod('default_featured_image');
    }

    return $imgURL;
}

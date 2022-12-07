<?php
if (!function_exists('spark_get_featured_image_url')) {
	/**
	 * Gets the URL for the post featured image
	 * @param string $size Optional. Image size. Accepts any valid image size, or an array of width and height values in pixels (in that order). Default 'single-post-thumbnail'.
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
	    if (!$post instanceof WP_Post) {
	        return false;
	    }

	    $image = $imgUrl = null;
	    if (has_post_thumbnail($post->ID)) {
	    	$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $size);
	    }
	    if (is_array($image)) {
	    	$imgUrl = $image[0];
	    } elseif ($with_fallback) {
	    	$imgUrl = spark_get_theme_mod('default_featured_image'); // @todo can we return a correctly sized version of this?
	    }

	    return $imgUrl;
	}
}

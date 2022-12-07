<?php
if (!function_exists('get_the_slug')) {
    /**
     * Get the page or post slug
     * @param integer $id Optional. Post ID (will use global post object if not specified).
     * @return string
     */
    function get_the_slug($id = null) {
        if (empty($id)) {
            global $post;
            if (empty($post)) {
                return ''; // No global $post var available.
            }
            $id = $post->ID;
        }

        $slug = get_post($id)->post_name;
        return $slug;
    }
}

if (!function_exists('the_slug')) {
    /**
     * Display the page or post slug
     *
     * Uses get_the_slug() and applies 'the_slug' filter.
     * @param integer $id Optional. Post ID (will use global post object if not specified).
     */
    function the_slug($id = null) {
        echo apply_filters('the_slug', get_the_slug($id));
    }
}

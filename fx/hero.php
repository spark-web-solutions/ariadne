<?php
/**
 * Gets the hero image URL for large, medium and small screens
 * @param mixed $post (optional) Post ID, WP_Post object or WP_Term object. If empty will use global $post.
 * @param integer $last_id (optional) Variable to hold the ID of the post the large image was found on (or the top-level ancestor if no value found)
 * @return boolean|array False on error, else associative array of screen sizes => URLs
 */
function spark_get_hero_images($post = null, &$last_id = null) {
    if (empty($post) || is_int($post)) {
        $post = get_post($post);
    }

    if ($post instanceof WP_Post) {
        $transient = ns_.'hero_'.$post->ID.'_';
    } elseif ($post instanceof WP_Term) {
        $transient = ns_.'hero_term_'.$post->term_id.'_';
    }
    if (!Spark_Transients::use_transients() && !empty($transient)) {
        delete_transient($transient);
    }
    if (false === ($images = get_transient($transient))) {
        if ($post instanceof WP_Post) {
            $large_hero_meta = get_value_from_hierarchy('hero_image', $post->ID, $last_id);
            if (!empty($large_hero_meta)) {
                $large_image = wp_get_attachment_image_src($large_hero_meta, 'full');
                $large_hero = $large_image[0];
            }
            if (empty($large_hero)) {
                $large_hero = get_value_from_hierarchy('featured_image', $post->ID, $last_id);
            }
        } elseif ($post instanceof WP_Term) {
            $large_hero_meta = get_term_meta($post->term_id, 'hero_image', true);
            $large_image = wp_get_attachment_image_src($large_hero_meta, 'full');
            $large_hero = $large_image[0];
        }
        if (empty($large_hero)) {
            $large_hero = spark_get_theme_mod('default_featured_image');
        }

        if ($post instanceof WP_Post) {
            $medium_hero_meta = get_value_from_hierarchy('hero_image_medium', $post->ID);
            if (!empty($medium_hero_meta)) {
                $medium_image = wp_get_attachment_image_src($medium_hero_meta, 'full');
                $medium_hero = $medium_image[0];
            }
        } elseif ($post instanceof WP_Term) {
            $medium_hero_meta = get_term_meta($post->term_id, 'hero_image_medium', true);
            $medium_image = wp_get_attachment_image_src($medium_hero_meta, 'full');
            $medium_hero = $medium_image[0];
        }
        if (empty($medium_hero)) {
            $medium_hero = $large_hero;
        }
        if ($post instanceof WP_Post) {
            $small_hero_meta = get_value_from_hierarchy('hero_image_small', $post->ID);
            if (!empty($small_hero_meta)) {
                $small_image = wp_get_attachment_image_src($small_hero_meta, 'full');
                $small_hero = $small_image[0];
            }
        } elseif ($post instanceof WP_Term) {
            $small_hero_meta = get_term_meta($post->term_id, 'hero_image_small', true);
            $small_image = wp_get_attachment_image_src($small_hero_meta, 'full');
            $small_hero = $small_image[0];
        }
        if (empty($small_hero)) {
            $small_hero = $large_hero;
        }

        $images = array(
                'large' => $large_hero,
                'medium' => $medium_hero,
                'small' => $small_hero,
        );
        set_transient($transient, $images, LONG_TERM);
    }
    unset($transient);

    return $images;
}

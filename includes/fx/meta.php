<?php
if (!function_exists('spark_get_post_meta')) {
	/**
	 * Gets meta for a post
	 * @param integer|WP_Post $post Optional. Post to retrieve meta for
	 * @param string $key Optional. Meta key to retrieve value for
	 * @return array|mixed If key is empty will return array of meta values already single-ised, else will return value for the specified key
	 */
	function spark_get_post_meta($post = null, $key = '') {
	    $post = get_post($post);
	    $meta = false;
	    if ($post instanceof WP_Post) {
	        $transient = ns_.'meta_'.$post->ID.'_';
	        if (false === ($meta = get_transient($transient)) || !Spark_Transients::use_transients()) {
	            $meta = get_post_meta($post->ID);
	            if (is_array($meta)) {
	                $meta = spark_rationalise_meta($meta);
	            }
	            if (Spark_Transients::use_transients()) {
	                set_transient($transient, $meta, SPARK_LONG_TERM);
	            }
	        }
	        unset($transient);

	        if (!empty($key)) {
	            return $meta[$key] ?? false;
	        }
	    }

	    return $meta;
	}
}

if (!function_exists('spark_get_user_meta')) {
	/**
	 * Gets meta for a user
	 * @param integer|WP_User $user Optional. User to retrieve meta for
	 * @param string $key Optional. Meta key to retrieve value for
	 * @return array|mixed If key is empty will return array of meta values already single-ised, else will return value for the specified key
	 */
	function spark_get_user_meta($user = null, $key = '') {
	    if (!$user instanceof WP_User) {
	        if (is_null($user)) {
	            $user = get_current_user_id();
	        }
	        $user = new WP_User($user);
	    }
	    $meta = false;
	    if ($user instanceof WP_User) {
	        $transient = ns_.'usermeta_'.$user->ID.'_';
	        if (false === ($meta = get_transient($transient)) || !Spark_Transients::use_transients()) {
	            $meta = get_user_meta($user->ID);
	            if (is_array($meta)) {
	                $meta = spark_rationalise_meta($meta);
	            }
	            if (Spark_Transients::use_transients()) {
	            	set_transient($transient, $meta, SPARK_LONG_TERM);
	            }
	        }
	        unset($transient);

	        if (!empty($key)) {
	        	return $meta[$key] ?? false;
	        }
	    }

	    return $meta;
	}
}

if (!function_exists('spark_get_term_meta')) {
	/**
	 * Gets meta for a term
	 * @param integer|WP_Term $term Optional. Term to retrieve meta for.
	 * @param string $key Optional. Meta key to retrieve value for.
	 * @return array|mixed If key is empty will return array of meta values already single-ised, else will return value for the specified key
	 */
	function spark_get_term_meta($term = null, $taxonomy = null, $key = '') {
	    if (!$term instanceof WP_Term) {
	        if (is_null($term)) {
	            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
	        } else {
	            if (is_null($taxonomy)) {
	                $taxonomy = get_query_var('taxonomy');
	            }
	            $term = get_term_by('id', $term, $taxonomy);
	        }
	    }
	    $meta = false;
	    if ($term instanceof WP_Term) {
	        $transient = ns_.'termmeta_'.$term->term_id.'_';
	        if (false === ($meta = get_transient($transient)) || !Spark_Transients::use_transients()) {
	            $meta = get_term_meta($term->term_id);
	            if (is_array($meta)) {
	                $meta = spark_rationalise_meta($meta);
	            }
	            if (Spark_Transients::use_transients()) {
	            	set_transient($transient, $meta, SPARK_LONG_TERM);
	            }
	        }
	        unset($transient);

	        if (!empty($key)) {
	        	return $meta[$key] ?? false;
	        }
	    }

	    return $meta;
	}
}

if (!function_exists('spark_rationalise_meta')) {
	/**
	 * Clean up meta array to include single values as direct value, like $single parameter to get_post_meta()
	 * @param array $meta
	 * @return array
	 */
	function spark_rationalise_meta(array $meta) {
	    $clean_meta = array();
	    foreach ($meta as $k => $v) {
	    	if (is_array($v) && count($v) == 1) {
	    		$clean_meta[$k] = maybe_unserialize($v[0]);
	    	} else {
	    		$clean_meta[$k] = maybe_unserialize($v);
	        }
	    }
	    return $clean_meta;
	}
}

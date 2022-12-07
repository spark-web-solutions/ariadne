<?php
if (!function_exists('spark_extract')) {
	/**
	 * Generate "teaser" text from longer content
	 * @param string $content Text to generate teaser from
	 * @param integer $max_chars Optional. Default 250.
	 * @param string $suffix Optional. Default '...'.
	 * @return string Teaser text
	 */
	function spark_extract($content, $max_chars = 200, $suffix = '...') {
		$content = str_replace("\n", ' ', strip_shortcodes($content));
		if (strlen(strip_tags($content)) > $max_chars) {
			return substr(strip_tags($content), 0, strrpos(substr(strip_tags($content), 0, $max_chars), ' ')+1).$suffix."\n";
		}
		return $content;
	}
}

if (!function_exists('spark_post_extract')) {
	/**
	 * Generate "teaser" text for post. Will use custom excerpt if defined, otherwise will look for WP "More" tag and return preceding content, else generate automatic extract via @see spark_extract().
	 * @param integer|WP_Post $post Optional. Post to use (will use global $post if not specified).
	 * @param integer $max_chars Optional. Default 250.
	 * @param string $suffix Optional. Default '...'.
	 * @return string|boolean Teaser text or false on failure
	 */
	function spark_post_extract($post = null, $max_chars = 200, $suffix = '...') {
		$post = get_post($post);
		if (!$post instanceof WP_Post) {
			return false;
		}
		if (!empty($post->post_excerpt)) { // Custom Excerpt
			$output = get_the_excerpt($post);
		} elseif (preg_match('/<!--more(.*?)?-->/', $post->post_content)) { // More
			global $more;
			$tmp_more = $more;
			$more = false;
			$output = get_the_content('', false, $post).$suffix;
			$more = $tmp_more;
		} else {
			$output = spark_extract($post->post_content, $max_chars, $suffix);
		}

		return $output;
	}
}

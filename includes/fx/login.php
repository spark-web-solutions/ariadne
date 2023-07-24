<?php
add_filter('login_redirect', 'spark_login_redirect', 10, 3);
if (!function_exists('spark_login_redirect')) {
	function spark_login_redirect($redirect_to, $request, $user) {
	    if (!is_wp_error($user) && !user_can($user, 'edit_posts')) {
	        return home_url('/');
	    }
	    return $redirect_to;
	}
}

add_filter('admin_init', 'spark_admin_redirect', 10, 3);
if (!function_exists('spark_admin_redirect')) {
	function spark_admin_redirect() {
	    if (!current_user_can('edit_posts') && !wp_doing_ajax()) {
	    	wp_redirect(home_url('/'));
	        exit;
	    }
	}
}

add_action('after_setup_theme', 'spark_remove_admin_bar');
if (!function_exists('spark_remove_admin_bar')) {
	function spark_remove_admin_bar() {
		if (!current_user_can('edit_posts') && !is_admin()) {
			show_admin_bar(false);
		}
	}
}

add_action('wp_logout', 'spark_logout_redirect');
if (!function_exists('spark_logout_redirect')) {
	function spark_logout_redirect() {
		wp_redirect(home_url('/'));
	}
}

add_filter('login_headerurl', 'spark_login_logo_url');
if (!function_exists('spark_login_logo_url')) {
	function spark_login_logo_url() {
		return home_url('/');
	}
}

add_filter('login_headertext', 'spark_login_logo_url_title');
if (!function_exists('spark_login_logo_url_title')) {
	function spark_login_logo_url_title() {
	    return '';
	}
}

add_action('login_footer', 'spark_login_footer');
if (!function_exists('spark_login_footer')) {
	function spark_login_footer() {
?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("p#backtoblog a").attr("href", '<?php echo esc_js(home_url('/')); ?>').html('← Back to Home');
    });
</script>
<?php
		$custom_logo_id = get_theme_mod('custom_logo');
		if (is_numeric($custom_logo_id)) {
			$logo_src = wp_get_attachment_image_src($custom_logo_id, 'medium');
			$width = min(320, $logo_src[1]);
			$height = round($logo_src[2]*$width/$logo_src[1]);
?>
<style>
body.login div#login h1 a {background-image: url(<?php echo $logo_src[0]; ?>) !important; margin: 0 auto; background-size: contain; width: <?php echo $width; ?>px; height: <?php echo $height; ?>px; max-width: 100%;}
</style>
<?php
		}
	}
}

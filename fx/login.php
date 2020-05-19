<?php
add_filter('login_redirect', 'spark_login_redirect', 10, 3);
function spark_login_redirect($redirect_to, $request, $user) {
    if (!is_wp_error($user) && !user_can($user, 'manage_options')) {
        return site_url('/');
    }
    return $redirect_to;
}

add_filter('admin_init', 'spark_admin_redirect', 10, 3);
function spark_admin_redirect() {
    if (!current_user_can('manage_options') && !wp_doing_ajax()) {
        wp_redirect(site_url('/'));
        exit;
    }
}

add_action('wp_logout', 'spark_logout_redirect');
function spark_logout_redirect() {
    wp_redirect(site_url('/'));
}

add_filter('login_headerurl', 'spark_login_logo_url');
function spark_login_logo_url() {
    return site_url('/');
}

add_filter('login_headertext', 'spark_login_logo_url_title');
function spark_login_logo_url_title() {
    return '';
}

add_action('login_footer', 'spark_login_footer');
function spark_login_footer() {
?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("p#backtoblog a").attr("href", '<?php echo esc_js(site_url('/')); ?>').html('← Back to Home');
    });
</script>
<?php
$logo_src = spark_get_theme_mod(ns_.'logo_large');
$size = getimagesize($logo_src);
$width = min(320, $size[0]);
$height = round($size[1]*$width/$size[0]);
?>
<style>
body.login div#login h1 a {background-image: url(<?php echo $logo_src; ?>) !important; margin: 0 auto; background-size: contain; width: <?php echo $width; ?>px; height: <?php echo $height; ?>px; max-width: 100%;}
</style>
<?php
}

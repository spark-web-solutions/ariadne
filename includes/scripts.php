<?php
if (is_admin()) {
	add_editor_style(get_template_directory_uri().'/assets/vendor/foundation/css/foundation.min.css');
	add_editor_style(get_template_directory_uri().'/style.css');
	add_editor_style(get_template_directory_uri().'/assets/css/editor.css');
	$external_fonts = spark_get_theme_mod('fonts_url');
	if (!empty($external_fonts)) {
		add_editor_style($external_fonts);
	}
}

add_action('wp_head', 'spark_google_maps_bootstrap');
function spark_google_maps_bootstrap() {
	$api = spark_google_map_api();
	if (!empty($api['key'])) {
?>
<script>
	(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
		key: "<?php echo $api['key']; ?>",
		v: "weekly",
	});
</script>
<?php
	}
}

add_action('wp_enqueue_scripts', 'spark_frontend_scripts', 1);
function spark_frontend_scripts() {
	// Core styles
	wp_enqueue_style('foundation', get_template_directory_uri().'/assets/vendor/foundation/css/foundation.min.css', array(), '6.4.2');

	// External fonts (configured through Customizer)
	$external_fonts = spark_get_theme_mod('fonts_url');
	if (!empty($external_fonts)) {
		wp_enqueue_style(ns_.'fonts', $external_fonts);
	}

	$theme = wp_get_theme('ariadne');

	// Theme styles
	wp_enqueue_style('spark-style', get_template_directory_uri().'/style.css', array('foundation'), $theme->get('Version'));
	wp_add_inline_style('spark-style', spark_generate_dynamic_styles());
	wp_enqueue_style('line-awesome', get_template_directory_uri().'/assets/vendor/line-awesome/css/line-awesome.min.css', array(), '1.3.0');

	// Administrator styles
	if (is_user_logged_in()) {
		wp_enqueue_style('spark-admin-style', get_template_directory_uri().'/assets/css/admin.css', array(), $theme->get('Version'));
	}

	// Header scripts

	// Footer sripts
	wp_enqueue_script('foundation', get_template_directory_uri().'/assets/vendor/foundation/js/foundation.min.js', array('jquery'), '6.4.2', true);
	wp_enqueue_script('spark-scripts', get_template_directory_uri().'/assets/js/spark.js', array('jquery'), $theme->get('Version'), true);

	// Remove admin-only styles for front end
	if (!is_admin()) {
		wp_deregister_style('thickbox');
		wp_deregister_style('tiptipCSS');
		wp_deregister_style('chosenCSS');
		wp_deregister_style('jqueryuiCSS');
		wp_deregister_style('wpclef-main');
	}
}

/**
 * Gutenberg scripts and styles
 */
add_action('enqueue_block_editor_assets', 'spark_gutenberg_scripts');
function spark_gutenberg_scripts() {
	$theme = wp_get_theme('ariadne');
	wp_enqueue_script('spark-editor', get_template_directory_uri().'/assets/js/editor.js', array('wp-blocks', 'wp-dom'), $theme->get('Version'), true);
}

<?php
define('SPARK_DEFAULT_COLOUR_COUNT', 8);

add_action('customize_register', 'spark_theme_customizer');
if (!function_exists('spark_theme_customizer')) {
	function spark_theme_customizer(WP_Customize_Manager $wp_customize) {
		// Key Images
		$wp_customize->add_section(ns_.'theme_images_section', array(
				'title' => __('Additional Images', SPARK_THEME_TEXTDOMAIN),
				'priority' => 30,
		));
		// footer logo
		$wp_customize->add_setting(ns_.'logo_footer', array(
				'default' => '',
				'sanitize_callback' => 'esc_url_raw',
				'type' => 'option',
		));
		$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, ns_.'logo_footer', array(
				'label' => __('Footer Logo', SPARK_THEME_TEXTDOMAIN),
				'section' => ns_.'theme_images_section',
				'priority' => 35,
		)));
		// default featured image
		$wp_customize->add_setting(ns_.'default_featured_image', array(
				'default' => '',
				'sanitize_callback' => 'esc_url_raw',
				'type' => 'option',
		));
		$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, ns_.'default_featured_image', array(
				'label' => __('Default Featured Image', SPARK_THEME_TEXTDOMAIN),
				'section' => ns_.'theme_images_section',
				'priority' => 50,
		)));

		// Fonts
		$wp_customize->add_section(ns_.'fonts', array(
				'title' => __('Fonts', SPARK_THEME_TEXTDOMAIN),
				'priority' => 45,
		));
		$wp_customize->add_setting(ns_.'font', array(
				'default' => 'Open Sans,Montserrat',
				'sanitize_callback' => 'sanitize_text_field',
				'type' => 'option',
		));
		$wp_customize->add_control(ns_.'font', array(
				'label' => __('Fonts', SPARK_THEME_TEXTDOMAIN),
				'section' => ns_.'fonts',
				'type' => 'text',
				'priority' => 5,
		));
		$wp_customize->add_setting(ns_.'fonts_url', array(
				'default' => esc_url('//fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i|Montserrat:400,400i,700,700i'),
				'sanitize_callback' => 'esc_url_raw',
				'type' => 'option',
		));
		$wp_customize->add_control(ns_.'fonts_url', array(
				'label' => __('External Fonts URL (e.g. Google Fonts)', SPARK_THEME_TEXTDOMAIN),
				'section' => ns_.'fonts',
				'type' => 'textarea',
				'priority' => 10,
		));

		// Palette
		$wp_customize->add_section(ns_.'palette', array(
				'title' => __('Theme Palette', SPARK_THEME_TEXTDOMAIN),
				'description' => __('Enter number of colours. Click save and reload the page.', SPARK_THEME_TEXTDOMAIN),
				'priority' => 50,
		));
		$wp_customize->add_setting(ns_.'colours', array(
				'default' => SPARK_DEFAULT_COLOUR_COUNT,
				'sanitize_callback' => 'absint',
				'type' => 'option',
		));
		$wp_customize->add_control(ns_.'colours', array(
				'label' => __('Number of colours in the palette', SPARK_THEME_TEXTDOMAIN),
				'section' => ns_.'palette',
				'type' => 'text',
				'priority' => 10,
		));
		$colours = spark_get_theme_mod(ns_.'colours', SPARK_DEFAULT_COLOUR_COUNT);
		for ($i = 1; $i <= $colours; $i++) {
			$wp_customize->add_setting(ns_.'colour'.$i, array(
					'default' => '#FFFFFF',
					'sanitize_callback' => 'sanitize_hex_color',
					'type' => 'option',
			));
			$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, ns_.'colour'.$i, array(
					/* translators: %d: numeric index of the colour. */
					'label' => sprintf(__('Colour %d', SPARK_THEME_TEXTDOMAIN), $i),
					'description' => 'var(--colour'.$i.'), .bg'.$i.', .hbg'.$i.', .text'.$i.', .htext'.$i.', .border'.$i.', .hborder'.$i,
					'section' => ns_.'palette',
					'priority' => 10 + $i,
			)));
		}

		// Key Dimensions
		$wp_customize->add_section(ns_.'key_dimensions', array(
				'title' => __('Key Dimensions', SPARK_THEME_TEXTDOMAIN),
				'priority' => 52,
		));
		$wp_customize->add_setting(ns_.'border_radius', array(
				'default' => '3px',
				'sanitize_callback' => 'sanitize_text_field',
				'type' => 'option',
		));
		$wp_customize->add_control(ns_.'border_radius', array(
				'description' => __('Default border radius for use across the site. Must include units (px, rem or % recommended) unless zero.', SPARK_THEME_TEXTDOMAIN),
				'label' => __('Global Border Radius', SPARK_THEME_TEXTDOMAIN),
				'section' => ns_.'key_dimensions',
				'type' => 'text',
				'priority' => 5,
		));
		$wp_customize->add_setting(ns_.'site_max_width', array(
				'default' => '130rem',
				'sanitize_callback' => 'sanitize_text_field',
				'type' => 'option',
		));
		$wp_customize->add_control(ns_.'site_max_width', array(
				'description' => __('Maximum width for the entire site. Highly recommended to be entered in rem.', SPARK_THEME_TEXTDOMAIN),
				'label' => __('Max Site Width', SPARK_THEME_TEXTDOMAIN),
				'section' => ns_.'key_dimensions',
				'type' => 'text',
				'priority' => 10,
		));
		$wp_customize->add_setting(ns_.'row_max_width', array(
				'default' => '100rem',
				'sanitize_callback' => 'sanitize_text_field',
				'type' => 'option',
		));
		$wp_customize->add_control(ns_.'row_max_width', array(
				'description' => __('Maximum width for content rows. Highly recommended to be entered in rem.', SPARK_THEME_TEXTDOMAIN),
				'label' => __('Max Row Width', SPARK_THEME_TEXTDOMAIN),
				'section' => ns_.'key_dimensions',
				'type' => 'text',
				'priority' => 15,
		));

		$pages = array(
				'home' => __('Home', SPARK_THEME_TEXTDOMAIN),
				'other' => __('Other', SPARK_THEME_TEXTDOMAIN),
		);
		$sizes = array(
				'small' => __('Small', SPARK_THEME_TEXTDOMAIN),
				'medium' => __('Medium', SPARK_THEME_TEXTDOMAIN),
				'large' => __('Large', SPARK_THEME_TEXTDOMAIN),
		);
		$p = 20;
		foreach ($pages as $page => $page_label) {
			foreach ($sizes as $size => $size_label) {
				$setting_name = 'hero_height_'.$page.'_'.$size;
				$wp_customize->add_setting(ns_.$setting_name, array(
						'default' => 'calc(100vw * 9/16)',
						'sanitize_callback' => 'sanitize_text_field',
						'type' => 'option',
				));
				$wp_customize->add_control(ns_.$setting_name, array(
						/* translators: %1$s: page type; %2$s: screen size. */
						'label' => sprintf(__('Hero Height - %1$s (%2$s)', SPARK_THEME_TEXTDOMAIN), $page_label, $size_label),
						'section' => ns_.'key_dimensions',
						'type' => 'text',
						'priority' => $p++,
				));
			}
		}

		// Contact Details
		$wp_customize->add_section(ns_.'contacts_section', array(
				'title' => __('Footer and Contact Details', SPARK_THEME_TEXTDOMAIN),
				'priority' => 60,
		));
		$wp_customize->add_setting(ns_.'contact_email', array(
				'sanitize_callback' => 'sanitize_email',
				'type' => 'option',
		));
		$wp_customize->add_control(ns_.'contact_email', array(
				'label' => __('Email', SPARK_THEME_TEXTDOMAIN),
				'section' => ns_.'contacts_section',
				'type' => 'text',
				'priority' => 10,
		));
		$wp_customize->add_setting(ns_.'contact_phone', array(
				'sanitize_callback' => 'sanitize_text_field', // This will do for now
				'type' => 'option',
		));
		$wp_customize->add_control(ns_.'contact_phone', array(
				'label' => __('Phone Number', SPARK_THEME_TEXTDOMAIN),
				'section' => ns_.'contacts_section',
				'type' => 'text',
				'priority' => 20,
		));
		$wp_customize->add_setting(ns_.'contact_address', array(
				'sanitize_callback' => 'sanitize_textarea_field', // This will do for now
				'type' => 'option',
		));
		$wp_customize->add_control(ns_.'contact_address', array(
				'label' => __('Address', SPARK_THEME_TEXTDOMAIN),
				'section' => ns_.'contacts_section',
				'type' => 'textarea',
				'priority' => 30,
		));
		$wp_customize->add_setting(ns_.'footer_text', array(
				'sanitize_callback' => 'sanitize_textarea_field', // This will do for now
				'type' => 'option',
		));
		$wp_customize->add_control(ns_.'footer_text', array(
				'label' => __('Footer Text', SPARK_THEME_TEXTDOMAIN),
				'section' => ns_.'contacts_section',
				'type' => 'textarea',
				'priority' => 30,
		));
		$wp_customize->add_setting(ns_.'copyright', array(
				'default' => '&copy; Copyright',
				'sanitize_callback' => 'sanitize_text_field',
				'type' => 'option',
		));
		$wp_customize->add_control(ns_.'copyright', array(
				'label' => __('Copyright Text', SPARK_THEME_TEXTDOMAIN),
				'section' => ns_.'contacts_section',
				'type' => 'text',
				'priority' => 80,
		));

		// External APIs
		$wp_customize->add_section(ns_.'external_apis_section', array(
				'title' => __('External APIs', SPARK_THEME_TEXTDOMAIN),
				'priority' => 80,
		));
		$wp_customize->add_setting(ns_.'google_maps_api', array(
				'default' => '',
				'sanitize_callback' => 'sanitize_text_field',
				'type' => 'option',
		));
		$wp_customize->add_control(ns_.'google_maps_api', array(
				'label' => __('Google Maps API Key', SPARK_THEME_TEXTDOMAIN),
				'section' => ns_.'external_apis_section',
				'type' => 'text',
				'priority' => 10,
		));
	}
}

if (!function_exists('spark_get_theme_mod')) {
	function spark_get_theme_mod($key, $default = '') {
		if (strpos($key, ns_) !== 0) {
			$key = ns_.$key;
		}
		$value = get_option($key);
		if (empty($value)) {
			$value = get_theme_mod($value);
		}
		if (empty($value)) {
			$value = $default;
		}
		return $value;
	}
}

add_action('customize_save_after', 'spark_save_default_customizer_values');
if (!function_exists('spark_save_default_customizer_values')) {
	function spark_save_default_customizer_values(WP_Customize_Manager $wp_customize) {
		$settings = $wp_customize->settings();
		$mods = get_theme_mods();
		foreach ($settings as $setting) {
			/** @var WP_Customize_Setting $setting */
			if ($setting->type == 'option') {
				add_option($setting->id, $setting->default);
			} elseif (!isset($mods[$setting->id])) {
				set_theme_mod($setting->id, $setting->default);
			}
		}
	}
}

if (!function_exists('spark_generate_dynamic_styles')) {
	function spark_generate_dynamic_styles() {
		$rules = '';
		$vars = '';

		// Font styles
		$font = spark_get_theme_mod(ns_.'font');
		if (!empty($font)) {
			$fonts = explode(',', $font);
			if (count($fonts) == 1) {
				$fonts[] = $fonts[0]; // Make sure we have at least 2 fonts defined, even if they're both the same
			}
			for ($i = 0; $i < count($fonts); $i++) {
				$vars .= '	--font'.($i+1).': "'.$fonts[$i].'", sans-serif;'."\n";
			}
		}

		// Colour variables and helper classes for text, background and border colours
		$colour_count = spark_get_theme_mod(ns_.'colours', SPARK_DEFAULT_COLOUR_COUNT);
		for ($i = 0; $i <= $colour_count; $i++) {
			if ($i == 0) {
				$colour = 'transparent';
			} else {
				$colour = spark_get_theme_mod(ns_.'colour'.$i);
			}
			$vars .= '	--colour'.$i.': '.$colour.';'."\n";
			$rules .= '.text'.$i.', .has-colour-'.$i.'-color, a.has-colour-'.$i.'-color:link, a.has-colour-'.$i.'-color:visited, .panel-wrapper.text'.$i.' *:not(input):not(select):not(textarea) {color: var(--colour'.$i.');}'."\n";
			$rules .= '.bg'.$i.', .has-colour-'.$i.'-background-color, a.has-colour-'.$i.'-background-color:link, a.has-colour-'.$i.'-background-color:visited {background-color: var(--colour'.$i.');}'."\n";
			$rules .= '.border'.$i.' {border-color: var(--colour'.$i.');}'."\n";
			$rules .= '.htext'.$i.':hover, .panel-wrapper.text'.$i.':hover *:not(input):not(select):not(textarea) {color: var(--colour'.$i.');}'."\n";
			$rules .= '.hbg'.$i.':hover {background-color: var(--colour'.$i.');} '."\n";
			$rules .= '.hborder'.$i.':hover {border-color: var(--colour'.$i.');}'."\n";
		}

		// Key dimensions
		$border_radius = spark_get_theme_mod(ns_.'border_radius');
		$row_max_width = spark_get_theme_mod(ns_.'row_max_width');
		$site_max_width = spark_get_theme_mod(ns_.'site_max_width');
		$pages = array('Home', 'Other');
		$sizes = array('Small', 'Medium', 'Large');
		foreach ($pages as $page) {
			foreach ($sizes as $size) {
				$setting_name = 'hero_height_'.strtolower($page.'_'.$size);
				$$setting_name = spark_get_theme_mod($setting_name);
			}
		}

		// Custom styles including key dimensions
		$styles = <<<EOS
:root {
$vars
	--row-max-width: $row_max_width;
	--site-max-width: $site_max_width;
	--hero-height: $hero_height_other_small;
	--border-radius: $border_radius;
}
.home {
	--hero-height: $hero_height_home_small;
}

@media only screen and (min-width: 40em) { /* <-- min-width 640px - medium screens and up */
	:root {
		--hero-height: $hero_height_other_medium;
	}
	.home {
		--hero-height: $hero_height_home_medium;
	}
}
@media only screen and (min-width: 64em) { /* <-- min-width 1024px - large screens and up */
	:root {
		--hero-height: $hero_height_other_large;
	}
	.home {
		--hero-height: $hero_height_home_large;
	}
}

$rules
EOS;
		return $styles;
	}
}

if (!function_exists('spark_get_theme_palette')) {
	function spark_get_theme_palette() {
		$colours = array();
		$colour_count = spark_get_theme_mod(ns_.'colours', SPARK_DEFAULT_COLOUR_COUNT);

		for ($i = 1; $i <= $colour_count; $i++) {
			$colours[] = array(
					'name' => 'Colour'.$i,
					'slug' => 'colour-'.$i,
					'color' => spark_get_theme_mod(ns_.'colour'.$i),
			);
		}

		return $colours;
	}
}

add_action('customize_register', 'spark_load_customize_controls', 0);
if (!function_exists('spark_load_customize_controls')) {
	function spark_load_customize_controls() {
		require_once(trailingslashit(get_template_directory()).'includes/classes/customizer/checkbox-multiple.php');
		require_once(trailingslashit(get_template_directory()).'includes/classes/customizer/wp-editor.php');
	}
}

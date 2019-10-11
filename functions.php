<?php
define('ns_', 'spark_');
define('THEME_TEXTDOMAIN', ns_.'theme');

define('SHORT_TERM', 0.25 * HOUR_IN_SECONDS);
define('MEDIUM_TERM', defined('SPARK_ENV') && SPARK_ENV == 'PRODUCTION' ? 4 * HOUR_IN_SECONDS : SHORT_TERM);
define('LONG_TERM', defined('SPARK_ENV') && SPARK_ENV == 'PRODUCTION' ? 24 * HOUR_IN_SECONDS : SHORT_TERM);

$theme_files = array(
        // Theme elements
        array('file' => 'customizer.php',           'dir' => 'theme'), // Our customizer fields & settings
        array('file' => 'functions.php',            'dir' => 'theme'), // Our core theme functions
        array('file' => 'menus.php',                'dir' => 'theme'), // Registers and displays our menus
        array('file' => 'scripts.php',              'dir' => 'theme'), // Enqueues our styles and scripts

        // Helper functions
        array('file' => 'cards.php',                'dir' => 'fx'),
        array('file' => 'children.php',             'dir' => 'fx'),
        array('file' => 'columns.php',              'dir' => 'fx'),
        array('file' => 'convert-colour.php',       'dir' => 'fx'),
        array('file' => 'extract.php',              'dir' => 'fx'),
        array('file' => 'featured-image.php',       'dir' => 'fx'),
        array('file' => 'hero.php',                 'dir' => 'fx'),
        array('file' => 'hierarchy-walker.php',     'dir' => 'fx'),
        array('file' => 'login.php',                'dir' => 'fx'),
        // array('file' => 'map.php',                  'dir' => 'fx'),
        array('file' => 'meta.php',                 'dir' => 'fx'),
        array('file' => 'pagination.php',           'dir' => 'fx'),
        array('file' => 'panels.php',               'dir' => 'fx'),
        array('file' => 'search.php',               'dir' => 'fx'),
        array('file' => 'slug.php',                 'dir' => 'fx'),
        array('file' => 'time.php',                 'dir' => 'fx'),
        // array('file' => 'tracking-widget.php',      'dir' => 'fx'),

        // Miscellaneous utilities
        array('file' => 'cookies.php',              'dir' => 'utils'), // Spark_Cookie()       - handy cookie management
        array('file' => 'random.php',               'dir' => 'utils'), // Spark_Random()       - funky logic for displaying random items, with caching if desired
        array('file' => 'transients.php',           'dir' => 'utils'), // Spark_Transients()   - transient management

        // Information architecture
        array('file' => 'cpt_.php',                 'dir' => 'ia'),
        array('file' => 'cpt_tax_.php',             'dir' => 'ia'),
        array('file' => 'tax_.php',                 'dir' => 'ia'),
        array('file' => 'hero.php',                 'dir' => 'ia'),
        array('file' => 'panels.php',               'dir' => 'ia'),
        array('file' => 'search.php',               'dir' => 'ia'),
        array('file' => 'blocks.php',                  'dir' => 'ia'),

        // Custom Gravity Forms pieces
        array('file' => 'australian-states.php',    'dir' => 'gf'), // Adds Australia address type
        array('file' => 'columns.php',              'dir' => 'gf'), // Adds support for multi-column Gravity Forms
        array('file' => 'enable-fields.php',        'dir' => 'gf'), // Enables Credit Card and Password field types
);

foreach ($theme_files as $theme_file) {
    spark_init::include_file($theme_file);
}

class spark_init {
    static function include_file($args) {
        if (!is_array($args)) {
            parse_str($args, $args);
        }
        extract($args);

        // check for required variables
        if (!$dir && !$file) {
            return;
        }

        // include required theme part
        $dir == '' ? locate_template(array($file), true) : locate_template(array($dir. '/' . $file), true);
    }
}

define('ROW_MAX_WIDTH', spark_get_theme_mod(ns_.'row_max_width'));
define('SITE_MAX_WIDTH', spark_get_theme_mod(ns_.'site_max_width'));

add_action('customize_register', 'spark_load_customize_controls', 0);
function spark_load_customize_controls() {
    require_once(trailingslashit(get_template_directory()).'theme/customizer/checkbox-multiple.php');
    require_once(trailingslashit(get_template_directory()).'theme/customizer/wp-editor.php');
}

add_filter('bbx_best_before_post_types_covered', 'spark_add_best_before_post_types');
function spark_add_best_before_post_types(array $post_types) {
    $post_types[] = 'panel';
    return $post_types;
}

// Hide ACF field group menu item
add_filter('acf/settings/show_admin', '__return_false');

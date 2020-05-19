<?php
/**
 * Simplifying working with multiple transients
 * @version 1.0.1
 * @author Chris Chatterton <chris@brownbox.net.au> - Base functionality
 * @author Mark Parnell <markparnell@brownbox.net.au> - Class wrapper and auto-refresh functionality
 */
class Spark_Transients {
    /**
     * Constructor. Sets up necessary hooks etc.
     */
    public function __construct() {
        add_action('save_post', array($this, 'save_post'), 10, 3);
        add_action('delete_post', array($this, 'delete_post'), 10, 1);
        add_action('updated_post_meta', array($this, 'updated_post_meta'), 10, 4);
        add_action('wp_update_nav_menu', array($this, 'wp_update_nav_menu'), 10, 2);
        add_action('comment_post', array($this, 'comment_post'), 10, 3);
    }

    public static function use_transients() {
    	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    		return false;
    	}
        if (isset($_GET['transients']) && $_GET['transients'] == 'false') {
            return false;
        }
        if (isset($_GET['preview']) && $_GET['preview'] == 'true') {
            return false;
        }
        if (defined('SPARK_USE_TRANSIENTS')) {
            return SPARK_USE_TRANSIENTS;
        }
        return true;
    }

    public static function name(array $args) {
        extract($args);

        $local['file'] = basename($file, '.php');
        $local['folders'] = explode(DIRECTORY_SEPARATOR, dirname($file));
        $local['folder'] = $local['folders'][count($local['folders'])-1];

        $key = md5(dirname($file).'/'.$file);

        $t_name = ns_.$local['folder'].'_'.$local['file'].'_'.$name.'_'.$key;

        return $t_name;
    }

    /**
     * @param array $args {
     *     Optional. Arguments used to find matching transients. If empty, will return all transients.
     *
     *     @type string     $string     Search term to match transients against.
     * }
     * @return array The matching transients.
     */
    public static function get($args = '') {
        if (!is_array($args)) {
            parse_str($args, $args);
        }
        extract($args);
        if (!isset($string)) {
            $string = (is_string($args)) ? $args : "";
        }

        // Test for required variables and set defaults
        $matching = array();

        global $wpdb;
        $sql = "SELECT `option_name` AS `name`, `option_value` AS `value`
        FROM $wpdb->options
        WHERE `option_name` LIKE '%transient_%'
        ORDER BY `option_name`";

        $transients = $wpdb->get_results($sql);

        // filter by $string (if provided)
        foreach ($transients as $transient) {
            if (empty($string) || false !== strpos($transient->name, $string)) {
                $matching[] = $transient;
            }
        }
        $transients = $matching;

        // return results
        return $transients;
    }

    /**
     * @param array $args {
     *     Optional. Arguments used to define which transients to delete.
     *
     *     @type string     $string     Search term to match transients against. If both $string and $transients are empty, will delete all transients.
     *     @type array      $transients List of transients to delete. Each item must be an object with at least a name property pertaining to the name of the transient.
     *                                  If both $string and $transients are empty, will delete all transients.
     * }
     * @return array List of deleted transients
     */
    public static function delete($args = '') {
        if (!is_array($args)) {
            parse_str($args, $args);
        }
        extract($args);
        $matching = array();

        // Test for required variables and set defaults
        if (!isset($string)) {
            $string = (is_string($args)) ? $args : "";
        }
        if (!isset($transients) || !is_array($transients)) {
            $transients = self::get($args);
        }

        // loop through and delete matching transients
        foreach ($transients as $transient) {
            if (empty($string) || false !== strpos($transient->name, $string)) {
                $matching[] = str_replace('_transient_', '', $transient->name);
                delete_transient(str_replace('_transient_', '', $transient->name));
            }
        }

        // return results
        $matching = implode(', ', $matching);
        return $matching;
    }

    /**
     *
     * @param array $args {
     *     Optional. Arguments used to define cleaning behaviour.
     *
     *     @type string     $string     Search term to match transients against.
     *     @type array      $transients List of transients to clean. Each item must be an object with at least name and value properties pertaining to the name and value of the transient respectively.
     *                                  If both $string and $transients are empty, will clean all transients.
     *     @type string|array $clean    Values to remove from matching transients
     * }
     * @return array List of cleaned transient values
     */
    public static function clean($args = '') {
        if (!is_array($args)) {
            parse_str($args, $args);
        }
        extract($args);

        // Test for required variables and set defaults
        if (!isset($string)) {
            $string = "";
        }
        if (!is_array($transients)) {
            $transients = self::get($string);
        }
        echo '<!-- ' . count($transients) . ' transients cleaned -->' . "\n";

        if (!isset($clean)) {
            return $transients;
        } else {
            $clean = (is_string($clean)) ? array($clean) : $clean;
        }

        $results = array();

        foreach ($transients as $transient) {
            echo '<!-- ' . $transient->name . ' -->' . "\n";
            if (strpos($transient->name, $string) && false === strpos($transient->name, 'timeout')) {
                foreach ($clean as $value) {
                    $transient->value = str_replace($value, "  ", $transient->value);
                }
                $results[] = $transient->value;
            }
        }
        $results = implode("  ", $results);
        return $results;
    }

    /**
     * Fires on save_post hook to clear all transients associated with that post
     * @param integer $post_id
     * @param WP_Post $post
     * @param boolean $update
     */
    public function save_post($post_id, WP_Post $post, $update) {
        // No point in doing anything if it's new
        if ($update) {
            $this->clear_post_transients($post_id);
        }
    }

    /**
     * Fires on delete_post hook to clear all transients associated with that post
     * @param integer $post_id
     */
    public function delete_post($post_id) {
        $this->clear_post_transients($post_id);
    }

    /**
     * Fires on updated_post_meta hook to clear transients associated with the post
     * @param integer $meta_id
     * @param integer $post_id
     * @param string $meta_key
     * @param mixed $_meta_value
     */
    public function updated_post_meta($meta_id, $post_id, $meta_key, $_meta_value) {
        $this->clear_post_transients($post_id);
    }

    /**
     * Fires on saving menu to clear all nav transients
     * @param integer $menu_id
     * @param array $menu_data
     */
    public function wp_update_nav_menu($menu_id, array $menu_data = array()) {
        self::delete('nav');
    }

    /**
     * Fires when a comment is added
     * @param integer $comment_ID The comment ID
     * @param integer|string $comment_approved 1 if the comment is approved, 0 if not, 'spam' if spam
     * @param array $commentdata Comment data
     */
    public function comment_post($comment_ID, $comment_approved, $commentdata) {
        if ($comment_approved == 1) {
            $this->clear_post_transients($commentdata['comment_post_ID']);
        }
    }

    /**
     * Removes all transients associated with the specified post, including parent posts and archives
     * @param integer $post_id
     */
    private function clear_post_transients($post_id) {
        // Delete transients for current post
        self::delete('_'.$post_id.'_');

        // Delete transients for archives
        $post_type = get_post_type($post_id);
        self::delete('_'.$post_type.'_');

        // Have to also remove transients for ancestors or we may run into issues with "children as..." templates
        $ancestors = get_ancestors($post_id, $post_type);
        foreach ($ancestors as $ancestor_id) {
            self::delete('_'.$ancestor_id.'_');
        }
    }
}
new Spark_Transients();

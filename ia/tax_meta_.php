<?php
namespace Spark_Theme;
class taxMetaClass {
    function __construct($label, array $taxonomies, array $fields) {
        $this->label = $label;
		$this->slug = str_replace(' ','',strtolower($label));
		$this->taxonomies = $taxonomies;
        $this->fields = $fields;

        foreach ($this->taxonomies as $taxonomy) {
            add_action($taxonomy.'_add_form_fields', array($this, 'add_fields'));
            add_action($taxonomy.'_edit_form_fields', array($this, 'edit_fields'), 10, 2);
            add_action('created_'.$taxonomy, array($this, 'save_meta'), 10, 2);
            add_action('edited_'.$taxonomy, array($this, 'save_meta'), 10, 2);
        }
    }

    function add_fields($taxonomy) {
        $meta_fields = array();
        wp_nonce_field(plugin_basename(__FILE__), 'metabox_content_nonce');
        echo '<div class="form-field term-group">'."\n";
        echo '<h3>'.$this->label.'</h3>'."\n";
        foreach ($this->fields as $field) {
            array_push($meta_fields, $this->new_field($field));
        }
        echo '</div>'."\n";
        set_transient($this->slug.'_meta_fields', serialize($meta_fields), 3600);
    }

    function edit_fields($term, $taxonomy) {
        $meta_fields = array();

        echo '<tr class="form-field term-group-wrap">'."\n";
        echo '<th scope="row" colspan="2">'."\n";
        echo '<h3>'.$this->label.'</h3>'."\n";
        wp_nonce_field(plugin_basename(__FILE__), 'metabox_content_nonce');
        echo '</th>'."\n";
        echo '</tr>'."\n";
        foreach ($this->fields as $field) {
            echo '<tr class="form-field term-group-wrap">'."\n";
            echo '<td colspan="2">'."\n";
            array_push($meta_fields, $this->new_field($field));
            echo '</td>'."\n";
            echo '</tr>'."\n";
        }

        set_transient($this->slug.'_meta_fields', serialize($meta_fields), 3600);
    }

    function save_meta($term_id, $tt_id) {
        $meta_fields = unserialize(get_transient($this->slug.'_meta_fields'));
        foreach($meta_fields as $meta_field) {
            update_term_meta($term_id, $meta_field, sanitize_text_field($_POST[$meta_field]));
        }
    }

    function new_field($args) {
        is_array($args) ? extract($args) : parse_str($args);

        //set defaults
        if (!$title && !$field_name) {
            return;
        }
        if (!$title && $field_name) {
            $title = $field_name;
        }
        $title = ucfirst(strtolower($title));
        if (!$field_name && $title) {
            $field_name = $title;
        }
        $field_name = strtolower(str_replace(' ', '_', $field_name));
        if (!$size) {
            $size = '100%'; // accepts valid css for all types expect textarea. Textarea expects an array where [0] is width and [1] is height
        }
        if (!$max_width) {
            $max_width = '100%';
        }
        if (!$type) {
            $type = 'text';
        }

        $script = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/') + 1);
        $field_name = ($source == 'option') ? $group . "[" . $name . "]" : $field_name;

        switch ($type) {
            case 'checkbox':
                $checked = get_term_meta($_GET['tag_ID'], $field_name, true) == 'true' ? 'checked="checked"' : '';
                echo '   <input type="checkbox" name="' . $field_name . '" value="true" ' . $checked . ' style="margin: 0 5px 0px 0;"/><label style="color:rgba(0,0,0,0.75);">' . $title . '</label>' . "\n";
                break;

            case 'textarea':
                $value = get_term_meta($_GET['tag_ID'], $field_name, true);
                if ($default && !$value) {
                    $value = $default;
                }
                echo '	<label for="' . $field_name . '">' . "\n";
                echo '   	' . $title . "\n";
                echo '   </label>' . "\n";
                if (!is_array($size)) {
                    $size = explode(',', $size);
                }
                $style = 'width:' . $size[0] . ';height:' . $size[1] . ';max-width:' . $max_width . ';';
                echo '   <textarea id="' . $field_name . '" name="' . $field_name . '" style="' . $style . ';" placeholder="' . $placeholder . '" >' . esc_attr($value) . '</textarea>' . "\n";
                break;

            case 'select':
                // expects an $options array of arrays as follows
                // $options = array (
                //		array ( 'label' => 'aaa', 'value' => '1' ),
                //		array ( 'label' => 'aaa', 'value' => '1' ),
                //		);
                $current = get_term_meta($_GET['tag_ID'], $field_name, true);
                echo '	' . $title . "\n";
                echo '    <select name="' . $field_name . '" id="' . $field_name . '">' . "\n";
                foreach ($options as $option) {
                    echo '        <option value="' . $option['value'] . '" ' . selected($option['value'], $current, false) . '>' . $option['label'] . '</option>' . "\n";
                }
                echo '	  </select>' . "\n";
                break;

            case 'color-picker':
                echo '	<label for="meta-color" class="prfx-row-title">' . $title . '</label>' . "\n";
                echo '	<input name="' . $field_name . '" type="text" value="' . get_term_meta($_GET['tag_ID'], $field_name, true) . '" class="meta-color" />' . "\n";
                break;

            case 'wp-editor':
                $value = get_term_meta($_GET['tag_ID'], $field_name, true);
                if ($default && !$value) {
                    $value = $default;
                }
                wp_editor($value, $field_name, $settings);
                break;

            case 'text':
            default:
                $value = get_term_meta($_GET['tag_ID'], $field_name, true);
                if ($default && !$value) {
                    $value = $default;
                }
                echo '	<label for="' . $field_name . '">' . "\n";
                echo '		' . $title . "\n";
                echo '	</label>' . "\n";
                echo '  <input type="' . $type . '" id="' . $field_name . '" name="' . $field_name . '" style="display:block;max-width:' . $max_width . ';width:' . $size . ';" placeholder="' . $placeholder . '" value="' . esc_attr($value) . '" />' . "\n";
                break;
        }
        if ($description) {
            echo '   <p class="description">' . $description . '</p>' . "\n";
        }
        return $field_name;
    }
}

<?php
/**
 * Multiple checkbox customize control class.
 */
class Spark_Customize_Control_Checkbox_Multiple extends WP_Customize_Control {
    /**
     * The type of customize control being rendered.
     * @var string
     */
    public $type = 'checkbox-multiple';

    /**
     * Enqueue scripts/styles.
     */
    public function enqueue() {
        wp_enqueue_script('spark-customize-checkbox-multiple', trailingslashit(get_template_directory_uri()).'js/customizer/checkbox-multiple.js', array('jquery'));
    }

    /**
     * Displays the control content.
     */
    public function render_content() {
    	if (empty($this->choices)) {
            return;
    	}

        if (!empty($this->label)) {
?>
<span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
<?php
        }
        if (!empty($this->description)) {
?>
<span class="description customize-control-description"><?php echo $this->description; ?></span>
<?php
        }
        $multi_values = !is_array($this->value()) ? explode(',', $this->value()) : $this->value();
?>
<ul>
<?php
        foreach ($this->choices as $value => $label) {
?>
    <li><label><input type="checkbox" name="<?php echo $this->id ?>[]" value="<?php echo esc_attr($value); ?>" <?php checked(in_array($value, $multi_values)); ?>><?php echo esc_html($label); ?></label></li>
<?php
        }
?>
</ul>
<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr(implode(',', $multi_values)); ?>">
<?php

    }
}

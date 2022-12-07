<?php
add_filter('gform_field_content', 'spark_gf_column_splits', 10, 5);
if (!function_exists('spark_gf_column_splits')) {
	// @todo add support for new GF 2.5+ markup
	function spark_gf_column_splits($content, $field, $value, $lead_id, $form_id) {
		if (!is_admin()) { // Only perform on the front end
			// Target section breaks
			if ($field['type'] == 'section') {
				$form = RGFormsModel::get_form_meta($form_id, true);

				// Check for the presence of multi-column form classes
				$form_class = explode(' ', $form['cssClass']);

				$form_class_matches = array_intersect($form_class, array('two-column', 'three-column'));

				// Check for the presence of section break column classes
				$field_class = explode(' ', $field['cssClass']);
				$field_class_matches = array_intersect($field_class, array('gform_column'));

				// If field is a column break in a multi-column form, perform the list split
				if (!empty($form_class_matches) && !empty($field_class_matches)) { // Make sure to target only multi-column forms
					// Retrieve the form's field list classes for consistency
					$form = RGFormsModel::add_default_properties($form);
					$description_class = rgar($form, 'descriptionPlacement') == 'above' ? 'description_above' : 'description_below';

					// Close current field's li and ul and begin a new list with the same form field list classes
					return '</li></ul><ul class="gform_fields '.$form['labelPlacement'].' '.$description_class.' '.$field['cssClass'].'"><li class="gfield gsection empty">'.$field['label'];
				}
			}
		}
		return $content;
	}
}

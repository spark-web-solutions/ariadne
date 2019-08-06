<?php
// Register custom block types (requires ACF Pro!)
if (function_exists('acf_register_block_type')) {
    add_action('acf/init', 'spark_register_block_types');
    function spark_register_block_types() {
        acf_register_block_type(array(
                'name'              => 'panel',
                'title'             => __('Panel'),
                'description'       => __('Insert a panel in your page as a block.'),
                'render_template'   => trailingslashit(get_stylesheet_directory()).'blocks/panel.php',
                'category'          => 'layout',
                'icon'              => 'layout',
                'keywords'          => array('panel'),
                'mode'              => 'auto',
                'align'             => 'full',
                'supports'          => array(
                        'align' => false,
                ),
        ));
    }

    add_action('admin_init', 'spark_block_meta'); // Needs to run after init to get CPTs in post type list
    function spark_block_meta() {
        if (function_exists("register_field_group")) {
            register_field_group(array(
                    'id' => 'acf_block_panel_settings',
                    'title' => 'Block: Panel',
                    'fields' => array(
                            array(
                                    'key' => 'spark_block_panel_field_panel',
                                    'label' => 'Panel',
                                    'name' => 'panel',
                                    'type' => 'post_object',
                                    'post_type' => array('panel'),
                                    'return_format' => 'id',
                            ),
                    ),
                    'location' => array(
                            array(
                                    array(
                                            'param' => 'block',
                                            'operator' => '==',
                                            'value' => 'acf/panel',
                                            'order_no' => 0,
                                            'group_no' => 0,
                                    ),
                            ),
                    ),
            ));
        }
    }
}

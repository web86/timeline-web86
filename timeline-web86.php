<?php
/*
Plugin Name: Timeline Web86
Plugin URI: https://web86.ru/timeline-web86
Description: Simple adaptive timeline (road-map) plugin.
Version: 1.0
Author: Konstantin Shulyarenko
Author URI: https://web86.ru/
*/

function timeline_web86_init()
{

    /**
     * Metabox form template
     */
    function web86_meta_box_callback($post)
    {
        $value = get_post_meta($post->ID, 'web86_field_name', true);
        if (!is_array($value)) {
            $value = array();
        }
        ?>

        <div id="timeline-web86">
            <div class="wfieldset">
                <?php
                foreach ($value[0] as $key => $group) {
                    echo '<div class="repeatable-fieldset">
                    <label class="mainlabel"><span class="count">' . ($key + 1) . ')</span> Section name:</label>';

                    foreach ($group as $fieldkey => $input_value) {
                        if (!is_array($input_value)) {
                            $ifif = preg_replace('/\D/', '', $fieldkey);
                            $placeholder = ($ifif < 2) ? "Section name" : "Section color";
                            $myclass = ($ifif < 2) ? "text-input" : "color-input";
                            $typeinpit = ($ifif < 2) ? "text" : "color";
                            echo '<input type="' . $typeinpit . '" name="web86_field_name[0][' . ($key + 1) . '][' . $fieldkey . ']" value="' . $input_value . '" class="' . $myclass . '" placeholder="' . $placeholder . '">';
                        }
                    }
                    echo '<div class="subfields-container">';

                    foreach ($group as $fieldkey => $input_value) {

                        if (is_array($input_value)) {
                            foreach ($input_value as $subfield_key => $subfield_value) {
                                echo '<div class="subfield-container">';
                                foreach ($subfield_value as $subfield_value_key => $subfield_value_val) {
                                    echo '<textarea rows="4" name="web86_field_name[0][' . ($key + 1) . '][' . $fieldkey . '][' . $subfield_key . '][' . $subfield_value_key . ']" style="width:100%">' . $subfield_value_val . '</textarea>';
                                }
                                echo '<button class="remove-sub-field-row button-secondary">Remove step</button>
                            </div>';
                            }
                        }
                    }
                    echo '</div>
                    <div class="btns">
                    <a class="button add-subfield-row" href="#">Add new step</a>
                    <a class="button remove-row" href="#">Remove section</a>
                    </div></div>';
                }
                ?>
            </div>

            <?php
            // we use the editor to enter the text that will be at the very end
            $web86_editor_id = 'web86editor'; // unique ID of the editor
            $web86_settings = array(
                'textarea_name' => 'web86_field_name[1]',
                // field name
                'media_buttons' => false,
                // don't show media buttons
                'quicktags' => true,
                // show quick formatting buttons
                'tinymce' => array(
                    'toolbar1' => 'bold italic | formatselect | bullist numlist | alignleft aligncenter alignright | link unlink',
                    'toolbar2' => '',
                    'toolbar3' => '',
                    'toolbar4' => ''
                )
            );
            $editor_height = '150px'; // editor height
            $web86_settings['teeny'] = true; // show a simplified toolbar
            $web86_settings['editor_height'] = $editor_height; // set the height of the editor
            ?>

            <a class="button add-row" href="#">Add new section</a>
            <label style="margin-top:10px;display:block">Text at the end of Timeline:</label>

            <?php if (!empty($value[1])) {
                wp_editor($value[1], $web86_editor_id, $web86_settings);
            } else {
                wp_editor('', $web86_editor_id, $web86_settings);
            } ?>

            <div class="empty-row screen-reader-text">

                <label class="mainlabel"><span class="count"></span>) Section name:</label>
                <input type="text" name="field1" class="text-input" placeholder="Section name" />
                <input type="color" name="field2" class="my-color-picker-web86" />
                <div class="subfields-container"></div>
                <div class="btns">
                    <a class="button add-subfield-row" href="#">Add new step</a>
                    <a class="button remove-row" href="#">Remove section</a>
                </div>
            </div>

            <div class="repeatable-subfieldset empty-subfieldset screen-reader-text">
                <div class="subfield-container">
                    <div>
                        <label>Date:</label><br>
                        <textarea rows="4" name="mysubfield[0][%group_index%][sub_fields][%sub_group_index%][sub_field_1]"
                            placeholder="Date" style="width:100%" /></textarea>
                    </div>
                    <div>
                        <label>Title:</label><br>
                        <textarea rows="4" name="mysubfield[0][%group_index%][sub_fields][%sub_group_index%][sub_field_2]"
                            placeholder="Title" style="width:100%" /></textarea>
                    </div>
                    <div>
                        <label>Subtitle:</label><br>
                        <textarea rows="4" name="mysubfield[0][%group_index%][sub_fields][%sub_group_index%][sub_field_3]"
                            placeholder="Subtitle" style="width:100%" /></textarea>
                    </div>
                    <div>
                        <label>Description:</label><br>
                        <textarea rows="4" name="mysubfield[0][%group_index%][sub_fields][%sub_group_index%][sub_field_4]"
                            placeholder="Description" style="width:100%" /></textarea>
                    </div>
                    <button class="remove-sub-field-row button-secondary" style="width:150px">Remove step</button>
                </div>
            </div>
        </div>
        <?php

    }
    /**
     * Adding Metabox to the page or post 
     */
    function web86_add_meta_box()
    {
        $allowed_page_ids = get_option('timeline_web86_pages', array());
        $allowed_post_ids = get_option('timeline_web86_posts', array());

        if (in_array(get_the_ID(), $allowed_post_ids) || in_array(get_the_ID(), $allowed_page_ids)) {
            add_meta_box(
                'web86_meta_box',
                // Unique identifier of the meta block
                'Web86 Timeline',
                // Meta block header
                'web86_meta_box_callback',
                // Callback function for displaying meta block fields
                array('page', 'post'),
                // On which screen to show the meta block
                'normal',
                // Location of the meta block (normal, side or advanced)
                'high' // Meta block priority (high, core, default or low)
            );
        }
    }
    add_action('add_meta_boxes', 'web86_add_meta_box'); // Adding a meta block

    function web86_save_meta_box_data($post_id)
    {
        // Checking access rights.
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Processing the values from the "web86_field_name" field.

        // we only allow these tags in the fields
        $allowed_tags = array(
            'strong' => array(),
            'span' => array(),
            'h3' => array(),
            'h4' => array(),
            'br' => array(),
            'p' => array(),
            'ul' => array(),
            'ol' => array(),
            'li' => array(),
            'a' => array(
                'href' => array(),
                'title' => array()
            )
        );

        $web86_field_name = array();
        foreach ($_POST['web86_field_name'][0] as $group) {
            $clean_group = array();
            if (isset($group['field1'])) {
                $clean_group['field1'] = sanitize_text_field($group['field1']);
            }
            if (isset($group['field2'])) {
                $clean_group['field2'] = sanitize_text_field($group['field2']);
            }

            foreach ($group as $key => $value) {
                if (is_array($value)) {
                    $clean_subfields = array();
                    foreach ($value as $subfield_key => $subfield_value) {
                        if (is_array($subfield_value)) {
                            $clean_subsubfields = array();
                            foreach ($subfield_value as $subsubfield_key => $subsubfield_value) {

                                $clean_subsubfields[$subsubfield_key] = wp_kses($subsubfield_value, $allowed_tags);
                            }
                            $clean_subfields[$subfield_key] = $clean_subsubfields;
                        } else {

                            $clean_subfields[$subfield_key] = wp_kses($subfield_value, $allowed_tags);
                        }
                    }
                    $clean_group[$key] = $clean_subfields;
                } else {

                    $clean_group[$key] = wp_kses($value, $allowed_tags);
                }
            }
            $web86_field_name[0][] = $clean_group;
        }
        $web86_field_name[1] = wp_kses($_POST['web86_field_name'][1], $allowed_tags);


        // Deleting the fields that were deleted in the form.
        $existing_meta = get_post_meta($post_id, 'web86_field_name', true);
        if ($existing_meta) {
            foreach ($existing_meta[0] as $existing_group) {
                if (!in_array($existing_group, $web86_field_name[0])) {
                    delete_post_meta($post_id, 'web86_field_name', $existing_group);
                }
            }
        }

        // We save the values in the metadata.
        update_post_meta($post_id, 'web86_field_name', $web86_field_name);
    }

    add_action('save_post', 'web86_save_meta_box_data');

    // register the fields for the settings page in the menu
    register_setting('timeline_web86_plugin_settings', 'timeline_web86_pages');
    register_setting('timeline_web86_plugin_settings', 'timeline_web86_posts');
}
add_action('admin_init', 'timeline_web86_init');


// Include the script
function web86_plugin_enqueue_scripts($hook)
{
    if ('post.php' == $hook || 'post-new.php' == $hook || 'page.php' == $hook || 'page-new.php' == $hook) {
        // adding our own scripts and styles
        wp_enqueue_script(
            'web86-plugin-script',
            plugins_url('/web86-timeline-admin.js', __FILE__),
            array('jquery'),
            '1.0.0',
            true
        );
        wp_enqueue_style('web86-plugin-style', plugins_url('web86-timeline-admin.css', __FILE__));

    }
}
add_action('admin_enqueue_scripts', 'web86_plugin_enqueue_scripts');


// Create a shortcode
function web86_plugin_register_shortcodes()
{

    // inqlude a file with a shortcode
    require_once(plugin_dir_path(__FILE__) . 'shortcode.php');

    add_shortcode('timeline_web86', 'web86_shortcode_function');
}
add_action('init', 'web86_plugin_register_shortcodes');

// Adding setting page in menu
add_action('admin_menu', 'web86_timeline_settings');
function web86_timeline_settings()
{

    require_once(plugin_dir_path(__FILE__) . 'settings_page.php');

    add_options_page(
        'Setting page for Timeline Web86',
        // page title
        'Timeline',
        // name of the menu item
        'manage_options',
        // access level
        'timeline_web86_plugin_settings',
        // unique page ID
        'timeline_web86_settings_page' // the function of displaying the settings page
    );
}

// Deleting data from the database after deactivating the plugin
function web86_timeline_plugin_deactivation()
{
    delete_option('timeline_web86_pages');
    delete_option('timeline_web86_posts');
}
register_deactivation_hook(__FILE__, 'web86_timeline_plugin_deactivation');
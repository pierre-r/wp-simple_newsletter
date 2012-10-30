<?php

add_action('widgets_init', 'wpsn_widget_init');

function wpsn_widget_init() {
    register_widget('wpsn_widget');
}

$wpsn_subscribe_form_default_atts = array(
    'title' => 'Newsletter',
    'show_on_front_page' => 1,
    'do_not_widget' => 0,
    'form_id' => 'simple_newsletter',
    'form_classes' => '',
    'submit_value' => __('Subscribe', 'wpsn'),
    'javascript_alert' => FALSE,
    'error_classes' => 'alert alert-error',
    'success_classes' => 'alert alert-success',
);

function wpsn_subscribe_form($atts = array()) {
    global $wpsn_form_error;
    $atts = wp_parse_args($atts, $GLOBALS['$wpsn_subscribe_form_default_atts']);
    extract($atts);
    $output = "";
    $output_error = "";
    if (isset($_POST['action']) && $_POST['action'] == 'simple_newsletter_register') {
        $wpsn_display_message = ($wpsn_form_error) ? $wpsn_form_error : __('Thank you for your subscription :)', 'wpsn');
        // Manage success/errors and how to ouput them
        if ($atts['javascript_alert']) {
            $output_error .= '<script>
                            var tid = setInterval( function () {
                            if ( document.readyState !== \'complete\' ) return;
                            clearInterval( tid );       
                                alert("' . $wpsn_display_message . '");
                            }, 100 );
                    </script>';
        } else {
            $className = ($wpsn_form_error) ? $atts['error_classes'] : $atts['success_classes'];
            $output_error .= '<div class="' . $className . '">' . $wpsn_display_message . '</div>';
        }
    }
    $output .= '<form class="' . $form_classes . '" id="' . $form_id . '" action="" method="post">
        ' . $output_error . '
                    <input type="text" name="simple_newsletter_email" placeholder="' . __('Newsletter Subscribe', 'wpsn') . '">
                    <input type="hidden" name="action" value="simple_newsletter_register" />
                    <button type="submit" class="submit">' . $submit_value . '</button>
                </form>';
    return $output;
}

class wpsn_widget extends WP_Widget {

    public function __construct() {
        $widget_options = array(
            'classname' => 'wpsn-widget',
            'description' => __('Widget to show a subscribe form in your sidebar.', 'wpsn'),
        );
        parent::__construct('wpsn-widget', __('Simple Newsletter', 'wpsn'), $widget_options);
    }

    public function widget($args, $instance) {
        if ($instance['show_on_front_page'] || !is_front_page()) {
            extract($args);
            $output = "";
            if (!$instance['do_not_widget']) {
                $output .= $before_widget;
            }
            if (!empty($instance['title'])) {
                $output .= $before_title . $instance['title'] . $after_title;
            }
            $output .= wpsn_subscribe_form($instance);
            if (!$instance['do_not_widget']) {
                $output .= $after_widget;
            }
            echo $output;
        }
    }

    public function form($instance) {
        $instance = wp_parse_args($instance, $GLOBALS['$wpsn_subscribe_form_default_atts']);
        $show_on_front_page = ($instance['show_on_front_page']) ? ' checked="checked"' : '';
        $do_not_widget = ($instance['do_not_widget']) ? ' checked="checked"' : '';
        $javascript_alert = ($instance['javascript_alert']) ? ' checked="checked"' : '';

        echo '<p><label for="' . $this->get_field_id('title') . '">' . __('Title') . '</label> <input type="text" name="' . $this->get_field_name('title') . '" value="' . $instance['title'] . '" id="' . $this->get_field_id('title') . '" /></p>';
        echo '<p><label for="' . $this->get_field_id('form_id') . '">' . __('Form ID', 'wpsn') . '</label> <input type="text" name="' . $this->get_field_name('form_id') . '" value="' . $instance['form_id'] . '" id="' . $this->get_field_id('form_id') . '" /></p>';
        echo '<p><label for="' . $this->get_field_id('form_classes') . '">' . __('Form classes', 'wpsn') . '</label> <input type="text" name="' . $this->get_field_name('form_classes') . '" value="' . $instance['form_classes'] . '" id="' . $this->get_field_id('form_classes') . '" /></p>';
        echo '<p><label for="' . $this->get_field_id('error_classes') . '">' . __('Error class', 'wpsn') . '</label> <input type="text" name="' . $this->get_field_name('error_classes') . '" value="' . $instance['error_classes'] . '" id="' . $this->get_field_id('error_classes') . '" /></p>';
        echo '<p><label for="' . $this->get_field_id('success_classes') . '">' . __('Success class', 'wpsn') . '</label> <input type="text" name="' . $this->get_field_name('success_classes') . '" value="' . $instance['success_classes'] . '" id="' . $this->get_field_id('success_classes') . '" /></p>';
        echo '<p><label for="' . $this->get_field_id('submit_value') . '">' . __('Submit button', 'wpsn') . '</label> <input type="text" name="' . $this->get_field_name('submit_value') . '" value="' . $instance['submit_value'] . '" id="' . $this->get_field_id('submit_value') . '" /></p>';
        echo '<p><input type="checkbox" name="' . $this->get_field_name('show_on_front_page') . '" value="1"' . $show_on_front_page . ' id="' . $this->get_field_id('show_on_front_page') . '" /> <label for="' . $this->get_field_id('show_on_front_page') . '">' . __('Show on front page', 'wpsn') . '</label></p>';
        echo '<p><input type="checkbox" name="' . $this->get_field_name('do_not_widget') . '" value="1"' . $do_not_widget . ' id="' . $this->get_field_id('do_not_widget') . '" /> <label for="' . $this->get_field_id('do_not_widget') . '">' . __('Do not consider as widget', 'wpsn') . '</label></p>';
        echo '<p><input type="checkbox" name="' . $this->get_field_name('javascript_alert') . '" value="1"' . $javascript_alert . ' id="' . $this->get_field_id('javascript_alert') . '" /> <label for="' . $this->get_field_id('javascript_alert') . '">' . __('Show error and success in JS alert()', 'wpsn') . '</label></p>';
    }

    public function update($new_instance, $old_instance) {
        if (!isset($new_instance['show_on_front_page'])) {
            $new_instance['show_on_front_page'] = 0;
        }
        if (!isset($new_instance['do_not_widget'])) {
            $new_instance['do_not_widget'] = 0;
        }
        if (!isset($new_instance['javascript_alert'])) {
            $new_instance['javascript_alert'] = 0;
        }
        return $new_instance;
    }

}
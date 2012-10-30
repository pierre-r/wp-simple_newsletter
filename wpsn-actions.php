<?php

add_action('init', 'wpsn_plugin_init');
add_action('plugins_loaded', 'wpsn_plugin_loaded');

function wpsn_plugin_init() {
    global $wpdb, $wpsn_form_error;
    if (isset($_POST['simple_newsletter_email'])) {
        $_POST['simple_newsletter_email'] = trim($_POST['simple_newsletter_email']);
    }
    if (isset($_POST['action']) && $_POST['action'] == 'simple_newsletter_register') {
        if (is_email($_POST['simple_newsletter_email'])) {
            $sql = "INSERT INTO " . $wpdb->prefix . "simple_newsletter (email, lang) VALUES (%s, %s)
                    ON DUPLICATE KEY UPDATE lang = VALUES(lang), unsubscribed = 0, unsubscribe_date = NULL";
            $sqlData = array($_POST['simple_newsletter_email'], get_locale());
            $wpdb->query($wpdb->prepare($sql, $sqlData));
        } else {
            $wpsn_form_error = __('Invalid email address', 'wpsn');
        }
    } else if (isset($_GET['sn_unsubscribe']) && is_email($_GET['sn_unsubscribe'])) {
        $sql = "UPDATE " . $wpdb->prefix . "simple_newsletter SET unsubscribed = 1, unsubscribe_date = %s WHERE email = %s";
        $sqlData = array(date('Y-m-d H:i:s'), $_GET['sn_unsubscribe']);
        $wpdb->query($wpdb->prepare($sql, $sqlData));
    }
}

function wpsn_plugin_loaded() {
    // Make plugin available for translation
    load_plugin_textdomain('wpsn', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
<?php

/*
  Plugin Name: Simple Newsletter
  Description: Easy management of newsletter subscription
  Version: 1.0
  Author: Greenpig
  Author URI: http://www.greenpig.be
 */

// Create database table on activation
register_activation_hook(__FILE__, 'simple_newsletter_install');

function simple_newsletter_install() {
    global $wpdb;

    $sql = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "simple_newsletter` (
                `ID` int(11) NOT NULL AUTO_INCREMENT,
                `email` varchar(255) NOT NULL,
                `lang` varchar(5) NOT NULL,
                `subscribe_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `unsubscribed` tinyint(1) NOT NULL DEFAULT '0',
                `unsubscribe_date` datetime DEFAULT NULL,
                PRIMARY KEY (`ID`),
                UNIQUE KEY `email` (`email`)
          );";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Includes
require_once(dirname(__FILE__) . '/wpsn-functions.php');
require_once(dirname(__FILE__) . '/wpsn-install.php');
require_once(dirname(__FILE__) . '/wpsn-actions.php');
require_once(dirname(__FILE__) . '/wpsn-widget.php');
require_once(dirname(__FILE__) . '/wpsn-admin.php');
?>
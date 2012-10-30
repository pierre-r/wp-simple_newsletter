<?php

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

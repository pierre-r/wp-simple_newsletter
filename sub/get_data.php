<?php

global $wpdb;
$users_per_page = 10;
// Sort & Filters
if (!isset($_GET['sm'])) {
    $_GET['sm'] = 'dashboard';
}
if (!isset($_GET['sort_by']) || empty($_GET['sort_by'])) {
    $_GET['sort_by'] = 'email';
}
if (!isset($_GET['sort_order']) || empty($_GET['sort_order'])) {
    $_GET['sort_order'] = 'DESC';
}
if ((!isset($_GET['paged']) || empty($_GET['paged'])) && (!isset($_GET['show_all']) || $_GET['show_all'] != 1)) {
    $_GET['paged'] = 1;
}
// Get subscribers
$order_by = $wpdb->escape($_GET['sort_by']) . " " . $wpdb->escape($_GET['sort_order']);
$limit = (!isset($_GET['show_all']) || $_GET['show_all'] != 1) ? " LIMIT " . $users_per_page : "";
if (!empty($_GET['paged']) && $_GET['paged'] > 1) {
    $limit .= " OFFSET " . ($_GET['paged'] - 1) * $users_per_page;
}
$unsubscribed = ($_GET['sm'] == 'unsubscribers') ? 1 : 0;
$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . $wpdb->prefix . "simple_newsletter WHERE unsubscribed = " . $unsubscribed . " ORDER BY " . $order_by . $limit;
$user_list = $wpdb->get_results($sql);
$total_users = $wpdb->get_row('SELECT FOUND_ROWS() AS total')->total;
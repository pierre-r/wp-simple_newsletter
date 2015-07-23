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
// SQL base statement
$sqlData = array();
$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . $wpdb->prefix . "simple_newsletter WHERE unsubscribed = %d ORDER BY %s %s %s %s";
$sqlData[] = ($_GET['sm'] == 'unsubscribers') ? 1 : 0;
// SQL Order By
$sqlData[] = $_GET['sort_by'];
$sqlData[] = $_GET['sort_order'];
// SQL Limit
$sqlData[] = (!isset($_GET['show_all']) || $_GET['show_all'] != 1) ? " LIMIT " . $users_per_page : "";
// SQL Offset
$sqlData[] = (!empty($_GET['paged']) && $_GET['paged'] > 1) ? " OFFSET " . ($_GET['paged'] - 1) * $users_per_page : "";
// SQL QUERY
$user_list = $wpdb->get_results($wpdb->prepare($sql, $sqlData));
$total_users = $wpdb->get_row('SELECT FOUND_ROWS() AS total')->total;

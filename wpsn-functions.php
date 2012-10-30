<?php

// DATAS
$wpsn_export_formats = array('xml', 'csv', 'txt');
$wpsn_upload_folder_name = "simple_newsletter";
$wpsn_upload_dir = wp_upload_dir();
$wpsn_upload_url = $wpsn_upload_dir['baseurl'] . DIRECTORY_SEPARATOR . 'simple_newsletter';
$wpsn_upload_dir = $wpsn_upload_dir['basedir'] . '/' . $wpsn_upload_folder_name;

// FUNCTIONS

function wpsn_link_sort_by($field) {
    $url = admin_url('admin.php?page=simple-newsletter');
    foreach ($_GET AS $k => $v) {
        if ($k != 'page' && $k != $field && $k != 'sort_by' && $k != 'sort_order') {
            $url .= "&" . $k . "=" . $v;
        }
    }
    $order = (isset($_GET['sort_by']) && $_GET['sort_by'] == $field && $_GET['sort_order'] == 'DESC') ? 'ASC' : 'DESC';
    $url .= "&sort_by=" . $field . "&sort_order=" . $order;
    return $url;
}

function wpsn_admin_navigation($nb_showed, $total_items, $items_per_page) {
    $max_pages = ceil($total_items / $items_per_page);
    $output = '<div class="tablenav">';
    $output .= '<div style="width: 30%; float: left;"><strong>';
    if ($_GET['sm'] == 'unsubscribers') {
        $output .= __('Total unsubscribers:', 'wpsn');
    } else {
        $output .= __('Total subscribers:', 'wpsn');
    }
    $output .= '</strong> ' . $total_items . '</div>';
    $output .= '<div style="width: 50%; float: right;">';
    if (isset($_GET['show_all']) && $_GET['show_all'] && $total_items > $items_per_page) {
        $output .= '<a style="float:right" href="' . admin_url('admin.php?page=simple-newsletter') . '">' . sprintf(__('Show %d users per page', 'wpsn'), $nb_showed) . '</a>';
    } else {
        // pagination  init
        $pagination_args = array();
        foreach ($_GET AS $k => $v) {
            if (!preg_match('/page/i', $k)) {
                $pagination_args[$k] = $v;
            }
        }
        $page_links = paginate_links(array(
            'base' => add_query_arg('paged', '%#%'),
            'format' => '',
            'show_all' => FALSE,
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
            'total' => $max_pages,
            'current' => $_GET['paged'],
            'add_args' => $pagination_args
                ));
        // Pagination display
        $output .= '<div class="tablenav-pages">';
        if ($total_items > $items_per_page) {
            $output .= '<a style="font-weight:normal" href="' . admin_url('admin.php?page=simple-newsletter&show_all=1') . '">' . __('show all', 'wpsn') . '</a>';
        }
        $output .= sprintf(' <span class="displaying-num">' . __('Displaying %s&#8211;%s of %s', 'wpsn') . '</span>%s', number_format_i18n(( $_GET['paged'] - 1 ) * $items_per_page + 1), number_format_i18n(min($_GET['paged'] * $items_per_page, $total_items)), number_format_i18n($total_items), $page_links);
        $output .= '</div>';
    }
    $output .= '</div>';
    $output .= '</div>';
    return $output;
}

function wpsn_get_base_filename() {
    $basename_prefix = ($_GET['sm'] == 'unsubscribers') ? 'unsubscribers' : 'subscribers';
    return sanitize_title($basename_prefix . '-' . get_bloginfo('name'));
}

function wpsn_data_format($data, $format) {
    $output = "";
    $output .= wpsn_create_data_headers($format);
    $output .= wpsn_feed_data($data, $format);
    return $output;
}

function wpsn_create_data_headers($format) {
    $headers = "";
    switch ($format) {
        case 'xml':
            $headers = '<?xml version="1.0"?>' . PHP_EOL;
            break;
        case 'csv':
            $columns = array('ID', 'email', 'lang', 'subscribe_date', 'unsubscribed', 'unsubscribe_date');
            foreach ($columns AS $v) {
                $headers .= $v . ',';
            }
            $headers = trim($headers, ',') . PHP_EOL;
            break;
    }
    return $headers;
}

function wpsn_feed_data($data, $format) {
    $ret = ($format == 'xml') ? "<root>" . PHP_EOL : "";
    foreach ($data AS $k => $v) {
        if (!empty($v)) {
            switch ($format) {
                case 'xml':
                    $ret .= "\t<subscriber>" . PHP_EOL;
                    foreach ($v AS $kk => $vv) {
                        $ret .= "\t\t<" . $kk . "><![CDATA[" . $vv . "]]></" . $kk . ">" . PHP_EOL;
                    }
                    $ret .= "\t</subscriber>" . PHP_EOL;
                    break;
                case 'csv':
                    foreach ($v AS $vv) {
                        if (empty($vv)) {
                            $vv = "______________";
                        }
                        $ret .= $vv . ",";
                    }
                    $ret = rtrim($ret, ',') . PHP_EOL;
                    break;
                case 'txt':
                    $ret .= $v->email . PHP_EOL;
                    break;
            }
        }
    }
    $ret .= ($format == 'xml') ? "</root>" : "";
    return $ret;
}
<?php

//Our class extends the WP_List_Table class, so we need to make sure that it's there
if (!class_exists('WP_List_Table')) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Subscribers_List_Table extends WP_List_Table {

    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct() {
        parent::__construct(array(
            'singular' => 'wp_list_subscriber', //Singular label
            'plural' => 'wp_list_subscribers', //plural label, also this well be one of the table css class
            'ajax' => false //We won't support Ajax for this table
        ));
    }

    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns() {
        $columns = array(
            'id' => __('ID'),
            'email' => __('Email'),
            'last_name' => __('Nom', 'wpsn'),
            'first_name' => __('Prénom', 'wpsn'),
            'entreprise_name' => __('Entreprise', 'wpsn'),
            'lang' => __('Language', 'wpsn'),
            'subscribe_date' => __('Date Subscription', 'wpsn'),
        );
        if (isset($_GET['sm']) && $_GET['sm'] === 'unsubscribers') {
            $columns['unsubscribe_date'] = __('Date Unsubscribe', 'wpsn');
        }
        return $columns;
    }

    /**
     * Decide which columns to activate the sorting functionality on
     * @return array $sortable, the array of columns that can be sorted by the user
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'ID' => array('ID', false),
            'email' => array('email', false),
            'last_name' => array('last_name', false),
            'first_name' => array('first_name', false),
            'entreprise_name' => array('entreprise_name', false),
            'lang' => array('lang', false),
            'subscribe_date' => array('subscribe_date', false),
            'unsubscribe_date' => array('unsubscribe_date', false),
        );
        return $sortable_columns;
    }

    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'id':
            case 'email':
            case 'last_name':
            case 'first_name':
            case 'entreprise_name':
            case 'lang':
            case 'subscribe_date':
            case 'unsubscribe_date':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb, $_wp_column_headers;
        $screen = get_current_screen();

        /* -- Preparing your query -- */
        $query = "SELECT * FROM " . $wpdb->prefix . "simple_newsletter";

        /* -- WHEREing parameters -- */
        $query .= " WHERE unsubscribed = " . (int) (isset($_GET['sm']) && $_GET['sm'] === 'unsubscribers');

        /* -- Ordering parameters -- */
        //Parameters that are going to be used to order the result
        $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ASC';
        $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : '';
        if (!empty($orderby) & !empty($order)) {
            $query.=' ORDER BY ' . $orderby . ' ' . $order;
        }
        /* -- Pagination parameters -- */
        //Number of elements in your table?
        $totalitems = $wpdb->query($query); //return the total number of affected rows
        //How many to display per page?
        $perpage = 15;
        //Which page is this?
        $paged = !empty($_GET["paged"]) ? mysql_real_escape_string($_GET["paged"]) : '';
        //Page Number
        if (empty($paged) || !is_numeric($paged) || $paged <= 0) {
            $paged = 1;
        }
        //How many pages do we have in total?
        $totalpages = ceil($totalitems / $perpage);
        //adjust the query to take pagination into account
        if (!empty($paged) && !empty($perpage)) {
            $offset = ($paged - 1) * $perpage;
            $query.=' LIMIT ' . (int) $offset . ',' . (int) $perpage;
        }

        /* -- Register the pagination -- */
        $this->set_pagination_args(array(
            "total_items" => $totalitems,
            "total_pages" => $totalpages,
            "per_page" => $perpage,
        ));
        //The pagination links are automatically built according to those parameters

        /* -- Register the Columns -- */
        $columns = $this->get_columns();
        $_wp_column_headers[$screen->id] = $columns;

        /* -- Fetch the items -- */
        $columns = $this->get_columns();
        $hidden = array('id');
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $wpdb->get_results($query);
    }

    /**
     * Display the rows of records in the table
     * @return string, echo the markup of the rows
     */
    function display_rows() {

        //Get the records registered in the prepare_items method
        $records = $this->items;

        //Get the columns registered in the get_columns and get_sortable_columns methods
        $columns = $this->get_columns();
        $hidden = array('id');
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        //Loop for each record
        if (!empty($records)) {
            foreach ($records as $rec) {

                //Open the line
                echo '<tr id="record_' . $rec->ID . '">';
                foreach ($columns as $column_name => $column_display_name) {

                    //Style attributes for each col
                    $class = "class='$column_name column-$column_name'";
                    $style = "";
                    if (in_array($column_name, $hidden))
                        $style = ' style="display:none;"';
                    $attributes = $class . $style;

                    //Display the cell
                    switch ($column_name) {
                        case "email":
                            echo '<td ' . $attributes . ' style="width:50%;"><strong>' . $rec->$column_name . '</strong></td>';
                            break;
                        default:
                            echo '<td ' . $attributes . '>' . $rec->$column_name . '</td>';
                            break;
                    }
                }

                //Close the line
                echo'</tr>';
            }
        }
    }

}
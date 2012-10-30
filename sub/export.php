<form method="get" action="">
    <h3><?php _e('Export Options', 'wpsn'); ?></h3>
    <input type="hidden" name="page" value="<?php echo $_GET['page']; ?>" />
    <input type="hidden" name="sm" value="<?php echo $_GET['sm']; ?>" />
    <input type="hidden" name="action" value="export_files" />
    <label for="user_type"><?php _e('Users type', 'wpsn'); ?></label>
    <select name="user_type" id="user_type">
        <option value="subs"<?php if (!isset($_GET['user_type']) || $_GET['user_type'] === "subs") echo ' selected="selected"'; ?>><?php _e('Subscribers', 'wpsn'); ?></option>
        <option value="unsubs"<?php if (isset($_GET['user_type']) && $_GET['user_type'] === "unsubs") echo ' selected="selected"'; ?>><?php _e('Unsubscribers', 'wpsn'); ?></option>
        <option value="all"<?php if (isset($_GET['user_type']) && $_GET['user_type'] === "all") echo ' selected="selected"'; ?>><?php _e('All', 'wpsn'); ?></option>
    </select>

    <label for="data_format"><?php _e('Data format', 'wpsn'); ?></label>
    <select name="data_format" id="data_format">
        <?php
        foreach ($GLOBALS['wpsn_export_formats'] AS $v) {
            $selected = (isset($_GET['data_format']) && $_GET['data_format'] === $v) ? ' selected="selected"' : '';
            echo '<option value="' . $v . '"' . $selected . '>' . strtoupper($v) . '</option>';
        }
        ?>
    </select>

    <!--
    <label for="output"><?php _e('Output'); ?></label>
    <select name="output" id="output">
        <option value="show"<?php if (isset($_GET['output']) && $_GET['output'] === "show") echo ' selected="selected"'; ?>><?php _e('Show results', 'wpsn'); ?></option>
        <option value="file"<?php if (isset($_GET['file']) && $_GET['file'] === "show") echo ' selected="selected"'; ?>><?php _e('Save in a file', 'wpsn'); ?></option>
    </select>
    -->


    <button type="submit" class="button-primary"><?php _e('Export', 'wpsn'); ?></button>
</form>
<?php
if (isset($_GET['action']) && $_GET['action'] === "export_files") {
    global $wpdb;
    $sqlWhere = "";
    if ($_GET['user_type'] !== 'all') {
        //$sqlWhere = ($_GET['user_type'] === 'unsubs') ? " WHERE unsubscribed = '0' " : "";
        $unsubscribed = (int) ($_GET['user_type'] === 'unsubs');
        $sqlWhere = " WHERE unsubscribed = '" . $unsubscribed . "'";
    }
    $sql = "SELECT * FROM " . $wpdb->prefix . "simple_newsletter" . $sqlWhere;
    $sql_data = $wpdb->get_results($sql);
    $formated_data = wpsn_data_format($sql_data, $_GET['data_format']);
    echo '<h3>' . __('Results', 'wpsn') . '</h3><textarea style="width:100%;" rows="30">' . $formated_data . "</textarea>";
}
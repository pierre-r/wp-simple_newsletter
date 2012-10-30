<?php
require_once('subscribers_list_table.php');
//Prepare Table of elements
$wpsn_list_table = new Subscribers_List_Table;
$wpsn_list_table->prepare_items();
?>
<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
<form id="movies-filter" method="get">
    <!-- For plugins, we also need to ensure that the form posts back to our current page -->
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
    <input type="hidden" name="sm" value="<?php echo $_REQUEST['sm'] ?>" />
    <!-- Now we can render the completed list table -->
    <?php $wpsn_list_table->display() ?>
</form>
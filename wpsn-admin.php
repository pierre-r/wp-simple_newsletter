<?php
add_action('admin_menu', 'simple_newsletter_main_menu');

function simple_newsletter_main_menu() {
    add_menu_page(__('Manage newsletter subscriptions', 'wpsn'), 'S-Newsletter', 'manage_options', 'simple-newsletter', 'simple_newsletter_admin_page');
}

function simple_newsletter_admin_page() {
    ?>
    <br clear="all" />
    <div class="wrap">
        <h2><?php _e('Manage newsletter subscriptions', 'wpsn'); ?></h2>
        <a class="nav-tab <?php if (!isset($_GET['sm']) || (isset($_GET['sm']) && $_GET['sm'] == 'dashboard')): ?> nav-tab-active<?php endif; ?>" 
           href="admin.php?page=<?php echo $_GET['page']; ?>&sm=dashboard"><?php _e('Subscribers list', 'wpsn') ?></a>
        <a class="nav-tab<?php if (isset($_GET['sm']) && $_GET['sm'] == 'unsubscribers'): ?> nav-tab-active<?php endif; ?>" 
           href="admin.php?page=<?php echo $_GET['page']; ?>&sm=unsubscribers"><?php _e('Unsubscribers list', 'wpsn') ?></a>
        <a class="nav-tab<?php if (isset($_GET['sm']) && $_GET['sm'] == 'export'): ?> nav-tab-active<?php endif; ?>" 
           href="admin.php?page=<?php echo $_GET['page']; ?>&sm=export"><?php _e('Export', 'wpsn') ?></a>
        <a class="nav-tab<?php if (isset($_GET['sm']) && $_GET['sm'] == 'documentation'): ?> nav-tab-active<?php endif; ?>" 
           href="admin.php?page=<?php echo $_GET['page']; ?>&sm=documentation"><?php _e('Documentation', 'wpsn') ?></a>

        <div class="icl_tm_wrap">
            <?php
            if (!isset($_GET['sm']) || empty($_GET['sm'])) {
                $_GET['sm'] = 'dashboard';
            }
            switch ($_GET['sm']) {
                case 'export':
                    require_once dirname(__FILE__) . '/sub/export.php';
                    break;
                case 'documentation':
                    require_once dirname(__FILE__) . '/sub/documentation.php';
                    break;
                default:
                    require_once(dirname(__FILE__) . '/sub/get_data.php');
                    require_once dirname(__FILE__) . '/sub/dashboard.php';
                    break;
            }
            ?>
        </div>
    </div>
    <?php
}

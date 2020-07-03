<?php
/*  Plugin deactivation Hook  */
function ngb_woocommerce_deactivation()
{
    //die('de activation hook');
}
register_deactivation_hook(__FILE__, 'ngb_woocommerce_deactivation');

/* Check transient, if available display notice */
add_action('admin_notices', 'ngb_fx_admin_notice_woocommerce');

function ngb_fx_admin_notice_woocommerce()
{
    if (get_transient('fx-admin-notice-woocommerce')) {
?>
        <div class="error notice is-dismissible">
            <p>We Recommend to install Woo Commerce Plugin for edit default Woo Commerce Email Templates.</p>
        </div>
    <?php
        /* Delete transient, only display this notice once. */
        delete_transient('fx-admin-notice-woocommerce');
    }
}



/* Create a Admin Menu in Dashboard */
add_action('admin_menu', 'ngb_woocommerce_plugin_custom_menu');

function ngb_woocommerce_plugin_custom_menu()
{
    add_menu_page('Woocommerce Templates', 'NGB', 'manage_options', 'ngb-templates', null, 'dashicons-email-alt');
    add_submenu_page('ngb-templates', 'NGB Templates Manager', 'Templates', 'manage_options', 'ngb-templates', 'ngb_woocommerce_menu_output');
    add_submenu_page('ngb-templates', 'NGB Woocommerce Templates', 'Woocommerce', 'manage_options', 'ngb-woocommerce', 'ngb_woocommerce_menu_output');
    add_submenu_page('ngb-templates', 'NGB Widgets Manager', 'Widgets', 'manage_options', 'ngb-widgets', 'ngb_woocommerce_menu_output');
    add_submenu_page('ngb-templates', 'NGB Settings', 'Settings', 'manage_options', 'ngb-settings', 'ngb_woocommerce_menu_output');
}

function ngb_woocommerce_menu_output()
{
    ?>
    <script>
        window.NGB = {
            host: '<?= get_rest_url(null, 'ngbwoocommerce-plugin/v1') ?>',
            app_base_href: '<?= plugins_url('js/ngb/', __FILE__) ?>',
            current_slug: '<?= sanitize_key($_GET['page']) ?>'
        }
    </script>
    <app-root></app-root>
<?php
}

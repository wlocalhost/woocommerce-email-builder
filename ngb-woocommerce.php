<?php
/*
Plugin Name: NGB WooCommerce Email Builder
Plugin URI: https://wlocalhost.org
Description: NGB WooCommerce Plugin to customize WooCommerce Email Templates
Author: Wlocalhost
Version: 1.0
Author URI: https://wlocalhost.org
*/

define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));
include(MY_PLUGIN_PATH . 'inc/ngb-functions.php');
include(MY_PLUGIN_PATH . 'inc/email-api-functions.php');

/* Activation hook function */
function ngb_woocommerce_activation()
{
    global $wpdb;
    $db_table_name = $wpdb->prefix . 'ngb_woocommerce_emails';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $db_table_name (
        id int(11) NOT NULL auto_increment,
        template_name varchar(255) NOT NULL,
        template_content longtext NULL,
        email_object longtext NULL,
        template_type enum('woocommerce_email', 'contact_form') NULL,
        created_at DATETIME NULL,
        updated_at DATETIME NULL,
        created_by int(11) NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    /* Check Woo Commerce Plugin is Activated or Not */
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
        set_transient('fx-admin-notice-woocommerce', true, 5);
    }
}
register_activation_hook(__FILE__, 'ngb_woocommerce_activation');

/* Add script and css in plugin */
/**
 * TODO: Change version '1.1' to be automatically the current plugin version
 */
function ngb_woocommerce_add_files()
{
    $plugin_data = get_plugin_data(__FILE__);
    $plugin_version = $plugin_data['Version'];

    wp_register_style('ngb_styles', plugins_url('js/ngb/styles.css', __FILE__));
    wp_enqueue_style('ngb_styles');

    wp_enqueue_style('wpb-google-fonts', 'https://fonts.googleapis.com/icon?family=Material+Icons', false);

    wp_register_script('ngb_main', plugins_url('js/ngb/main.js', __FILE__), '', $plugin_version, true);
    wp_enqueue_script('ngb_main');
    wp_script_add_data('ngb_main', 'async', true);

    wp_register_script('ngb_polyfills', plugins_url('js/ngb/polyfills.js', __FILE__), '', $plugin_version, true);
    wp_enqueue_script('ngb_polyfills');
    wp_script_add_data('ngb_polyfills', 'async', true);

    wp_register_script('ngb_runtime', plugins_url('js/ngb/runtime.js', __FILE__), '', $plugin_version, true);
    wp_enqueue_script('ngb_runtime');
    wp_script_add_data('ngb_runtime', 'async', true);
}
add_action('admin_init', 'ngb_woocommerce_add_files');

/* Add action to display setting button */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'ngb_woo_add_plugin_page_settings_link');
function ngb_woo_add_plugin_page_settings_link($links)
{
    $links[] = '<a href="' . admin_url('?page=ngb-plugin') . '">' . __('Settings') . '</a>';
    return $links;
}

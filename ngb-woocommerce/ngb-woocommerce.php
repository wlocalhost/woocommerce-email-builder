<?php
/*
Plugin Name: NGB Woo Commerce
Plugin URI: https://www.mobilyte.com
Description: NGB Woo Commerce Plugin to customize Woo commerce Email Templates
Author: Ion Prodon
Version: 1.0
Author URI: https://www.mobilyte.com
*/

define( 'MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
include( MY_PLUGIN_PATH . 'inc/ngb-functions.php');
include( MY_PLUGIN_PATH . 'inc/email-api-functions.php');

/* Activation hook function */
function ngb_woocommerce_activation(){
	global $wpdb;
    $db_table_name = $wpdb->prefix . 'ngb_woocommerce_emails';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $db_table_name (
                id int(11) NOT NULL auto_increment,
                template_name varchar(255) NOT NULL,
                template_content longtext NULL,
                email_object text NULL,
                template_type enum('woocommerce_email', 'contact_form') NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL,
                created_by int(11) NULL,
                UNIQUE KEY id (id)
        ) $charset_collate;";

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql );
    /* Check Woo Commerce Plugin is Activated or Not */
    if (!in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        set_transient( 'fx-admin-notice-woocommerce', true, 5 );
    }
}
register_activation_hook(__FILE__, 'ngb_woocommerce_activation');

/* Add script and css in plugin */
function ngb_woocommerce_add_files(){
    wp_register_style('add_files', plugins_url('css/styles.941a71364ba42236534d.css',__FILE__ ));
    wp_enqueue_style('add_files');
    wp_enqueue_style( 'wpb-google-fonts', 'https://fonts.googleapis.com/icon?family=Material+Icons', false ); 
    wp_register_script('main_es5', plugins_url('js/main-es5.c0165b57de6eded8cdd0.js',__FILE__ ), '', '1.1', true);
    wp_enqueue_script('main_es5');
    wp_register_script('main_es2015', plugins_url('js/main-es2015.c0165b57de6eded8cdd0.js',__FILE__ ), '', '1.1', true);
    wp_enqueue_script('main_es2015');
    wp_register_script('polyfills', plugins_url('js/polyfills-es5.9e286f6d9247438cbb02.js',__FILE__ ), '', '1.1', true);
    wp_enqueue_script('polyfills');
    wp_register_script('polyfills_es2015', plugins_url('js/polyfills-es2015.690002c25ea8557bb4b0.js',__FILE__ ), '', '1.1', true);
    wp_enqueue_script('polyfills_es2015');
    wp_register_script('runtime_es5', plugins_url('js/runtime-es5.1eba213af0b233498d9d.js',__FILE__ ), '', '1.1', true);
    wp_enqueue_script('runtime_es5');
    wp_register_script('runtime_es2015', plugins_url('js/runtime-es2015.1eba213af0b233498d9d.js',__FILE__ ), '', '1.1', true);
    wp_enqueue_script('runtime_es2015');
}
add_action( 'admin_init','ngb_woocommerce_add_files');
?>
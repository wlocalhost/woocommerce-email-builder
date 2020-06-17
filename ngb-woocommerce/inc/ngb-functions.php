<?php
/*  Plugin deactivation Hook  */
function ngb_woocommerce_deactivation() {
    //die('de activation hook');
}
register_deactivation_hook(__FILE__, 'ngb_woocommerce_deactivation');

/* Check transient, if available display notice */
add_action( 'admin_notices', 'ngb_fx_admin_notice_woocommerce' );

function ngb_fx_admin_notice_woocommerce(){
    if( get_transient( 'fx-admin-notice-woocommerce' ) ){
        ?>
        <div class="error notice is-dismissible">
            <p>We Recommend to install Woo Commerce Plugin for edit default Woo Commerce Email Templates.</p>
        </div>
        <?php
        /* Delete transient, only display this notice once. */
        delete_transient( 'fx-admin-notice-woocommerce' );
    }
}

/* Create a Admin Menu in Dashboard */
add_action('admin_menu', 'ngb_woocommerce_plugin_custom_menu');

function ngb_woocommerce_plugin_custom_menu(){
    add_menu_page( 'NGB Woo Commerce', 'NGB Woocommerce', 'manage_options', 'ngbwoocommerce-plugin', 'ngb_woocommerce_menu_output', 'dashicons-email-alt' );
}

/* Function for display Menu Output */
function ngb_woocommerce_menu_output(){ 
	echo "Hello";
}
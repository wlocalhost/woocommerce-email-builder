<?php
/*  Register route for get_single_template */

add_action('rest_api_init', function () {
    /* 
        Register routes for get all templates
        http://localhost/wordpress/wp-json/ngbwoocommerce/v1/get-all-template/all  
    */
    register_rest_route('ngbwoocommerce/v1', 'get-all-template/all', array(
        'methods'  => 'GET',
        'callback' => 'ngb_get_all_email_template'
    ));
    /*  
        Register routes for updating template
        http://localhost/wordpress/wp-json/ngbwoocommerce/v1/update-template
    */
    register_rest_route('ngbwoocommerce/v1', '/update-template', array(
        'methods'  => 'POST',
        'callback' => 'ngb_update_email_template'
    ));
    /*
        Register route for getting email template
        http://localhost/wordpress/wp-json/ngbwoocommerce/v1/get-email-template/template-name-to-get
    */
    register_rest_route('ngbwoocommerce/v1', 'get-email-template/(?P<template_name>[\w]+)', array(
        'methods' => 'GET',
        'callback' => 'ngb_woocommerce_get_email_template'
    ));
    /*
        Register route for saving an email template
        http://localhost/wordpress/wp-json/ngbwoocommerce/v1/save-template
    */
    register_rest_route('ngbwoocommerce/v1', '/save-template', array(
        'methods'  => 'POST',
        'callback' => 'ngb_save_email_template'
    ));
    /*
        Register route for getting all email tags and placeholders
        http://localhost/wordpress/wp-json/ngbwoocommerce/v1/email-tags-and-placeholders/all
    */
    register_rest_route('ngbwoocommerce/v1', 'email-tags-and-placeholders/all', array(
        'methods'  => 'GET',
        'callback' => 'ngb_get_all_email_tags_and_placeholders'
    ));
});

/*  Function for get_single_template */
function ngb_get_all_email_template()
{

    global $wpdb;
    $table_name = $wpdb->prefix . 'ngb_woocommerce_emails';
    $qry = "SELECT id, template_name, template_type FROM " . $table_name . " ORDER BY id DESC";
    $results = $wpdb->get_results($qry);

    if (empty($results)) {
        return new WP_Error('empty_templates', 'there is no templates found', array('status' => 404));
    }

    $response = new WP_REST_Response($results);
    $response->set_status(200);

    return $response;
}
/* Function for updating email template */
function ngb_update_email_template(WP_REST_Request $request)
{
    /* 
        Parameters Name for Update Template
        #1: template_name
        #2: template_content
        #3: email_object
     */
    global $wpdb;
    $table_name = $wpdb->prefix . 'ngb_woocommerce_emails';
    $template_name = $request['template_name'];
    $template_content = $request['template_content'];
    $email_object = $request['email_object'];
    $email_object_encode = json_encode($email_object);
    $today = date('m/d/Y H:i:s');
    $date = date('Y-m-d H:i:s', strtotime($today));

    if (empty($template_name)) {
        return new WP_Error('update_template', 'template name is empty', array('status' => 404));
    } elseif (empty($email_object)) {
        return new WP_Error('update_template', 'email object is empty', array('status' => 404));
    } else {
        $qry = "UPDATE `" . $table_name . "` SET template_content = '" . $template_content . "',email_object = " . $email_object_encode . ", updated_at = '" . $date . "' WHERE template_name = '" . $template_name . "'";
        //$result = $wpdb->query($wpdb->prepare($qry));
        $result = $wpdb->query($qry);
        if (empty($result)) {
            return new WP_Error('update_template', 'unable to update template', array('status' => 404));
        }
        $success = 'template updated successfully';
        $response = new WP_REST_Response($success);
        $response->set_status(200);
        return $response;
    }
}

/* Function to get single email template */
function ngb_woocommerce_get_email_template(WP_REST_Request $request)
{

    global $wpdb;
    $table_name = $wpdb->prefix . 'ngb_woocommerce_emails';
    $template_name = $request['template_name'];

    if (empty($template_name)) {
        return new WP_Error('get_single_template', 'template not found', array('status' => 404));
    }

    $query = "SELECT id, template_name, template_content, email_object, template_type FROM " . $table_name . " WHERE template_name = '" . $template_name . "'";
    $results = $wpdb->get_row($query);
    $email_object = json_decode($results->email_object);
    $result_array = array(
        'id'                    => $results->id,
        'template_name'         => $results->template_name,
        'template_content'      => $results->template_content,
        'email_object'          => $email_object,
        'template_type'         => $results->template_type
    );
    if (!empty($results)) {
        $response = new WP_REST_Response($result_array);
        $response->set_status(200);
        return $response;
    } else {
        return new WP_Error('get_single_template', 'template not found', array('status' => 404));
    }
}

/* Function for saving email template */
function ngb_save_email_template(WP_REST_Request $request)
{
    /* 
        Parameters Name for Update Template
        #1: template_name
        #2: template_content
        #3: email_object
        #4: template_type (woocommerce_email or contact_form)
     */
    global $wpdb;
    $table_name = $wpdb->prefix . 'ngb_woocommerce_emails';
    $template_name = $request['template_name'];
    $template_content = $request['template_content'];
    $template_type = $request['template_type'];
    $email_object = $request['email_object'];
    $email_object_encode = json_encode($email_object);
    $today = date('m/d/Y H:i:s');
    $date = date('Y-m-d H:i:s', strtotime($today));
    $user_id = get_current_user_id();
    $current_user_id = (!empty($user_id)) ? $user_id : 1;

    if (empty($template_name)) {
        return new WP_Error('save_template', 'template name is empty', array('status' => 404));
    }
    // Check if template name exists in Database or not
    $query = "SELECT id, template_name FROM " . $table_name . " WHERE template_name = '" . $template_name . "'";
    $results = $wpdb->get_row($query);
    if (!empty($results)) {
        return new WP_Error('save_template', 'Template name already exists', array('status' => 404));
    }

    // If template name not exists then save in DB
    $qry = "INSERT INTO " . $table_name . " (`template_name`, `template_content`, `email_object`, `template_type`, `created_at`, created_by) VALUES ('$template_name', $template_content, $email_object_encode, '$template_type', '$date', $current_user_id)";
    $result = $wpdb->query($qry);

    if ($result) {
        $success = 'Template saved successfully';
        $response = new WP_REST_Response($success);
        $response->set_status(200);
        return $response;
    } else {
        return new WP_Error('save_template', 'Unable to save template', array('status' => 404));
    }
}

/*  Function for get all woocommerce placeholders and merge tags */
function ngb_get_all_email_tags_and_placeholders()
{

    $generic_var = array(
        '{item_names}'   => 'The explicit name(s) of the purchased items the email was triggered for as a link back to the item',
        '{customer_username}' => 'Displays the username of your customer',
        '{customer_first_name}' => 'Displays the first name of your customer',
        '{customer_last_name}' => 'Displays the last name of your customer',
        '{customer_name}' => 'Displays the concatenated first and last name of your customer',
        '{customer_email}' => 'Displays your customer’s email address',
        '{order_number}' => 'Outputs the order number for the order',
        '{order_date}' => 'Outputs the date of the order',
        '{order_datetime}' => 'Outputs the date and time of the order',
        '{order_subtotal}' => 'Outputs the order total minus the tax and shipping amounts',
        '{order_tax}' => 'Outputs the tax amount of the order',
        '{order_pay_method}' => 'Outputs the payment gateway or payment method used',
        '{order_pay_url}' => 'URL for customer to pay their (unpaid – pending) order',
        '{order_billing_address}' => 'Outputs the billing address for the specified order',
        '{order_shipping_address}' => 'Outputs the shipping address for the specified order',
        '{coupon_code}' => 'Outputs the dynamically generated coupon code for the customer. must be using the after coupon used trigger',
        '{coupon_code_used}' => 'Outputs the coupon code used by the customer for the order.',
        '{coupon_amount}' => 'Outputs the value of the coupon generated/used.',
        '{dollars_spent_order}' => 'Outputs the amount that the customer spent for the order',
        '{store_url}' => 'Outputs your store’s URL/web address without https',
        '{store_url_secure}' => 'Displays your store’s URL/web address with https',
        '{store_url=path}' => 'Displays a URL appended to your domain allowing for tracking of the link. For example, you can use {store_url=/my-account} to send your customer to their account page',
        '{store_name}' => 'Displays the name of your store',
        '{unsubscribe_url}' => 'Displays a dynamically generated URL that the customer can click to unsubscribe from emails',
        '{download_url}' => 'Displays the URL to the downloadable file when available',
        '{download_filename}' => 'Displays the filename of the downloadable file that is available to the customer',
        '{order_billing_phone}' => 'Displays the billing phone number of the customer',
        '{order_shipping_phone}' => 'Displays the shipping phone number for the customer',
        '{webversion_url}' => 'Displays the raw URL to visit a webversion of the email – requires being wrapped in a custom link in the email body',
        '{site_url}' => 'returns site url'
    );

    $abonded_cart_var = array(
        '{cart_contents}' => 'Renders the contents of the known user’s cart',
        '{cart_total}'    => 'Displays the total order value in the cart',
        '{cart_url}' => 'This complex variable will automatically redirect the user to their cart if they are logged in. If they are not logged in, they will be sent to My-Account where they will be redirected to their cart after logging in.'
    );

    $all_product_storewide = array(
        '{item_names}' => 'The name(s) of the purchased item(s) as a link back to the item',
        '{item_names_list}' => 'Displays a comma-separated list of products purchased',
        '{item_prices}' => 'Displays a list of purchased items, quantities, and prices',
        '{item_codes_prices}' => 'Displays a list of purchased items with the quantities, SKUs (codes), and prices',
        '{item_prices_categories}' => 'Displays a list of purchased items with their quantities, categories, and prices',
        '{item_categories}' => 'The list of categories that the purchased items are contained in',
        '{dollars_spent_order}' => 'Displays the total amount paid on the order by the customer'
    );

    $specific_product_storewide = array(
        '{item_names}' => 'The name(s) of the purchased item(s) as a link back to the item',
        '{item_names_list}' => 'Displays a comma-separated list of products purchased',
        '{item_prices}' => 'Displays a list of purchased items, quantities, and prices',
        '{item_prices_image}' => 'Displays a list of purchased items, quantities, and prices with images',
        '{item_codes_prices}' => 'Displays a list of purchased items with the quantities, SKUs (codes), and prices',
        '{item_prices_categories}' => 'Displays a list of purchased items with their quantities, categories, and prices',
        '{item_categories}' => 'The list of categories that the purchased items are contained in',
        '{dollars_spent_order}' => 'Displays the total amount paid on the order by the customer',
        '{item_names}' => 'The explicit name(s) of the purchased item the email was triggered for as a link back to the item',
        '{item_code}' => 'Displays the code (SKU) of the explicit product the email was triggered for',
        '{item_url}' => 'Displays the URL of the purchased item',
        '{item_category}' => 'Displays the category of the explicit product the email was triggered for',
        '{refund_amount}' => 'Outputs the value of the amount refunded',
        '{refund_reason}' => 'Outputs the reason for the refund',
        '{item_price}' => 'Displays the price of the explicit product the email was triggered for',
        '{item_quantity}' => 'Displays the number of the products purchased'
    );

    $category_storewide = array(
        '{item_name}' => 'The name(s) of the purchased item(s) as a link back to the item',
        '{item_names_list}' => 'Displays a comma-separated list of products purchased',
        '{item_prices}' => 'Displays a list of purchased items, quantities, and prices',
        '{item_prices_image}' => 'Displays a list of purchased items, quantities, and prices with images',
        '{item_codes_prices}' => 'Displays a list of purchased items with the quantities, SKUs (codes), and prices',
        '{item_prices_categories}' => 'Displays a list of purchased items with their quantities, categories, and prices',
        '{item_categories}' => 'The list of categories that the purchased items are contained in',
        '{dollars_spent_order}' => 'Displays the total amount paid on the order by the customer'
    );

    $sensi_emails = array(
        '{course_name}' => 'Displays the name of the course',
        '{course_url}' => 'Outputs the raw URL to the course',
        '{course_link}' => 'Displays a link to the selected course',
        '{lesson_name}' => 'Displays the name of the lesson',
        '{lesson_url}' => 'Outputs the raw URL to the lesson',
        '{lesson_link}' => 'Displays a link to the selected lesson',
        '{quiz_grade}' => 'Displays the score the user received for the quiz selected',
        '{quiz_passmark}' => 'Displays the passing mark the user received for the quiz',
        '{quiz_url}' => 'Outputs the raw URL to the quiz',
        '{quiz_link}' => 'Displays a link to the selected quiz',
        '{certificate_url}' => 'Outputs the raw URL to the certificate – certificates must be enabled in Sensei',
        '{certificate_link}' => 'Displays a link to the selected certificate – certificates must be enabled in Sensei'
    );

    $customer_email = array(
        'amount_spent_order' => 'Displays the amount spent on their order',
        '{amount_spent_total}' => 'Displays the total amount spent by that customer in your store',
        '{number_orders}' => 'Displays the count of orders made by your customers',
        '{last_purchase_date}' => 'Displays the date that the customer last submitted an order'
    );

    $resultArray = array(
        'All generic variables' => $generic_var,
        'Abandoned cart emails' => $abonded_cart_var,
        'All product storewide' => $all_product_storewide,
        'A specific product storewide' => $specific_product_storewide,
        'A specific category storewide' => $category_storewide,
        'Sensei emails' => $sensi_emails,
        'Customer emails' => $customer_email
    );

    $response = new WP_REST_Response($resultArray);
    $response->set_status(200);

    return $response;
}

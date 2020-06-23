<?php
/*  Register route for get_single_template */

add_action('rest_api_init', function () {
    /* 
        Register routes for get all templates
        http://localhost/wordpress/wp-json/ngbwoocommerce/v1/get-all-template/all  
    */
    register_rest_route( 'ngbwoocommerce/v1', 'get-all-template/all',array(
                    'methods'  => 'GET',
                    'callback' => 'ngb_get_all_email_template'
    ));
    /*  
        Register routes for updating template
        http://localhost/wordpress/wp-json/ngbwoocommerce/v1/update-template
    */
    register_rest_route( 'ngbwoocommerce/v1', '/update-template',array(
                    'methods'  => 'POST',
                    'callback' => 'ngb_update_email_template'
    ));
    /*
        Register route for getting email template
        http://localhost/wordpress/wp-json/ngbwoocommerce/v1/get-email-template/template-name-to-get
    */
    register_rest_route( 'ngbwoocommerce/v1', 'get-email-template/(?P<template_name>[\w]+)', array(
                'methods' => 'GET',
                'callback' => 'ngb_woocommerce_get_email_template'
    ));
    /*
        Register route for saving an email template
        http://localhost/wordpress/wp-json/ngbwoocommerce/v1/save-template
    */
    register_rest_route( 'ngbwoocommerce/v1', '/save-template',array(
                    'methods'  => 'POST',
                    'callback' => 'ngb_save_email_template'
    )); 

});

/*  Function for get_single_template */
function ngb_get_all_email_template() {

    global $wpdb;
    $table_name = $wpdb->prefix . 'ngb_woocommerce_emails';
    $qry = "SELECT id, template_name, template_type FROM ". $table_name ." ORDER BY id DESC";
    $results = $wpdb->get_results($qry);

    if (empty($results)) {
        return new WP_Error( 'empty_templates', 'there is no templates found', array('status' => 404) );
    }

    $response = new WP_REST_Response($results);
    $response->set_status(200);

    return $response;
}
/* Function for updating email template */
function ngb_update_email_template(WP_REST_Request $request){
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

    if(empty($template_name)){
        return new WP_Error( 'update_template', 'template name is empty', array('status' => 404) );
    }
    elseif(empty($email_object)){
        return new WP_Error( 'update_template', 'email object is empty', array('status' => 404) );
    }
    else{
        $qry = "UPDATE `". $table_name ."` SET template_content = '". $template_content ."',email_object = ". $email_object_encode .", updated_at = '". $date ."' WHERE template_name = '". $template_name ."'";
        //$result = $wpdb->query($wpdb->prepare($qry));
        $result = $wpdb->query($qry);
        if(empty($result)){
            return new WP_Error( 'update_template', 'unable to update template', array('status' => 404) );
        }
        $success = 'template updated successfully';
        $response = new WP_REST_Response($success);
        $response->set_status(200);
        return $response;
    }

}

/* Function to get single email template */
function ngb_woocommerce_get_email_template(WP_REST_Request $request){
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'ngb_woocommerce_emails';
    $template_name = $request['template_name'];

    if(empty($template_name)){
        return new WP_Error( 'get_single_template', 'template not found', array('status' => 404) );
    }

    $query = "SELECT id, template_name, template_content, email_object, template_type FROM ". $table_name ." WHERE template_name = '". $template_name ."'";
    $results = $wpdb->get_row($query);
    $email_object = json_decode($results->email_object);
    $result_array = array(
                        'id'                    => $results->id,
                        'template_name'         => $results->template_name,
                        'template_content'      => $results->template_content,
                        'email_object'          => $email_object,
                        'template_type'         => $results->template_type
                    );
    if(!empty($results)){
        $response = new WP_REST_Response($result_array);
        $response->set_status(200);
        return $response;
    }else{
        return new WP_Error( 'get_single_template', 'template not found', array('status' => 404) );
    }

}

/* Function for saving email template */
function ngb_save_email_template(WP_REST_Request $request){
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
    $current_user_id= (!empty($user_id)) ? $user_id : 1;

    if(empty($template_name)){
        return new WP_Error( 'save_template', 'template name is empty', array('status' => 404) );
    }
    // Check if template name exists in Database or not
    $query = "SELECT id, template_name FROM ". $table_name ." WHERE template_name = '". $template_name ."'";
    $results = $wpdb->get_row($query);
    if(!empty($results)){
        return new WP_Error( 'save_template', 'Template name already exists', array('status' => 404) );
    }

    // If template name not exists then save in DB
    $qry = "INSERT INTO ". $table_name ." (`template_name`, `template_content`, `email_object`, `template_type`, `created_at`, created_by) VALUES ('$template_name', $template_content, $email_object_encode, '$template_type', '$date', $current_user_id)";
    $result = $wpdb->query($qry);

    if($result){
        $success = 'Template saved successfully';
        $response = new WP_REST_Response($success);
        $response->set_status(200);
        return $response;
    }else{
        return new WP_Error( 'save_template', 'Unable to save template', array('status' => 404) );   
    }

}


?>
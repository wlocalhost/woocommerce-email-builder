<?php
/*  Register route for get_single_template */
add_action('rest_api_init', function () {
  register_rest_route( 'ngbwoocommerce/v1', 'get-single-template/(?P<template_name>\d+)',array(
                'methods'  => 'GET',
                'callback' => 'ngb_get_single_email_template'
      ));
});

/*  Function for get_single_template */
function ngb_get_single_email_template($request) {

    $args = array(
            'category' => $request['template_name']
    );

    $posts = get_posts($args);
    if (empty($posts)) {
    return new WP_Error( 'empty_category', 'there is no post in this category', array('status' => 404) );

    }

    $response = new WP_REST_Response($_REQUEST);
    $response->set_status(200);

    return $response;
}

 ?>
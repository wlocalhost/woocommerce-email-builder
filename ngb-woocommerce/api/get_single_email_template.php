<?php
/* 
Parameters Requires
#1: template_name
 */
require("../../../../wp-load.php");

global $wpdb;
$table_name = $wpdb->prefix . 'ngb_woocommerce_emails';

try{
    $template_name = trim($_REQUEST['template_name']);
    if(!empty($template_name)){

        $qry = "SELECT * FROM ". $table_name ." WHERE template_name = '". $template_name ."'";
        $results = $wpdb->get_row($qry);
        if(!empty($results)){

            $data = array(
                    'id'                => $results->id,
                    'template_name'     => $results->template_name,
                    'content'           => $results->template_content,
                    'type'              => $results->template_type
                    );
            $response['code'] = '11';
            $response['msg'] = "";
            $response['data'] = $data;
            echo json_encode($response); 
            return;

        }else{
            $response['code'] = '01';
            $response['msg'] = "No Email Template Found";
            $response['data'] = '';
            echo json_encode($response); 
            return;
        }

    }else{
        $response['code'] = '01';
        $response['msg'] = "No Email Template Found";
        $response['data'] = '';
        echo json_encode($response); 
        return;    
    }
    
}catch(Exception $e){
    $response['code'] = '01';
    $response['msg'] = $e->getMessage();
    $response['data'] = '';
    echo json_encode($response); 
    return;
}
?>
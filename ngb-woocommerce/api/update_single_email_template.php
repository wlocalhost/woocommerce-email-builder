<?php
/* 
Parameters Requires
#1: template_name
#2: template_content
 */
require("../../../../wp-load.php");

global $wpdb;
$table_name = $wpdb->prefix . 'ngb_woocommerce_emails';

try {

	$template_name = trim($_POST['template_name']);
	$template_content = trim($_POST['template_content']);
	// $template_type = trim($_POST['template_type']);
	$today = date('m/d/Y H:i:s');
    $date = date('Y-m-d H:i:s', strtotime($today));

	if(!empty($template_name) && !empty($template_content)){

		$query = "UPDATE ". $table_name ." SET template_content = '". $template_content ."', updated_at = '". $date ."' WHERE template_name = '". $template_name ."'";
		$result = $wpdb->query($wpdb->prepare($query));
		if($result){
			$response['code'] = '11';
            $response['msg'] = "Template Updated Successfully";
            echo json_encode($response); 
            return;
		}else{
			$response['code'] = '01';
            $response['msg'] = "Unable to update template";
            echo json_encode($response); 
            return;
		}

	}elseif(empty($template_name)){
		$response['code'] = '01';
        $response['msg'] = "Template name required";
        echo json_encode($response); 
        return;
	}else{
		$response['code'] = '01';
        $response['msg'] = "Template content requires";
        echo json_encode($response); 
        return;
	}

} catch (Exception $e) {
	$response['code'] = '01';
    $response['msg'] = $e->getMessage();
    echo json_encode($response); 
    return;
}

?>
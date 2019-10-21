<?php

function azure_devops_upload_bugs(){
	$cp_id = array_key_exists('cp_id', $_POST) ? intval($_POST['cp_id']) : false;
	$bug_id = array_key_exists('bug_id', $_POST) ? intval($_POST['bug_id']) : false;

	if (!$cp_id || !$bug_id) {
		wp_send_json_error('Invalid data: CP_ID or BUG_ID not set');
	}
	$api = new AzureDevOpsRestApi($cp_id);
	
	$bug = $api->get_bug($bug_id);
	wp_send_json_success( $api->send_issue($bug));
}

add_action( 'wp_ajax_azure_devops_upload_bugs', 'azure_devops_upload_bugs' );


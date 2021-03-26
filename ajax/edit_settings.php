<?php

function appq_jira_edit_settings()
{
    if(!check_ajax_referer('appq-ajax-nonce', 'nonce', false)){
        wp_send_json_error('You don\'t have the permission to do this');
	}
	global $wpdb;
	$cp_id = array_key_exists('cp_id', $_POST) ? intval($_POST['cp_id']) : false;
	$endpoint = array_key_exists('jira_endpoint', $_POST) ? $_POST['jira_endpoint'] : '';
	$apikey = array_key_exists('jira_apikey', $_POST) ? $_POST['jira_apikey'] : '';
	$project = array_key_exists('jira_project', $_POST) ? $_POST['jira_project'] : '';
	$upload_media = array_key_exists('media', $_POST) ? $_POST['media'] : false;
	
	$endpoint = json_encode(array(
		'endpoint' => $endpoint,
		'project' => $project
	));

	$has_value = intval($wpdb->get_var(
		$wpdb->prepare('SELECT COUNT(*) FROM ' .$wpdb->prefix .'appq_integration_center_config WHERE integration = "jira" AND campaign_id = %d', $cp_id)
	));
	if ($has_value === 0) {
		$wpdb->insert($wpdb->prefix .'appq_integration_center_config', array(
			'integration' => 'jira',
			'campaign_id' => $cp_id,
		));
	}
	$wpdb->update($wpdb->prefix .'appq_integration_center_config', array(
		'endpoint' => $endpoint,
		'apikey' => $apikey,
        'upload_media' => $upload_media ? 1 : 0,
        'is_active' => 1,
	), array(
		'integration' => 'jira',
		'campaign_id' => $cp_id,
	));
  
	$sql = 'UPDATE '.$wpdb->prefix .'appq_integration_center_config
	SET is_active = 0
	WHERE campaign_id = %d AND integration != "jira";';
	$sql = $wpdb->prepare($sql,$cp_id);
	
	$wpdb->query($sql);
	wp_send_json_success('ok');
}

add_action('wp_ajax_appq_jira_edit_settings', 'appq_jira_edit_settings');

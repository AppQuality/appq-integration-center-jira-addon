<?php

function appq_jira_edit_settings()
{
	global $wpdb;
	$cp_id = array_key_exists('cp_id', $_POST) ? intval($_POST['cp_id']) : false;
	$endpoint = array_key_exists('jira_endpoint', $_POST) ? $_POST['jira_endpoint'] : '';
	$apikey = array_key_exists('jira_apikey', $_POST) ? $_POST['jira_apikey'] : '';
	$project = array_key_exists('jira_project', $_POST) ? $_POST['jira_project'] : '';
	$field_mapping = array_key_exists('field_mapping', $_POST) ? $_POST['field_mapping'] : new stdClass();
	foreach ($field_mapping as $key => $value) {
		$field_mapping[$key]['value'] = stripslashes($value['value']);
	}
	$field_mapping = (json_encode($field_mapping));
	
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
		'field_mapping' => $field_mapping,
	), array(
		'integration' => 'jira',
		'campaign_id' => $cp_id,
	));
	wp_send_json_success('ok');
}

add_action('wp_ajax_appq_jira_edit_settings', 'appq_jira_edit_settings');

<?php

function appq_jira_edit_mapping_fields()
{
    if(!check_ajax_referer('appq-ajax-nonce', 'nonce', false)){
        wp_send_json_error('You don\'t have the permission to do this');
	}
	global $tbdb;
	$cp_id = array_key_exists('cp_id', $_POST) ? intval($_POST['cp_id']) : false;
	$name = array_key_exists('name', $_POST) ? $_POST['name'] : '';
	$value = array_key_exists('value', $_POST) ? $_POST['value'] : '';
	$value = str_replace("\\","", $value);
	$sanitize = array_key_exists('sanitize', $_POST) ? 'on' : '';
	$is_json = array_key_exists('is_json', $_POST) ? 'on' : '';

	$field_mapping = $tbdb->get_row(
		$tbdb->prepare('SELECT * FROM ' . $tbdb->prefix . 'appq_integration_center_config WHERE integration = "jira" AND campaign_id = %d', $cp_id)
	);

	$field_mapping = json_decode($field_mapping->field_mapping);

	$field_mapping->$name = [
		'value' => $value,
		'sanitize' => $sanitize,
		'is_json' => $is_json,
	];
	
	$field_mapping = (json_encode($field_mapping));

	$has_value = intval($tbdb->get_var(
		$tbdb->prepare('SELECT COUNT(*) FROM ' .$tbdb->prefix .'appq_integration_center_config WHERE integration = "jira" AND campaign_id = %d', $cp_id)
	));
	if ($has_value === 0) {
		$tbdb->insert($tbdb->prefix .'appq_integration_center_config', array(
			'integration' => 'jira',
			'campaign_id' => $cp_id,
		));
	}
	$tbdb->update($tbdb->prefix .'appq_integration_center_config', array(
        'is_active' => 1,
		'field_mapping' => $field_mapping,
	), array(
		'integration' => 'jira',
		'campaign_id' => $cp_id,
	));
	
	wp_send_json_success([
		'key' => $name,
		'content' => $value,
		'sanitize' => $sanitize,
		'json' => $is_json,
	]);
}

add_action('wp_ajax_appq_jira_edit_mapping_fields', 'appq_jira_edit_mapping_fields');

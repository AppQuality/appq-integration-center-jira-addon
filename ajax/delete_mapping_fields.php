<?php

function appq_jira_delete_mapping_fields()
{
    if(!check_ajax_referer('appq-ajax-nonce', 'nonce', false)){
        wp_send_json_error('You don\'t have the permission to do this');
	}
	global $wpdb;
	$cp_id = array_key_exists('cp_id', $_POST) ? intval($_POST['cp_id']) : false;
	$key = array_key_exists('field_key', $_POST) ? $_POST['field_key'] : '';

	$field_mapping = $wpdb->get_row(
		$wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'appq_integration_center_config WHERE integration = "jira" AND campaign_id = %d', $cp_id)
	);

	$field_mapping = json_decode($field_mapping->field_mapping);

	unset($field_mapping->$key);
	
	$field_mapping = (json_encode($field_mapping));

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
        'is_active' => 1,
		'field_mapping' => $field_mapping,
	), array(
		'integration' => 'jira',
		'campaign_id' => $cp_id,
	));
	
	wp_send_json_success($key);
}

add_action('wp_ajax_appq_jira_delete_mapping_fields', 'appq_jira_delete_mapping_fields');

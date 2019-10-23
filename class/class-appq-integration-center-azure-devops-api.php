<?php

class AzureDevOpsRestApi
{

	private $cp_id;
	private $configuration;


	public function __construct($cp_id)
	{
		$this->api_version = '5.1';
		$this->cp_id = $cp_id;
		$this->integration = array(
			'slug' => 'azure-devops',
			'name' => 'Azure Devops',
		);

		$this->configuration = $this->get_configuration($this->cp_id);

		$this->basic_configuration = array(
			'/fields/System.Title' => '{Bug.message}',
			'/fields/System.Description' => '{Bug.message}'
		);
	}


	public function get_apiurl()
	{
		return $this->configuration->endpoint;
	}
	public function get_token()
	{
		return $this->configuration->apikey;
	}
	public function get_authorization()
	{
		return "Basic ".base64_encode(':' .$this->get_token());
	}

	public function get_configuration($cp_id)
	{
		global $wpdb;

		return $wpdb->get_row(
			$wpdb->prepare('SELECT * FROM ' . $wpdb->prefix .'appq_integration_center_config WHERE campaign_id = %d AND integration = %s', $cp_id, $this->integration['slug'])
		);
	}

	public function get_issue_type()
	{
		return 'Microsoft.VSTS.WorkItemTypes.Issue';
	}

	public function bug_data_replace($bug, $value)
	{
		global $wpdb;
		$value = str_replace('{Bug.message}', $bug->message, $value);
		$value = str_replace('{Bug.steps}', $bug->description, $value);
		$value = str_replace('{Bug.expected}', $bug->expected_result, $value);
		$value = str_replace('{Bug.actual}', $bug->current_result, $value);
		$value = str_replace('{Bug.note}', $bug->note, $value);
		$value = str_replace('{Bug.id}', $bug->id, $value);
		$value = str_replace('{Bug.internal_id}', $bug->internal_id, $value);

		$value = str_replace('{Bug.status_id}', $bug->status_id, $value);
		$value = str_replace('{Bug.severity_id}', $bug->severity_id, $value);
		$value = str_replace('{Bug.replicability_id}', $bug->bug_replicability_id, $value);
		$value = str_replace('{Bug.type_id}', $bug->bug_type_id, $value);

		$type = $wpdb->get_var($wpdb->prepare('SELECT name FROM ' . $wpdb->prefix . 'appq_evd_bug_type WHERE id = %d', $bug->bug_type_id));
		$severity = $wpdb->get_var($wpdb->prepare('SELECT name FROM ' . $wpdb->prefix . 'appq_evd_severity WHERE id = %d', $bug->severity_id));
		$status = $wpdb->get_var($wpdb->prepare('SELECT name FROM ' . $wpdb->prefix . 'appq_evd_bug_status WHERE id = %d', $bug->status_id));
		$replicability = $wpdb->get_var($wpdb->prepare('SELECT name FROM ' . $wpdb->prefix . 'appq_evd_bug_replicability WHERE id = %d', $bug->bug_replicability_id));

		$value = str_replace('{Bug.severity}', $severity, $value);
		$value = str_replace('{Bug.replicability}', $replicability, $value);
		$value = str_replace('{Bug.type}', $type, $value);
		$value = str_replace('{Bug.status}', $status, $value);

		$value = str_replace('{Bug.manufacturer}', $bug->manufacturer, $value);
		$value = str_replace('{Bug.model}', $bug->model, $value);
		$value = str_replace('{Bug.os}', $bug->os, $value);
		$value = str_replace('{Bug.os_version}', $bug->os_version, $value);

		if (property_exists($bug, 'fields') && sizeof($bug->fields) > 0)
		{
			foreach ($bug->fields as $slug => $field_value) {
				$value = str_replace('{Bug.field.'.$slug.'}', $field_value, $value);
			}
		}
		$value = nl2br($value);
		$value = stripslashes($value);

		return $value;
	}

	public function map_key_and_value($bug, $key, $value)
	{
		$key = $this->bug_data_replace($bug, $key);
		$value = $this->bug_data_replace($bug, $value);

		return array(
			'key' => $key,
			'value' => $value
		);
	}

	public function map_fields($bug)
	{
		$data = array();
		$field_mapping = property_exists($this->configuration, 'field_mapping') ? json_decode($this->configuration->field_mapping, true) : array();
		$field_mapping = array_merge($this->basic_configuration, $field_mapping);

		foreach ($field_mapping as $key => $value) {
			$map = $this->map_key_and_value($bug, $key, $value);
			$data[] = array(
				"path" => $map['key'],
				"op" => "add",
				"from" => null,
				"value" => $map['value']
			);
		}

		return $data;
	}

	public function send_issue($bug)
	{
		global $wpdb;
		$data = $this->map_fields($bug);
		$req = Requests::post($this->get_apiurl() . '/wit/workitems/$' . $this->get_issue_type() . '?api-version=' . $this->api_version, array(
			'Authorization' => $this->get_authorization(),
			'Content-Type' => 'application/json-patch+json'
		), json_encode($data));

		$res = json_decode($req->body);

		if (is_null($res)) {
			return array(
				'status' => false,
				'message' => 'Error on upload bug'
			);
		}

		if (property_exists($res, 'innerException') && property_exists($res, 'message')) {
			return array(
				'status' => false,
				'message' => $res->message
			);
		}

		if (property_exists($res, 'fields'))
		{
			$wpdb->insert($wpdb->prefix . 'appq_integration_center_bugs', array(
				'bug_id' => $bug->id,
				'integration' => $this->integration['slug']
			));

			return array(
				'status' => true,
				'message' => $res
			);
		}
		return array(
			'status' => false,
			'message' => 'Generic error'
		);
	}

	public function get_issue_by_id($id)
	{
		$id = intval($id);
		$req = Requests::get($this->get_apiurl() . '/wit/workitems/'. $id .'?api-version=' . $this->api_version, array(
			'Authorization' => $this->get_authorization()
		));

		return json_decode($req->body);
	}




	public function get_bug($bug_id)
	{
		$bug_model = mvc_model('Bug');
		$bug = $bug_model->find_by_id($bug_id);
		$additional_fields = appq_get_campaign_additional_fields_data($bug_id);

		if (sizeof($additional_fields) > 0)
		{
			$bug->fields = array();
		}

		foreach ($additional_fields as $additional_field)
		{
			$bug->fields[$additional_field->slug] = $additional_field->value;
		}

		return $bug;
	}
}

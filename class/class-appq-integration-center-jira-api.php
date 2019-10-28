<?php

class JiraRestApi extends IntegrationCenterRestApi
{


	public function __construct($cp_id)
	{
		$this->api_version = '2';
		
		parent::__construct($cp_id,'jira','Jira');

		$this->basic_configuration = array(
			'summary' => array(
				'value' => '{Bug.message}',
				'sanitize' => 'off'
			),
			'description' => array(
				'value' => '{Bug.message}',
				'sanitize' => 'on'
			),
		);
	}


	public function get_apiurl()
	{
		$endpoint_data = json_decode($this->configuration->endpoint);
		if (empty($endpoint_data)) {
			return '';
		}
		return $endpoint_data->endpoint;
	}

	public function get_project()
	{
		$endpoint_data = json_decode($this->configuration->endpoint);
		if (empty($endpoint_data)) {
			return '';
		}
		return $endpoint_data->project;
	}

	public function get_issue_type()
	{
		return 'Task';
	}

	public function bug_data_replace_jira($bug, $value,$sanitize)
	{
		global $wpdb;
		
		if (strpos($value,'{Bug.media}') !== false)
		{
			$media =  $wpdb->get_results($wpdb->prepare('SELECT type,location FROM ' . $wpdb->prefix . 'appq_evd_bug_media WHERE bug_id = %d', $bug->id));
			$media_items = array();
			foreach ($media as $media_item) 
			{
				if ($media_item->type == 'image') {
					$media_items[] = '!' . $media_item->location . '! - ' . $media_item->location;
				} else {
					$media_items[] = $media_item->location;
				}
			}
			$value = str_replace('{Bug.media}', implode(', ',$media_items), $value);
		}
		$value = parent::bug_data_replace($bug, $value);
		
		$value = strip_tags($value);
		
		if ($sanitize)
		{
			$value = str_replace('_','\\_',$value);
			$value = str_replace('*','\\*',$value);
		}

		return $value;
	}

	public function map_key_and_value_jira($bug, $key, $value,$sanitize)
	{
		$key = $this->bug_data_replace_jira($bug, $key,$sanitize);
		$value = $this->bug_data_replace_jira($bug, $value,$sanitize);

		return array(
			'key' => $key,
			'value' => $value
		);
	}

	public function map_fields($bug)
	{
		$field_mapping = $this->get_field_mapping();
		foreach ($field_mapping as $key => $item) {
			$value = $item['value'];
			$sanitize = array_key_exists('sanitize', $item) && $item['sanitize'] === 'on';
			$map = $this->map_key_and_value_jira($bug, $key, $value, $sanitize);
			$data[$map['key']] = $map['value'];
		}

		return $data;
	}

	public function send_issue($bug)
	{
		global $wpdb;
		
		// TODO: Remove this control when old bugtracker will be discontinued
		$is_uploaded = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM ' . $wpdb->prefix .'appq_evd_bugtracker_sync WHERE bug_id = %d AND bug_tracker = "Jira"',$bug->id));
		$is_uploaded = intval($is_uploaded);
		if ($is_uploaded > 0)
		{
			return array(
				'status' => false,
				'message' => "This bug is already uploaded with old bugtracker"
			);
		}
		
		
		$data = $this->map_fields($bug);
		$data['issuetype'] = array(
			'name' => $this->get_issue_type()
		);
		$data['project'] = array(
			'key' => $this->get_project()
		);
		$body = new stdClass();
		$body->update = new stdClass();
		$body->fields = (object) $data;
		$url = parse_url($this->get_apiurl());
		$req = Requests::post($url['scheme'] . '://' . $this->get_authorization() . '@' . $url['host'] . '/rest/api/'.$this->api_version.'/issue'  , array(
			'Content-Type' => 'application/json',
			'Accept' => 'application/json'
		), json_encode($body));

		$res = json_decode($req->body);

		if (is_null($res)) {
			return array(
				'status' => false,
				'message' => 'Error on upload bug'
			);
		}
		
		if (property_exists($res, 'key'))
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
		
		if ($res->{"status-code"} == 404) {
			return array(
				'status' => false,
				'message' => $res->message
			);
		}
		
		if (property_exists($res,'errorMessages')) {
			return array(
				'status' => false,
				'message' => implode(',',$res->errorMessages) . ' - ' . implode(',', (array) $res->errors)
			);
		}

		return array(
			'status' => false,
			'message' => 'Generic error'
		);
	}

	public function get_issue_by_id($id)
	{
		return false;
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

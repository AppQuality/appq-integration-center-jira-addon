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

	public function map_key_and_value($bug,$key, $value)
	{
		$key = $key;
		
		$value = str_replace('{Bug.message}',$bug->message,$value);
		
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
			$map = $this->map_key_and_value($bug,$key,$value);
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

		$wpdb->insert($wpdb->prefix . 'appq_integration_center_bugs', array(
			'bug_id' => $bug->id,
			'integration' => $this->integration['slug']
		));

		return json_decode($req->body);
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

		return $bug;
	}
}

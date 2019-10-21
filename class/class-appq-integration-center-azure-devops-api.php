<?php

class AzureDevOpsRestApi
{

	private $cp_id;
	private $configuration;


	public function __construct($cp_id)
	{
		$this->api_version = '5.1';
		$this->cp_id = $cp_id;
		$this->integration = 'azure-devops';
		
		$this->configuration = $this->get_configuration($this->cp_id);
	}


	public function get_apiurl()
	{
		return $this->configuration->endpoint;
	}
	public function get_token()
	{
		return $this->configuration->token;
	}
	public function get_authorization()
	{
		return "Basic ".base64_encode(':' .$this->get_token());
	}

	public function get_configuration()
	{
		return (object) array(
			'endpoint' => 'https://dev.azure.com/cannarocks/test/_apis',
			'token' => 'gv6ay4jhf4uvyv6i2g5wh5n5myzk5vhuijwjvrd4mht5hcyrh6nq'
		);
	}

	public function get_issue_type()
	{
		return 'Microsoft.VSTS.WorkItemTypes.Issue';
	}


	public function send_issue($bug)
	{
		global $wpdb;
		$req = Requests::post($this->get_apiurl() . '/wit/workitems/$' . $this->get_issue_type() . '?api-version=' . $this->api_version, array(
			'Authorization' => $this->get_authorization(),
			'Content-Type' => 'application/json-patch+json'
		), json_encode(array(
			array(
				"path" => "/fields/System.Title",
				"op" => "add",
				"from" => null,
				"value" => $bug->message
			)
		)));
		
		$wpdb->insert($wpdb->prefix . 'appq_integration_center_bugs',array(
			'bug_id' => $bug->id,
			'integration' => $this->integration
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

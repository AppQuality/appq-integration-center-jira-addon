<?php

class AzureDevOpsRestApi
{

	private $token;
	private $apiurl;


	public function __construct($apiurl, $token)
	{
		$this->api_version = '5.1';
		$this->apiurl = $apiurl;
		$this->token = $token;
	}


	public function get_apiurl()
	{
		return $this->apiurl;
	}
	public function get_token()
	{
		return $this->token;
	}
	public function get_authorization()
	{
		return "Basic ".base64_encode(':' .$this->get_token());
	}


	public function get_issue_type()
	{
		return 'Microsoft.VSTS.WorkItemTypes.Issue';
	}


	public function send_issue($bug)
	{
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
}

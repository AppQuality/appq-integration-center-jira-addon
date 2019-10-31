<?php

class JiraRestApi extends IntegrationCenterRestApi
{


	public function __construct($cp_id)
	{
		$this->api_version = '2';

		parent::__construct($cp_id, 'jira', 'Jira');

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

	
	/**
	 * Get the apiurl
	 * @method get_apiurl
	 * @date   2019-10-30T14:52:24+010
	 * @author: Davide Bizzi <clochard>
	 * @return string                  The api URL
	 */
	public function get_apiurl()
	{
		$endpoint_data = json_decode($this->configuration->endpoint);
		if (empty($endpoint_data)) {
			return '';
		}

		return $endpoint_data->endpoint;
	}

	/**
	 * Get jira project
	 * @method get_project
	 * @date   2019-10-30T15:31:33+010
	 * @author: Davide Bizzi <clochard>
	 * @return string                  The project slug
	 */
	public function get_project()
	{
		$endpoint_data = json_decode($this->configuration->endpoint);
		if (empty($endpoint_data)) {
			return '';
		}

		return $endpoint_data->project;
	}
	
	/**
	 * Get the data for authorization
	 * @method get_authorization
	 * @date   2019-10-30T14:53:28+010
	 * @author: Davide Bizzi <clochard>
	 * @return string	The data for the authorization. Overwrite in the subclass if you need manipulation of the token (base64 encoding,...)
	 */
	public function get_issue_type()
	{
		return 'Task';
	}
	
	/**
	 * Replace {placeholders} in a field mapping value with data from a bug
	 * @method bug_data_replace
	 * @date   2019-10-30T15:06:02+010
	 * @author: Davide Bizzi <clochard>
	 * @param  MvcObject                  $bug   The bug (MvcObject with additional fields on field property)
	 * @param  string                  $value The string with {placeholders} to fill
	 * @param  bool                  $sanitize Escape special jira characters (_,*,...)
	 * @return string                         
	 */
	public function bug_data_replace_jira($bug, $value, $sanitize, $is_json = false)
	{
		global $wpdb;

		if (strpos($value, '{Bug.media}') !== false)
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
			$value = str_replace('{Bug.media}', implode(' , ', $media_items), $value);
		}
		$value = parent::bug_data_replace($bug, $value);

		$value = strip_tags($value);

		if ($sanitize)
		{
			$value = str_replace('_', '\\_', $value);
			$value = str_replace('*', '\\*', $value);
		}

		if ($is_json)
		{
			$value = json_decode($value);
		}

		return $value;
	}
	
	/**
	 * Get mapped field data
	 * @method map_fields
	 * @date   2019-10-30T15:20:13+010
	 * @author: Davide Bizzi <clochard>
	 * @param  MvcObject                  $bug The bug to map (MvcObject with additional fields on field property)
	 * @return array                       An associative array with bugtracker field as key and the data to send as value
	 */
	public function map_fields($bug)
	{
		$field_mapping = $this->get_field_mapping();
		foreach ($field_mapping as $key => $item) 
		{
			$value = $item['value'];
			$sanitize = array_key_exists('sanitize', $item) && $item['sanitize'] === 'on';
			$is_json = array_key_exists('is_json', $item) && $item['is_json'] === 'on';
			$key = $this->bug_data_replace_jira($bug, $key, $sanitize);
			$value = $this->bug_data_replace_jira($bug, $value, $sanitize,$is_json);
			$data[$key] = $value;
		}

		return $data;
	}

	/** 
	 * Send the issue
	 * @method send_issue
	 * @date   2019-10-30T15:21:44+010
	 * @author: Davide Bizzi <clochard>
	 * @param  MvcObject                  $bug The bug to upload (MvcObject with additional fields on field property)
	 * @return array 					An associative array {
	 * 										status: bool,		If uploaded successfully
	 * 										message: string		The response of the upload or an error message on error 
	 * 									}
	 */
	public function send_issue($bug)
	{
		global $wpdb;

		// TODO: Remove this control when old bugtracker will be discontinued
		$is_uploaded = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM ' . $wpdb->prefix .'appq_evd_bugtracker_sync WHERE bug_id = %d AND bug_tracker = "Jira"', $bug->id));
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
		$url = $url['scheme'] . '://' . $this->get_authorization() . '@' . $url['host'] . '/rest/api/'.$this->api_version.'/issue';
		$req = $this->http_post($url, array(
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
			if (property_exists($this->configuration, 'upload_media') && intval($this->configuration->upload_media) > 0)
			{
				$return = array(
					'status' => true,
					'message' => ''
				);
				$media =  $wpdb->get_col($wpdb->prepare('SELECT location FROM ' . $wpdb->prefix . 'appq_evd_bug_media WHERE bug_id = %d', $bug->id));
				foreach ($media as $media_item)
				{
					$result = $this->add_attachment($res->key, $media_item);
					if (!$result['status'])
					{
						$return['status'] = false;
						$return['message'] = $return['message'] . ' <br> '. $result['message'];
					}
				}
				
				if (!$return['status'])
				{
					return $return;
				}
			}

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

		if (property_exists($res, 'errorMessages')) {
			return array(
				'status' => false,
				'message' => implode(',', $res->errorMessages) . ' - ' . implode(',', (array) $res->errors)
			);
		}

		return array(
			'status' => false,
			'message' => 'Generic error'
		);
	}
	
	/**
	 * Get an issue associated with a bug
	 * @method get_issue_by_id
	 * @date   2019-10-30T15:24:54+010
	 * @author: Davide Bizzi <clochard>
	 * @param  int                  $id The bug id
	 * @return mixed                      false on error, an object on success
	 */
	public function get_issue_by_id($id)
	{
		return false;
	}


	/**
	 * Add bug media to an issue on jira
	 * @method add_attachment
	 * @date   2019-10-30T15:34:39+010
	 * @author: Davide Bizzi <clochard>
	 * @param  string                  $key   The issue key
	 * @param                    $media The url of the media to attach
	 * @return array 					An associative array {
	 * 										status: bool,		If uploaded successfully
	 * 										message: string		The response of the upload or an error message on error 
	 * 									}
	 */
	public function add_attachment($key, $media)
	{
		$basename = basename($media);
		$filename =  ABSPATH . 'wp-content/plugins/appq-integration-center/tmp/' . $basename;
		file_put_contents(ABSPATH . 'wp-content/plugins/appq-integration-center/tmp/' . $basename, fopen($media, 'r'));

		$headers = array(
			"X-Atlassian-Token"=>"no-check",
			"Content-Type"=>"multipart/form-data",
		);
		
		$url = parse_url($this->get_apiurl());
		$url = $url['scheme'] . '://' . $this->get_authorization() . '@' . $url['host'] . '/rest/api/'.$this->api_version.'/issue/' .$key .'/attachments';
		
		$req = $this->http_multipart_post($url,$headers,array (
			'file' => new CURLFile($filename)
		));
		
		
		$ret = array(
			'status' => false,
			'message' => 'Generic error on attachment ' . $basename
		);
		if($req->status)
		{
			if ($req->info['http_code'] == 200) {
				$ret = array(
					'status' => true,
					'message' => json_decode($req->body)
				);
			} elseif($req->info['http_code'] == 413) {
				$ret['message'] = $ret['message'] . ' - Entity too large';
			} else {
				$ret['message'] = $ret['message'] . ' - Error ' .  $req->info['http_code'];
			}
		}
		else
		{
			$ret = array(
				'status' => false,
				'message' => $req->error
			);
		}
		unlink($filename);
		return $ret;
	}

}

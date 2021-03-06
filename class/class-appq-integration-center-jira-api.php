<?php

class JiraRestApi extends IntegrationCenterRestApi
{


	public function __construct($cp_id)
	{
		$this->api_version = '2';

		parent::__construct($cp_id, 'jira', 'Jira');

		$this->basic_configuration = array(
			'summary' => array(
				'value' => '[{Bug.internal_id}] {Bug.message}',
				'sanitize' => 'off'
			),
			'issuetype' => array(
				'value' => '{"name":"Task"}',
				'is_json' => 'on'
			),
			'description' => array(
				'value' => '*Type*: {Bug.type}
                    *User Replicability*: {Bug.replicability}
                    *User Severity*: {Bug.severity}
                    
                    *Steps to reproduce*
                    {Bug.steps}
                    
                    *Expected Result*
                    {Bug.expected}
                    
                    *Current Result*
                    {Bug.actual}
                    
                    *Bug Media*
                    {Bug.media_links}
                    
                     
                    *Extra note*
                     {Bug.note}
                    
                    *Device*:
                    {Bug.manufacturer} {Bug.model} {Bug.os} {Bug.os_version}
                ',
				'sanitize' => 'off'
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
		global $tbdb;

		if (strpos($value, '{Bug.media}') !== false)
		{
			$media =  $tbdb->get_results($tbdb->prepare('SELECT type,location FROM ' . $tbdb->prefix . 'appq_evd_bug_media WHERE bug_id = %d', $bug->id));
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
			if ($value === null) {
				throw new Exception(__("Invalid JSON to decode", "appq-integration-center-jira-addon"), 1);
			}
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
			try {
				$key = $this->bug_data_replace_jira($bug, $key, $sanitize);
				$value = $this->bug_data_replace_jira($bug, $value, $sanitize,$is_json);
			} catch(Exception $e) {
				throw new Exception(__("Invalid JSON to decode for mapping", "appq-integration-center-jira-addon") . " " . $key);
			}
			$data[$key] = $value;
		}

		return $data;
	}

	/**
	 * Delete an issue from JIRA
	 * @param  string $bugtracker_id
	 */
	public function delete_issue($bugtracker_id) {
		global $tbdb;
		if (empty($bugtracker_id)) {
			return array(
				'status' => false,
				'data' => array(
					'auth_error' => false,
					'message' => __('Empty bugtracker_id', "appq-integration-center-jira-addon")
				)
			);
		}

		$url = parse_url($this->get_apiurl());
		$url = $url['scheme'] . '://' . $this->get_authorization() . '@' . $url['host'] . '/rest/api/'.$this->api_version.'/issue/' . $bugtracker_id;
		$req = $this->http_delete($url, array(
			'Content-Type' => 'application/json',
			'Accept' => 'application/json'
		));

		if (empty($req) || !property_exists($req,'status_code')) {
			return array(
				'status' => false,
				'data' => array(
					'auth_error' => false,
					'message' => __('Empty Response from Jira', "appq-integration-center-jira-addon")
				)
			);
		} else {
			$delete_from_db = false;
			if ($req->status_code == 204) {
				$delete_from_db = true;
				$authorized = true;
			} elseif ($req->status_code == 403) {
				$delete_from_db = true;
			}
		}

		if ($authorized) {
			$data = array(
				'auth_error' => false,
				'message' => __('Successfully deleted the issue', "appq-integration-center-jira-addon")
			);
		} else {
			$data = array(
				'auth_error' => true,
				'message' => __('You are not authorized to delete issues on this project', "appq-integration-center-jira-addon")
			);
		}

		if ($delete_from_db) {
			$tbdb->delete($tbdb->prefix . 'appq_integration_center_bugs',array(
				'bugtracker_id' => $bugtracker_id
			));
			return array(
				'status' => true,
				'data' => $data
			);
		}


		return array(
			'status' => false,
			'data' => array(
				'auth_error' => false,
				'message' => __('There was an error deleting the issue. The status code was', "appq-integration-center-jira-addon") . " " . $req->status_code
			)
		);
	}



	/**
	 * Send the issue
	 * @method update_issue
	 * @date              2020-11-25T10:27:36+010
	 * @author: Davide Bizzi <clochard>
	 * @param  MvcObject                  $bug The bug to upload (MvcObject with additional fields on field property)
	 * @param  string                  $key The key of the issue to update
	 * @return array 					An associative array {
	 * 										status: bool,		If uploaded successfully
	 * 										message: string		The response of the upload or an error message on error
	 * 									}
	 */
	public function update_issue($bug,$key) {
		global $tbdb;

		// TODO: Remove this control when old bugtracker will be discontinued
		$is_uploaded = $this->is_uploaded_with_old_bugtracker($bug->id);
		if ($is_uploaded)
		{
			return array(
				'status' => false,
				'message' => __("This bug is already uploaded with old bugtracker", "appq-integration-center-jira-addon")
			);
		}


		$data = $this->map_fields($bug);
		$data['project'] = array(
			'key' => $this->get_project()
		);
		$body = new stdClass();
		$body->update = new stdClass();
		$body->fields = (object) $data;
		$url = parse_url($this->get_apiurl());
		$url = $url['scheme'] . '://' . $this->get_authorization() . '@' . $url['host'] . '/rest/api/'.$this->api_version.'/issue/'.$key;
		$req = $this->http_put($url, array(
			'Content-Type' => 'application/json',
			'Accept' => 'application/json'
		), json_encode($body));


		if (!$req->success | $req->status_code != 204) {
			return array(
				'status' => false,
				'message' => __("Something went wrong during the update. The error code was", "appq-integration-center-jira-addon") . " " . $req->status_code
			);
		}
		if (property_exists($this->configuration, 'upload_media') && intval($this->configuration->upload_media) > 0)
		{
			$this->clear_attachments($key);
			$return = array(
				'status' => true,
				'message' => ''
			);
			if (property_exists($bug,'media')) {
				$media = $bug->media;
			} else {
				$media =  $tbdb->get_col($tbdb->prepare('SELECT location FROM ' . $tbdb->prefix . 'appq_evd_bug_media WHERE bug_id = %d', $bug->id));
			}
			foreach ($media as $media_item)
			{
				$result = $this->add_attachment($key, $media_item);
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
			'message' => $key . ' ' . __('updated', "appq-integration-center-jira-addon")
		);

	}

	private function is_uploaded_with_old_bugtracker($bug_id) {
		global $tbdb;

		$is_uploaded = $tbdb->get_var($tbdb->prepare('SELECT COUNT(*) FROM ' . $tbdb->prefix .'appq_evd_bugtracker_sync WHERE bug_id = %d AND bug_tracker = "Jira"', $bug_id));
		$is_uploaded = intval($is_uploaded);

		return $is_uploaded > 0;
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
		global $tbdb;

		// TODO: Remove this control when old bugtracker will be discontinued
		$is_uploaded = $this->is_uploaded_with_old_bugtracker($bug->id);
		if ($is_uploaded)
		{
			return array(
				'status' => false,
				'message' => __("This bug is already uploaded with old bugtracker", "appq-integration-center-jira-addon")
			);
		}

		$data = $this->map_fields($bug);
		$data['project'] = array(
			'key' => $this->get_project()
		);
		$body = new stdClass();
		$body->update = new stdClass();
		$body->fields = (object) $data;
		$url = parse_url($this->get_apiurl());
        $host = array_key_exists('path', $url) ? $url['host'] . "/" . str_replace('/', '', $url['path']) : $url['host'];
        $url = $url['scheme'] . '://' . $this->get_authorization() . '@' . $host . '/rest/api/'.$this->api_version.'/issue';
		$req = $this->http_post($url, array(
			'Content-Type' => 'application/json',
			'Accept' => 'application/json'
		), json_encode($body));

		$res = json_decode($req->body);

		if (is_null($res)) {
			return array(
				'status' => false,
				'message' => __('Error on bug upload', "appq-integration-center-jira-addon")
			);
		}

		if (property_exists($res, 'key'))
		{
			$tbdb->insert($tbdb->prefix . 'appq_integration_center_bugs', array(
				'bug_id' => $bug->id,
				'bugtracker_id' => $res->key,
				'integration' => $this->integration['slug']
			));
			if (property_exists($this->configuration, 'upload_media') && intval($this->configuration->upload_media) > 0)
			{
				$return = array(
					'status' => true,
					'message' => ''
				);
				if (property_exists($bug,'media')) {
					$media = $bug->media;
				} else {
					$media =  $tbdb->get_col($tbdb->prepare('SELECT location FROM ' . $tbdb->prefix . 'appq_evd_bug_media WHERE bug_id = %d', $bug->id));
				}

				$media_error = false;

				foreach ($media as $media_item)
				{
					$result = $this->add_attachment($res->key, $media_item);
					if (!$result['status'])
					{
						$media_error = $return['message'] . ' <br> '. $result['message'];
					}
				}

				if ($media_error) {
					return array(
						'status' => true,
						'warning' => $media_error,
						'message' => $res
					);
				}

			}

			return array(
				'status' => true,
				'message' => $res
			);
		}

		if (property_exists($res, "status-code") && $res->{"status-code"} == 404) {
			return array(
				'status' => false,
				'message' => $res->message
			);
		}

		if (property_exists($res, 'errorMessages')) {

		    $errors = implode(',', $res->errorMessages);

		    if(!empty( $res->errors ))
            {
                $errors .= " - ";
                foreach($res->errors as $key => $value)
                {
                    $errors .= sprintf('%s: %s, ', $key, $value);
                }
            }

			return array(
				'status' => false,
				'message' => $errors
			);
		}

		return array(
			'status' => false,
			'message' => __('Generic error', "appq-integration-center-jira-addon")
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
		global $tbdb;

		$sql = $tbdb->prepare('SELECT bugtracker_id FROM wp_appq_integration_center_bugs 
			WHERE bug_id = %d AND integration = "jira"',$id);
		return $tbdb->get_var($sql);
	}


	private function clear_attachments($key) {
		$url = parse_url($this->get_apiurl());
		$url = $url['scheme'] . '://' . $this->get_authorization() . '@' . $url['host'] . '/rest/api/'.$this->api_version.'/issue/'.$key.'?fields=attachment';
		$req = $this->http_get($url, array(
			'Content-Type' => 'application/json',
			'Accept' => 'application/json'
		));

		$body = json_decode($req->body);

		if ($body && property_exists($body,'fields') && property_exists($body->fields,'attachment')) {
			$url = parse_url($this->get_apiurl());
			$base_url = $url['scheme'] . '://' . $this->get_authorization() . '@' . $url['host'] . '/rest/api/'.$this->api_version.'/attachment/';
			foreach($body->fields->attachment as $attachment) {
				$url = $base_url . $attachment->id;
				$req = $this->http_delete($url, array(
					'Content-Type' => 'application/json',
					'Accept' => 'application/json'
				));
			}
		}
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
			'message' => __('Generic error on attachment', "appq-integration-center-jira-addon") . " " . $basename
		);
		if($req->status)
		{
			if ($req->info['http_code'] == 200) {
				$ret = array(
					'status' => true,
					'message' => json_decode($req->body)
				);
			} elseif($req->info['http_code'] == 413) {
                $message = __('Entity too large', "appq-integration-center-jira-addon");
                $ret['message'] = $ret['message'] . ' - ' . $message;
			} else {
                $message = __('Error', "appq-integration-center-jira-addon");
                $ret['message'] = $ret['message'] . ' - ' . $message .  $req->info['http_code'];
			}
		}
		unlink($filename);

		return $ret;
	}

	public function get_issue($key) {
		$url = parse_url($this->get_apiurl());

		$host = array_key_exists('path', $url) ? $url['host'] . "/" . str_replace('/', '', $url['path']) : $url['host'];
		$url = $url['scheme'] . '://' . $this->get_authorization() . '@' . $host . '/rest/api/'.$this->api_version.'/issue/' .$key ;

		$headers = array();
		$req = $this->http_get($url,$headers);
        $ret = array();

		if($req->success)
		{
			if ($req->status_code == 200) {
				$ret = array(
					'status' => true,
					'message' => json_decode($req->body)
				);
			} elseif($req->status_code == 413) {
                $message = __('Entity too large', "appq-integration-center-jira-addon");
				$ret['message'] = $ret['message'] . ' - ' . $message;
			} else {
                $message = __('Error', "appq-integration-center-jira-addon");
				$ret['message'] = $ret['message'] . ' - ' . $message .  $req->info['http_code'];
			}
		}
		else
		{
		    $message = __('Error', "appq-integration-center-jira-addon");
			$ret = array(
				'status' => false,
				'message' => $message . ' ' . $req->status_code . ': ' . $req->body
			);
		}

		return $ret;
	}

}

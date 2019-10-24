<?php

function appq_jira_upload_bugs($cp_id, $bug_id){
	$api = new JiraRestApi($cp_id);

	$bug = $api->get_bug($bug_id);
	return $api->send_issue($bug);
}


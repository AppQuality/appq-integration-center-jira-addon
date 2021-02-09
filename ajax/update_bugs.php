<?php

function appq_jira_update_bugs($cp_id, $bug_id){
	$api = new JiraRestApi($cp_id);
	
	if($bug_id == 'default') {
		$uploaded_issue = $api->get_issue_by_id($cp_id);
	} else {
		$uploaded_issue = $api->get_issue_by_id($bug_id);
	}

	if (empty($uploaded_issue)) {
		return array(
			'status' => false,
			'message' => 'No issue to update'
		);
	}
	$bug = $api->get_bug($bug_id);
	return $api->update_issue($bug,$uploaded_issue);
}

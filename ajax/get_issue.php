<?php


function appq_jira_get_issue($cp_id,$issue_id)
{
	$api = new JiraRestApi($cp_id);

	$issue = $api->get_issue($issue_id);
	return $issue;
}
<?php
/*
 * @Author: Davide Bizzi <clochard>
 * @Date:   26/05/2020
 * @Filename: delete_bugs.php
 * @Last modified by:   clochard
 * @Last modified time: 26/05/2020
 */




function appq_jira_delete_bugs($cp_id, $bugtracker_id){
	$api = new JiraRestApi($cp_id);

	return $api->delete_issue($bugtracker_id);
}


<?php

function appq_azure_devops_upload_bugs($cp_id, $bug_id){
	$api = new AzureDevOpsRestApi($cp_id);

	$bug = $api->get_bug($bug_id);
	return $api->send_issue($bug);
}


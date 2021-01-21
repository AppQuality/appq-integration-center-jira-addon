<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://bitbucket.org/%7B1c7dab51-4872-4f3e-96ac-11f21c44fd4b%7D/
 * @since      1.0.0
 *
 * @package    Appq_Integration_Center_Jira_Addon
 * @subpackage Appq_Integration_Center_Jira_Addon/admin/partials
 */
 
$field_mapping = !empty($config) ? json_decode($config->field_mapping,true) : array();
if (empty($field_mapping)) {
    $field_mapping = array();
}
$endpoint_data = !empty($config) && property_exists($config,'endpoint') ? json_decode($config->endpoint,true) : array();
?>
<form id="jira_settings" class="container-fluid">
	<h3> Jira Integration Settings</h3>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group row">
                <label for="jira_endpoint" class="col-sm-2 col-form-label">Endpoint</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="jira_endpoint" id="jira_endpoint" value="<?= !empty($endpoint_data) ? $endpoint_data['endpoint'] : ''?>" placeholder="https://{organization}.atlassian.net">
                </div>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group row">
                <label for="jira_apikey" class="col-sm-3 col-form-label">User email : api token</label>
                <div class="col-sm-9">
                    <input type="password" autocomplete="new-password" class="form-control" name="jira_apikey" id="jira_apikey" value="<?= !empty($config) ? $config->apikey : ''?>"  placeholder="username@email.com:xxxxxxxxxxxxxxxxxx">
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group row">
                <label for="jira_project" class="col-sm-3 col-form-label">Project</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="jira_project" id="jira_project" value="<?= !empty($endpoint_data) ? $endpoint_data['project'] : ''?>"  placeholder="CAQ">
                </div>
            </div>
        </div>
    </div>
	<?php 
	$this->partial('settings/field-mapping', array(
		'field_mapping' => $field_mapping,
		'campaign_id' => $campaign_id
    ));
    ?>
	<div class="row">
		<button type="button" class="save col-sm-2 offset-sm-10 btn btn-primary">Save</button>
	</div>
</form>

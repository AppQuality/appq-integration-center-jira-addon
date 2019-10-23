<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://bitbucket.org/%7B1c7dab51-4872-4f3e-96ac-11f21c44fd4b%7D/
 * @since      1.0.0
 *
 * @package    Appq_Integration_Center_Azure_Devops_Addon
 * @subpackage Appq_Integration_Center_Azure_Devops_Addon/admin/partials
 */
?>
<form id="azure_devops_settings" class="container-fluid">
	<h3> Azure DevOps Integration Settings</h3>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group row">
                <label for="azure_devops_endpoint" class="col-sm-2 col-form-label">Endpoint</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="azure_devops_endpoint" id="azure_devops_endpoint" value="<?= !empty($config) ? $config->endpoint : ''?>" placeholder="httpx://xxx.xxxxxx.xx/xxx/">
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="form-group row">
                <label for="azure_devops_pat" class="col-sm-3 col-form-label">Personal Access Token</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" name="azure_devops_apikey" id="azure_devops_apikey" value="<?= !empty($config) ? $config->apikey : ''?>"  placeholder="••••••••••">
                </div>
            </div>
        </div>
    </div>
	<?php 
	$field_mapping = !empty($config) ? json_decode($config->field_mapping,true) : array();
	if (empty($field_mapping)) {
		$field_mapping = array();
	}
	$this->partial('settings/field-mapping', array(
		'field_mapping' => $field_mapping
	)) ?>
	<div class="row">
		<button type="button" class="save col-sm-2 offset-sm-10 btn btn-primary">Save</button>
	</div>
</form>
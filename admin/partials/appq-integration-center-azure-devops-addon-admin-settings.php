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
<div class="container-flud">
	<h3> Azure DevOps Integration Settings</h3>
    <div class="row">
        <div class="col-sm-4">
            <div class="form-group row">
                <label for="azure_devops_endpoint" class="col-sm-2 col-form-label">Endpoint</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="ssh_username"placeholder="Username">
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="form-group row">
                <label for="azure_devops_pat" class="col-sm-3 col-form-label">Personal Access Token</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" id="ssh_password" placeholder="••••••••••">
                </div>
            </div>
        </div>
    </div>
	<?php $this->partial('settings/field-mapping') ?>
</div>
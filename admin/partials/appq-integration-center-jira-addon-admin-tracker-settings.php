<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/AppQuality/
 * @since      1.0.0
 *
 * @package    Appq_Integration_Center_Jira_Addon
 * @subpackage Appq_Integration_Center_Jira_Addon/admin/partials
 */

$endpoint_data = !empty($config) && property_exists($config, 'endpoint') ? json_decode($config->endpoint, true) : array();
?>
<form class="form" id="jira_tracker_settings">
    <div class="form-group">
        <input type="text" class="form-control" id="jira_endpoint" name="jira_endpoint" placeholder="<?= __('https://yourcompanyname.atlassian.com', 'appq-integration-center-jira-addon') ?>" value="<?= !empty($endpoint_data['endpoint']) ? $endpoint_data['endpoint'] : ''; ?>">
        <label for="jira_endpoint"><?= __("Jira Endpoint", 'appq-integration-center-jira-addon'); ?></label>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" id="jira_apikey" name="jira_apikey" placeholder="<?= __('email@adress.com:APITOKEN', 'appq-integration-center-jira-addon') ?>" value="<?= !empty($config) ? $config->apikey : ''; ?>">
        <label for="jira_apikey"><?= __("Authentication", 'appq-integration-center-jira-addon'); ?></label>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="jira_project" id="jira_project" placeholder="<?= __('ABC', 'appq-integration-center-jira-addon') ?>" value="<?= !empty($endpoint_data) ? $endpoint_data['project'] : '' ?>">
        <label for="jira_project"><?= __('Project ID', 'appq-integration-center-jira-addon') ?></label>
    </div>
    <small><?= __('Media preferences', 'appq-integration-center-jira-addon') ?></small>
    <div class="form-group">
        <label class="checkbox checkbox-styled">
            <input type="checkbox" name="media" id="media" <?= isset($config->upload_media) ? checked($config->upload_media, 1, false) : '' ?>>
            <?= __('Upload media', 'appq-integration-center-jira-addon') ?>
        </label>
    </div>
</form>
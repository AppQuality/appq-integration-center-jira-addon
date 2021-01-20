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

$field_mapping = !empty($config) ? json_decode($config->field_mapping, true) : array();
if (empty($field_mapping)) {
    $field_mapping = array();
}
$endpoint_data = !empty($config) && property_exists($config, 'endpoint') ? json_decode($config->endpoint, true) : array();
?>
<form id="jira_settings">
    <div class="form-group mt-5">
        <?php
        printf('<label for="jira_endpoint">%s</label>', __('Endpoint', $this->plugin_name));
        printf('<input type="text" class="form-control" name="jira_endpoint" id="jira_endpoint" placeholder="%s" value="%s">', __('https://yourcompanyname.atlassian.com', $this->plugin_name), !empty($endpoint_data) ? $endpoint_data['endpoint'] : '');
        ?>
    </div>
    <div class="form-group">
        <?php
        printf('<label for="jira_apikey">%s</label>', __('Authentication', $this->plugin_name));
        printf('<input type="text" class="form-control" name="jira_apikey" id="jira_apikey" placeholder="%s" value="%s">', __('email@adress.com:APITOKEN', $this->plugin_name), !empty($config) ? $config->apikey : '');
        ?>
    </div>
    <div class="form-group">
        <?php
        printf('<label for="jira_project">%s</label>', __('Project ID', $this->plugin_name));
        printf('<input type="text" class="form-control" name="jira_project" id="jira_project" placeholder="%s" value="%s">', __('ABC', $this->plugin_name), !empty($endpoint_data) ? $endpoint_data['project'] : '');
        ?>
    </div>
</form>
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

$field_mapping = !empty($config) ? json_decode($config->field_mapping, true) : array();
if (empty($field_mapping)) {
    $field_mapping = array();
}
$endpoint_data = !empty($config) && property_exists($config, 'endpoint') ? json_decode($config->endpoint, true) : array();
$campaign = AppQ_Integration_Center_Admin::get_campaign($campaign_id);
?>

<div class="settings-group">
    <?php
    $this->partial('settings/current-setup', array(
        'config' => $config,
        'endpoint_data' => $endpoint_data,
        'campaign' => $campaign
    ));
    ?>
</div>
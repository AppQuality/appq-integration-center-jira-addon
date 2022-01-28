<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/AppQuality/
 * @since      1.0.0
 *
 * @package    Appq_Integration_Center_Jira_Addon
 * @subpackage Appq_Integration_Center_Jira_Addon/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Appq_Integration_Center_Jira_Addon
 * @subpackage Appq_Integration_Center_Jira_Addon/includes
 * @author     Davide Bizzi <davide.bizzi@app-quality.com>
 */
class Appq_Integration_Center_Jira_Addon_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$error = false;
		
		if (!is_plugin_active('appq-integration-center/appq-integration-center.php'))
		{
			if (!$error)
			{
				$error = array();
			}
			$error[] = "Integration Center main plugin is not active";
		}
		
		
		
		
		if ($error) 
		{
			die('Plugin NOT activated: ' . implode(', ',$error));
		}
	}

}

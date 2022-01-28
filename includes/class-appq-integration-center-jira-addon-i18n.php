<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/AppQuality/
 * @since      1.0.0
 *
 * @package    Appq_Integration_Center_Jira_Addon
 * @subpackage Appq_Integration_Center_Jira_Addon/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Appq_Integration_Center_Jira_Addon
 * @subpackage Appq_Integration_Center_Jira_Addon/includes
 * @author     Davide Bizzi <davide.bizzi@app-quality.com>
 */
class Appq_Integration_Center_Jira_Addon_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'appq-integration-center-jira-addon',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

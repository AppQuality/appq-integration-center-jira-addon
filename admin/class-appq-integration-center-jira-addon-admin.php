<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/AppQuality/
 * @since      1.0.0
 *
 * @package    Appq_Integration_Center_Jira_Addon
 * @subpackage Appq_Integration_Center_Jira_Addon/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Appq_Integration_Center_Jira_Addon
 * @subpackage Appq_Integration_Center_Jira_Addon/admin
 * @author     Davide Bizzi <davide.bizzi@app-quality.com>
 */
class Appq_Integration_Center_Jira_Addon_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	public $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->integration = array(
			'slug' => 'jira',
			'name' => 'Jira'
		);
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook)
	{
		if (strpos($hook, 'integration-center') !== false) {
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/appq-integration-center-jira-addon-admin.css', array(), $this->version, 'all');
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook)
	{
		if (strpos($hook, 'integration-center') !== false) {
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/dist/appq-integration-center-jira-addon.min.js', array('jquery','wp-util'), $this->version, false);
            wp_set_script_translations($this->plugin_name, 'appq-integration-center-jira-addon', APPQ_INTEGRATION_CENTER_JIRA_PATH . 'languages');
			wp_localize_script($this->plugin_name, 'custom_object', array(
				'ajax_url' => admin_url('admin-ajax.php')
			));
		}
	}

	/**
	 * Register Jira integration type
	 *
	 * @since    1.0.0
	 */
	public function register_type($integrations)
	{
		$integrations[] = array_merge(
			$this->integration,
			array(
				'class' => $this
			)
		);
		return $integrations;
	}

	public function get_settings($campaign, $template_name = 'settings')
	{
		if (!in_array($template_name, ['tracker-settings', 'fields-settings'])) return;
		global $wpdb;
		$config = $wpdb->get_row(
			$wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'appq_integration_center_config WHERE campaign_id = %d AND integration = %s', $campaign->id, $this->integration['slug'])
		);
		$this->partial($template_name, [
			'config' => $config,
			'campaign_id' => $campaign->id
		]);
	}

	// public function main_settings($campaign)
	// {
	// 	global $wpdb;
	// 	$config = $wpdb->get_row(
	// 		$wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'appq_integration_center_config WHERE campaign_id = %d AND integration = %s', $campaign->id, $this->integration['slug'])
	// 	);
	// 	$this->partial('main-settings', array(
	// 		'config' => $config,
	// 		'campaign_id' => $campaign->id
	// 	));
	// }

	// public function full_settings($campaign)
	// {
	// 	global $wpdb;
	// 	$config = $wpdb->get_row(
	// 		$wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'appq_integration_center_config WHERE campaign_id = %d AND integration = %s', $campaign->id, $this->integration['slug'])
	// 	);
	// 	$this->partial('full-settings', array(
	// 		'config' => $config,
	// 		'campaign_id' => $campaign->id
	// 	));
	// }

	// public function settings($campaign)
	// {
	// 	global $wpdb;
	// 	$config = $wpdb->get_row(
	// 		$wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'appq_integration_center_config WHERE campaign_id = %d AND integration = %s', $campaign->id, $this->integration['slug'])
	// 	);
	// 	$this->partial('settings', array(
	// 		'config' => $config,
	// 		'campaign_id' => $campaign->id
	// 	));
	// }

	/** 
	 * Return admin partial path
	 * @var $slug
	 */
	public function get_partial($slug)
	{
		return $this->plugin_name . '/admin/partials/' . $this->plugin_name . '-admin-' . $slug . '.php';
	}
	/** 
	 * Include admin partial
	 * @var $slug
	 */
	public function partial($slug, $variables = false)
	{
		if (is_array($variables)) {
			foreach ($variables as $key => $value) {
				${$key} = $value;
			}
		}
		include(WP_PLUGIN_DIR . '/' . $this->get_partial($slug));
	}
	
	
	public function current_setup( $campaign = null )
	{
		$this->partial( 'settings/current-setup', [ 'campaign' => $campaign ] );
	}

}

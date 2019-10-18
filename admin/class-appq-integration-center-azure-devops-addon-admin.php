<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://bitbucket.org/%7B1c7dab51-4872-4f3e-96ac-11f21c44fd4b%7D/
 * @since      1.0.0
 *
 * @package    Appq_Integration_Center_Azure_Devops_Addon
 * @subpackage Appq_Integration_Center_Azure_Devops_Addon/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Appq_Integration_Center_Azure_Devops_Addon
 * @subpackage Appq_Integration_Center_Azure_Devops_Addon/admin
 * @author     Davide Bizzi <davide.bizzi@app-quality.com>
 */
class Appq_Integration_Center_Azure_Devops_Addon_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

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
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}


	/**
	 * Register Azure DevOps integration type
	 *
	 * @since    1.0.0
	 */
	public function register_type($integrations) {
		$integrations[] = 'azure-devops';
		return $integrations;
	}
	
	/** 
	 * Return admin partial path
	 * @var $slug
	 */
	public function get_partial($slug) {
		return $this->plugin_name . '/admin/partials/appq-integration-center-admin-'. $slug .'.php';
	}
	/** 
	 * Include admin partial
	 * @var $slug
	 */
	public function partial($slug) {
	   include(WP_PLUGIN_DIR . '/' . $this->get_partial($slug));
	}
}

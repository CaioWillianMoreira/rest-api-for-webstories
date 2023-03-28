<?php
	/**
	 * Plugin Name:     REST API For Webstories
	 * Plugin URI:      https://github.com/CaioWillianMoreira/rest-api-for-webstories
	 * Description:     Return registered webstories through REST API.
	 * Author:          Caio Willian Moreira Santos
	 * Author URI:      https://github.com/caiowillianmoreira
	 * Text Domain:     rest-api-for-webstories
	 * Domain Path:     /languages
	 * Version:         0.1.0
	 *
	 * @package         Rest_Api_For_Webstories
	 */

	defined('ABSPATH') || exit;

	define('REST_WEBSTORIES_PLUGIN_FILE', __FILE__);
	define('REST_WEBSTORIES_PLUGIN_PATH', untrailingslashit(plugin_dir_path(REST_WEBSTORIES_PLUGIN_FILE)));
	define('REST_WEBSTORIES_PLUGIN_URL', untrailingslashit(plugins_url('/', REST_WEBSTORIES_PLUGIN_FILE)));

	if (file_exists(REST_WEBSTORIES_PLUGIN_PATH . '/vendor/autoload.php')) {
		require_once REST_WEBSTORIES_PLUGIN_PATH . '/vendor/autoload.php';
	}

	require_once REST_WEBSTORIES_PLUGIN_PATH . '/includes/Plugin.php';

	if (class_exists('Plugin')) {
		function REST_WEBSTORIES() {
			return Plugin::getInstance();
		}

		add_action('plugins_loaded', array(REST_WEBSTORIES(), 'init'));

		// activation
		register_activation_hook(REST_WEBSTORIES_PLUGIN_FILE, array(REST_WEBSTORIES(), 'activate'));

		// deactivation
		register_deactivation_hook(REST_WEBSTORIES_PLUGIN_FILE, array(REST_WEBSTORIES(), 'deactivate'));
	}
?>
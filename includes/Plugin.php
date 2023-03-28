<?php
use REST_WEBSTORIES\Includes\Activate;
use REST_WEBSTORIES\Includes\Deactivate;
use REST_WEBSTORIES\Includes\Restwebstories;

defined('ABSPATH') || exit;
final class Plugin
{
	private static $_instance = null;
	protected function __construct()
	{
	}
	protected function __clone()
	{
	}
	protected function __wakeup()
	{
	}

	public static function getInstance(): ?Plugin
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function init()
	{
		$webstories = new Restwebstories();
		add_action('rest_api_init', array($webstories, 'register_routes'));
	}

	public function activate()
	{
		Activate::activate();
	}

	public function deactivate()
	{
		Deactivate::deactivate();
	}
}
?>

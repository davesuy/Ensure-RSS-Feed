<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/davesuy
 * @since      1.0.0
 *
 * @package    Ensure_Rss_Feed
 * @subpackage Ensure_Rss_Feed/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ensure_Rss_Feed
 * @subpackage Ensure_Rss_Feed/includes
 * @author     Dave Ramirez <davesuywebmaster@gmail.com>
 */
class Ensure_Rss_Feed_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ensure-rss-feed',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       presstigers.com
 * @since      1.0.0
 *
 * @package    Pt_Us_Zip_Codes
 * @subpackage Pt_Us_Zip_Codes/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Pt_Us_Zip_Codes
 * @subpackage Pt_Us_Zip_Codes/includes
 * @author     Presstigers <support@presstigers.com>
 */
class Pt_Us_Zip_Codes_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pt-us-zip-codes',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

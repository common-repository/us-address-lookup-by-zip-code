<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              presstigers.com
 * @since             1.0.0
 * @package           Pt_Us_Zip_Codes
 *
 * @wordpress-plugin
 * Plugin Name:       US Address Lookup by Zip Code
 * Plugin URI:        https://wordpress.org/plugins/us-address-lookup-by-zip-code/
 * Description:       This plugin is developed to auto fill the address and related fields like city, state and zip by putting zip code. Plugin is compatible with Contact form 7, Ninja Forms, Gravity Forms and Formidable Forms
 * Version:           1.0.2
 * Author:            PressTigers
 * Author URI:        https://presstigers.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       usz
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PT_US_ZIP_CODES_VERSION', '1.0.2' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pt-us-zip-codes-activator.php
 */
function activate_pt_us_zip_codes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pt-us-zip-codes-activator.php';
	Pt_Us_Zip_Codes_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pt-us-zip-codes-deactivator.php
 */
function deactivate_pt_us_zip_codes() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pt-us-zip-codes-deactivator.php';
	Pt_Us_Zip_Codes_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pt_us_zip_codes' );
register_deactivation_hook( __FILE__, 'deactivate_pt_us_zip_codes' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pt-us-zip-codes.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pt_us_zip_codes() {

	$plugin = new Pt_Us_Zip_Codes();
	$plugin->run();

}
run_pt_us_zip_codes();
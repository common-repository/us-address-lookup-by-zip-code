<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       presstigers.com
 * @since      1.0.0
 *
 * @package    Pt_Us_Zip_Codes
 * @subpackage Pt_Us_Zip_Codes/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pt_Us_Zip_Codes
 * @subpackage Pt_Us_Zip_Codes/public
 * @author     Presstigers <support@presstigers.com>
 */
class Pt_Us_Zip_Codes_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    		The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		/**
		 * Front-end user request to get the results
		 */
		add_action( "wp_ajax_nopriv_pt_address_search",  array( $this,"address_search" ) );
        add_action( "wp_ajax_pt_address_search", array( $this, "address_search" ) );
	}

	public function address_search(){
        $val = sanitize_text_field( $_REQUEST['val'] );
        $formtype = sanitize_text_field( $_REQUEST['form_type'] );
        global $wpdb;
       if($formtype == 01){
          if( get_option( "usz_integration_cf7") == 'on' ){
               $res = $wpdb->get_results( "SELECT * from `wp_zip_codes` WHERE zip LIKE '$val%' LIMIT 8" );
               wp_send_json_success($res);
          }
        }
       if($formtype == 02){
          if( get_option( "usz_integration_ninja") == 'on' ){
               $res = $wpdb->get_results( "SELECT * from `wp_zip_codes` WHERE zip LIKE '$val%' LIMIT 8" );
               wp_send_json_success($res);
          }
        }
       if($formtype == 03){
          if( get_option( "usz_integration_gravity") == 'on' ){
               $res = $wpdb->get_results( "SELECT * from `wp_zip_codes` WHERE zip LIKE '$val%' LIMIT 8" );
               wp_send_json_success($res);
          }
        }
       if($formtype == 04){
          if( get_option( "usz_integration_formidable") == 'on' ){
               $res = $wpdb->get_results( "SELECT * from `wp_zip_codes` WHERE zip LIKE '$val%' LIMIT 8" );
               wp_send_json_success($res);
          }
        }
	}

	private function validate_ajax_values( $value ){
		$value = esc_html( $value );
		return $value;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * Enqueue the main style sheet for the front-end
		 * @since	1.0.0
		 * @author	Presstigers
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pt-us-zip-codes-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * Enqueue the main js file for the front-end
		 * @since	1.0.0
		 * @author	Presstigers
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pt-us-zip-codes-public.js', array( 'jquery' ), $this->version, true );

		/**
		 * Enqueue jquery autocomplete
		 */
		 wp_enqueue_script( 'jquery-ui-autocomplete' );

		// Check if CF7 integration in ON from US Zip codes admin settings
		if(get_option("usz_integration_cf7") == 'on'){
			wp_enqueue_script( $this->plugin_name.'_cf7', plugin_dir_url( __FILE__ ) . 'js/pt-us-zip-codes-cf7.js', array( 'jquery' ), $this->version, true );
			
			wp_localize_script( $this->plugin_name.'_cf7', 'usz_cf7', array(
				'admin_url'     => admin_url( 'admin-ajax.php' ),
			) );
		}

		// Check if Ninja Form integration in ON from US Zip codes admin settings
		if(get_option("usz_integration_ninja") == 'on'){
			wp_enqueue_script( $this->plugin_name.'_nf', plugin_dir_url( __FILE__ ) . 'js/pt-us-zip-codes-nf.js', array( 'jquery' ), $this->version, true );
			
			wp_localize_script( $this->plugin_name.'_nf', 'usz_nf', array(
				'admin_url'     => admin_url( 'admin-ajax.php' ),
			) );
		}


		// Check if Formidable integration in ON from US Zip codes admin settings
		if(get_option("usz_integration_formidable") == 'on'){
			wp_enqueue_script( $this->plugin_name.'_ff', plugin_dir_url( __FILE__ ) . 'js/pt-us-zip-codes-ff.js', array( 'jquery' ), $this->version, true );
			
			wp_localize_script( $this->plugin_name.'_ff', 'usz_ff', array(
				'admin_url'     => admin_url( 'admin-ajax.php' ),
			) );
		}

		// Check if Gravity integration in ON from US Zip codes admin settings
		if(get_option("usz_integration_gravity") == 'on'){
			wp_enqueue_script( $this->plugin_name.'_gf', plugin_dir_url( __FILE__ ) . 'js/pt-us-zip-codes-gf.js', array( 'jquery' ), $this->version, true );
			
			wp_localize_script( $this->plugin_name.'_gf', 'usz_gf', array(
				'admin_url'     => admin_url( 'admin-ajax.php' ),
			) );
		}

	}

}
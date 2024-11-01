<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.

 * @link       presstigers.com
 * @since      1.0.0
 *
 * @package    Pt_Us_Zip_Codes
 * @subpackage Pt_Us_Zip_Codes/admin
 * @author     Presstigers <support@presstigers.com>
 */
class Pt_Us_Zip_Codes_Admin {

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

		/**
		 * Loading the admin settings page
		 * @since	1.0.0
		 * @author	Presstigers
		 */
		$this->load_dependencies();
        add_action( "wp_ajax_configure_usz", array($this,"configure_plugin") );

	}

	/**
	 * Load dependencies
	 * @since	1.0.0
	 * @author	Presstigers
	 */
	private function load_dependencies(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pt-us-zip-codes-admin-settings.php';
		new Pt_Us_Zip_Codes_Admin_Settings;
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pt-us-zip-codes-cf7.php';
		$cf7 = new Pt_Us_Zip_Codes_cf7;
		add_action( 'wpcf7_admin_init', array($cf7, 'add_tag_generator') );

		/**
		 * Registering Ninja Form Fields
		 * @since	1.0.0
		 * @author	Presstigers
		 */
		$this->ninja_form();

		/**
		 * Registering Formidable Form Fields
		 * @since	1.0.0
		 * @author	Presstigers
		 */
		$this->formidable_form();

		/**
		 * Registering Gravity Form Fields
		 * @since	1.0.0
		 * @author	Presstigers
		 */
		$this->gravity_form();
	}

	/**
	 * Ninja Forms Integration core filter
	 * @since	1.0.0
	 * @author	Presstigers
	 */
	public function ninja_form(){
		if( get_option("usz_integration_ninja") == 'on' && class_exists("NF_Fields_Textbox") ){
			add_filter( 'ninja_forms_register_fields', array($this, 'ninja_form_field_registration'));
		}
	}
	/**
	 * All integrations related to Ninja forms
	 * @since	1.0.0
	 * @author	Presstigers
	 */
	public function ninja_form_field_registration( $action ){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/ninja-forms/class-pt-us-zip-codes-address.php';
		$action['usz_address'] = new Pt_Us_Zip_Codes_Public_NF_Address(); 
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/ninja-forms/class-pt-us-zip-codes-zip.php';
		$action['usz_zip'] = new Pt_Us_Zip_Codes_Public_NF_Zip();

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/ninja-forms/class-pt-us-zip-codes-city.php';
		$action['usz_city'] = new Pt_Us_Zip_Codes_Public_NF_City(); 

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/ninja-forms/class-pt-us-zip-codes-state.php';
		$action['usz_state'] = new Pt_Us_Zip_Codes_Public_NF_State(); 
		
		return $action;
	}
	/**
	 * All integrations related to Formidable forms
	 * @since	1.0.0
	 * @author	Presstigers
	 */
	public function formidable_form(){
		if( get_option("usz_integration_formidable") == 'on' ){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/formidable-forms/class-pt-us-zip-codes-address.php';

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/formidable-forms/class-pt-us-zip-codes-zip.php';

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/formidable-forms/class-pt-us-zip-codes-city.php';

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/formidable-forms/class-pt-us-zip-codes-state.php';
		}
	}

	/**
	 * All integrations related to gravity forms
	 * @since	1.0.0
	 * @author	Presstigers
	 */
	public function gravity_form(){
		if( get_option("usz_integration_gravity") == 'on' && class_exists('GF_Field_Text') ){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/gravity-forms/class-pt-us-zip-codes-address.php';

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/gravity-forms/class-pt-us-zip-codes-zip.php';

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/gravity-forms/class-pt-us-zip-codes-city.php';

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/gravity-forms/class-pt-us-zip-codes-state.php';
		}		
	}

	/**
	 * Plugin Database Configuration 
	 * Inserts all zip code records from csv to database
	 * @since	1.0.0
	 * @author	Presstigers
	 */
	public function configure_plugin(){
        ini_set( "memory_limit", "-1" );
        ini_set( 'max_execution_time', 0 );

        global $wpdb;
        require_once( ABSPATH.'wp-admin/includes/upgrade.php' );

        $table_name = $wpdb->prefix."zip_codes";

		if( get_option( 'usz_db_configured' ) ){
			wp_send_json_success( 'Database Already Configured', 200 );
		}
        if( $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name ) {
			update_option( 'usz_db_configured', 1 );
			wp_send_json_success( __( 'Data Updated Successfully', 'usz' ), 200 );
		}else{
            $sql_querey_to_create_tabel="CREATE TABLE `{$table_name}` (
            `zip` int(5) DEFAULT NULL,
            `lat`  varchar(27) DEFAULT NULL,
            `lng` decimal(9,5) DEFAULT NULL,
            `city` varchar(27) DEFAULT NULL,
            `state_id` varchar(2) DEFAULT NULL,
            `state_name` varchar(20) DEFAULT NULL
           ) ENGINE=InnoDB DEFAULT CHARSET=utf8";


            dbDelta( $sql_querey_to_create_tabel );
    
            $f_pointer=fopen( plugin_dir_path(dirname(__FILE__))."/uszips.csv", "r" ); // file pointer

            $csv_data = array();
       
            while ( !feof($f_pointer) ) {
                array_push($csv_data, fgetcsv($f_pointer));
            }
     
            $flag = 0;
            while ($flag < 33098) {
                $floatValue = floatval($csv_data[$flag][1]);
                $wpdb->insert($table_name, array(
                'zip' 			=> $csv_data[$flag][0],
                'lat' 			=> $csv_data[$flag][1],
                'lng' 			=> (double)$csv_data[$flag][2],
                'city' 			=> $csv_data[$flag][3],
                'state_id' 		=> $csv_data[$flag][4],
                'state_name' 	=> $csv_data[$flag][5]
            ));
                $flag++;
            }
		}
		
		update_option( 'usz_db_configured', 1 );

		wp_send_json_success( 'Database Configured', 200 );
     
	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * Enqueue the main style sheet for the dashboard
		 * @since	1.0.0
		 * @author	Presstigers
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pt-us-zip-codes-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * Enqueue the main js file for the dashboard
		 * @since	1.0.0
		 * @author	Presstigers
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pt-us-zip-codes-admin.js', array( 'jquery' ), $this->version, false );
		
		/**
		 * Passing the admin-ajax.php URL to the main js file for an ajax request
		 * @since	1.0.0
		 * @since	1.0.1	up_localize_script param 3 changed from string to array
		 * @author	Presstigers
		 */
		wp_localize_script( $this->plugin_name, 'ajaxurl', array(
			'url'		=> admin_url( 'admin-ajax.php' ),
		) );
	}

}
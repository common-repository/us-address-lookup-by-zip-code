<?php

/**
 * The file is responsible for handling the Ninja Forms plugin integration.
 *
 * Defines the  hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pt_Us_Zip_Codes
 * @subpackage Pt_Us_Zip_Codes/public
 * @author     Presstigers <support@presstigers.com>
 */
class Pt_Us_Zip_Codes_Public_NF_City extends NF_Fields_Textbox {
    protected $_name = 'usz_city';
    protected $_section = 'common'; // section in backend
    protected $_type = 'textbox'; // field type
    protected $_templates = 'textbox'; // template; it's possible to create custom field templates

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
        parent::__construct();

        $this->_nicename = __( 'US City', 'presstigers' );
    }

}
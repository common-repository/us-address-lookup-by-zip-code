<?php

/**
 * The file is responsible for handling the Formidable Forms plugin integration.
 *
 * Defines the  hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pt_Us_Zip_Codes
 * @subpackage Pt_Us_Zip_Codes/public
 * @author     Presstigers <support@presstigers.com>
 */
class Pt_Us_Zip_Codes_Public_FF_Address {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		add_filter('frm_available_fields', array($this, 'add_field'));
		add_filter('frm_before_field_created', array($this, 'field_defaults'));
		add_filter( 'frm_update_field_options', array($this, 'update_field_options'), 10, 3 );
		add_action('frm_form_fields', array($this, 'show'), 10, 3);
    }
    
    /**
     * This method is responsible for registering the field
     * This method will result in displaying the field in admin screen
     * @since   1.0.0
     * @author  Presstigers
     */
	public function add_field($fields){
		$fields['usz_address'] = __('US Address', 'usz');
		return $fields;
    }
    
    /**
     * This method is responsible for setting the default values of this field
     * @since   1.0.0
     * @author  Presstigers
     */
	public function field_defaults($field_data){
		if ( $field_data['type'] == 'usz_address' ) {
			$field_data['name'] = 'Address';

			$defaults = array(
                'max' => 50,
				'label1' => 'Address Label',
			);

			foreach ( $defaults as $k => $v ) {
				$field_data['field_options'][ $k ] = $v;
			}
		}

		return $field_data;
    }
    
    /**
     * This method is responsible to store/update the field data into the database
     * @since   1.0.0
     * @author  Presstigers
     */
    public function update_field_options( $field_options, $field, $values ) {
        if($field->type != 'usz_address')
            return $field_options;
            
        $defaults = array(
            'label1' => __('Draw It', 'formidable'),
            'label2' => __('Type It', 'formidable'),
            'label3' => __('Clear', 'formidable'),
        );
        
        foreach ($defaults as $opt => $default)
            $field_options[ $opt ] = isset( $values['field_options'][ $opt . '_' . $field->id ] ) ? $values['field_options'][ $opt . '_' . $field->id ] : $default;
            
        return $field_options;
	}
    
    /**
     * This method is responsible for displaying this field on the front-end
     * @since   1.0.0
     * @author  Presstigers
     */
	public function show( $field, $field_name, $atts ) {
        if ( $field['type'] != 'usz_address' ) {
            return;
        }
      $field['value'] = stripslashes_deep($field['value']);
    	?>
<input type="text" id="usz-address-field" name="<?php echo esc_attr( $field_name ) ?>"
    value="<?php echo esc_attr($field['value']) ?>" />
<?php
	}

}

new Pt_Us_Zip_Codes_Public_FF_Address;
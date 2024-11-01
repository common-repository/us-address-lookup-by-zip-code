<?php

/**
 * The file is responsible for handling the Gravity Forms plugin integration.
 *
 * Defines the custom input field to display in the form builder
 *
 * @package    Pt_Us_Zip_Codes
 * @subpackage Pt_Us_Zip_Codes/public
 * @author     Presstigers <support@presstigers.com>
 */
class Pt_Us_Zip_Codes_Admin_GF_Address extends GF_Field_Text {

    public $type = 'usz_address';

    public function get_form_editor_field_title() {
        return esc_attr__( 'US Address', 'usz' );
    }

    function get_form_editor_field_settings() {
        return array(
            'conditional_logic_field_setting',
            'prepopulate_field_setting',
            'error_message_setting',
            'label_setting',
            'label_placement_setting',
            'admin_label_setting',
            'size_setting',
            'rules_setting',
            'visibility_setting',
            'duplicate_setting',
            'default_value_setting',
            'placeholder_setting',
            'description_setting',
            'phone_format_setting',
            'css_class_setting',
        );
    }
    
    public function get_field_input( $form, $value = '', $entry = null ) {
        $form_id         = $form['id'];
        $is_entry_detail = $this->is_entry_detail();
        $id              = (int) $this->id;
		$required_attribute    = $this->isRequired ? 'aria-required="true"' : '';
        $aria_describedby      = $this->get_aria_describedby();

        $input  = '<div>';
        $input .= '<input type="text" name="input_'.$id.'" class="usz_field_item usz_address_field usz_field_'.$form_id.'" id="usz_address_field" '. $this->get_field_placeholder_attribute() .'  '. $required_attribute .'>'; 
        $input .= '</div>';     
        return $input;
    }


}
GF_Fields::register( new Pt_Us_Zip_Codes_Admin_GF_Address() );
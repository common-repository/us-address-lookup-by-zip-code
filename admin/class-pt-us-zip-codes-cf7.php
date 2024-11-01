<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * This class adds the contact form 7 related functionality 
 * and add the new tag (address) in cf7 create/edit screen
 *
 * @link       presstigers.com
 * @since      1.0.0
 * @package    Pt_Us_Zip_Codes
 * @subpackage Pt_Us_Zip_Codes/admin
 * @author     Presstigers <support@presstigers.com>
 */
class Pt_Us_Zip_Codes_cf7 {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
        add_action( 'wpcf7_init', array($this, 'register_form_tags') );
    }

    /**
     * Ads the shortcodes tag
     * @since   1.0.0
     * @author  Presstigers
     */
    public function register_form_tags(){
        wpcf7_add_form_tag(
            array( 'us_address', 'us_address*' ),
            array( $this, 'address_callback' ),
            array( 'name-attr' => true )
        );

        wpcf7_add_form_tag(
            array( 'us_zip_code', 'us_zip_code*' ),
            array( $this, 'zip_callback' ),
            array( 'name-attr' => true )
        );    
    
        wpcf7_add_form_tag(
            array( 'us_state' , 'us_state*' ),
            array( $this, 'state_callback' ),
            array( 'name-attr' => true )
        );
    

        wpcf7_add_form_tag(
            array( 'us_city', 'us_city*' ),
            array( $this, 'city_callback' ),
            array( 'name-attr' => true )
        );
        
    }

    public function address_callback( $obj ){
        $address_attributes = array(
            'id'            => 'usz_address_code',
            'type'          => 'text',
            'name'          => $obj->name,
            'list'          => 'ptSearchResults',
            'autocomplete'  => 'off'
        );

        $addr_field = sprintf(
            '<input %s /><datalist id="ptSearchResults"></datalist>',
            wpcf7_format_atts($address_attributes)

        );

        return  $addr_field;
    }

    public function zip_callback( $obj ){
        $zip_attributes = array(
            'id'        => 'usz_zip_code',
            'type' => 'text',
            'name'      => $obj->name,
            'autocomplete'  => 'off',
            'readonly'  => true
        );

        $zip_field = sprintf(
            '<input %s />',
            wpcf7_format_atts($zip_attributes)
        );

        return $zip_field;
    }

    public function city_callback( $obj ){
        $city_attributes = array(
            'id'        => 'usz_city_code',
            'type' => 'text',
            'name'      => $obj->name,
            'autocomplete'  => 'off',
            'readonly'  => true
        );

        $city_field = sprintf(
            '<input %s />',
            wpcf7_format_atts($city_attributes)
        );

        return  $city_field;
    }

    public function state_callback( $obj ){
        $state_attributes = array(
            'id'        => 'usz_state_code',
            'type' => 'text',
            'name'      => $obj->name,
            'autocomplete'  => 'off',
            'readonly'  => true
        );

        $state_field = sprintf(
            '<input %s />',
            wpcf7_format_atts($state_attributes)
        );

        return  $state_field;
    }
    
    /**
     * The add tag generators are responsible for generating shortcode generator dialog box for each field
     * @since   1.0.0
     * @author  Presstigers
     */
    public function add_tag_generator(){
        if (class_exists('WPCF7_TagGenerator')) {
			$tag_generator = WPCF7_TagGenerator::get_instance();
			
            if(( get_option( 'usz_integration_cf7' ) == 'on' ) ){
                $tag_generator->add( 'us_address', __( 'US Address', 'usz' ),array($this,'tag_generator_address') );
                $tag_generator->add( 'us_zip_code', __( 'US Zip Code', 'usz' ), array($this,'tag_generator_zip') );
			    $tag_generator->add( 'us_city', __( 'US City', 'usz' ), array($this,'tag_generator_city') );
                $tag_generator->add( 'us_state', __( 'US State', 'usz' ), array($this,'tag_generator_state') );

            }
		}
    }


    public function tag_generator_address( $contact_form, $args = '' ){
        $args = wp_parse_args( $args, array() );
        $type = 'us_address';
        $description = __( "Generate a form-tag for a US Zip codes field.", 'usz' );
?>
<div class="control-box">
    <fieldset>
        <legend><?php echo sprintf( esc_html( $description ) ); ?></legend>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php echo esc_html( __( 'Field type', 'usz' ) ); ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <?php echo esc_html( __( 'Field type', 'usz' ) ); ?></legend>
                            <label><input type="checkbox" name="required" />
                                <?php echo esc_html( __( 'Required field', 'usz' ) ); ?></label>
                        </fieldset>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label
                            for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'usz' ) ); ?></label>
                    </th>
                    <td><input type="text" name="name" class="tg-name oneline"
                            id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
                </tr>

                <tr>
                    <th scope="row"><label
                            for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'usz' ) ); ?></label>
                    </th>
                    <td><input type="text" name="class" class="classvalue oneline option"
                            id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
                </tr>

            </tbody>
        </table>
    </fieldset>
</div>

<div class="insert-box">
    <input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

    <div class="submitbox">
        <input type="button" class="button button-primary insert-tag"
            value="<?php echo esc_attr( __( 'Insert Tag', 'usz' ) ); ?>" />
    </div>

    <br class="clear" />

</div>
<?php

    }
    public function tag_generator_zip( $contact_form, $args = '' ){
        $args = wp_parse_args( $args, array() );
        $type = 'us_zip_code';
        $description = __( "Generate a form-tag for a US Zip codes field.", 'usz' );
?>
<div class="control-box">
    <fieldset>
        <legend><?php echo sprintf( esc_html( $description ) ); ?></legend>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php echo esc_html( __( 'Field type', 'usz' ) ); ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <?php echo esc_html( __( 'Field type', 'usz' ) ); ?></legend>
                            <label><input type="checkbox" name="required" />
                                <?php echo esc_html( __( 'Required field', 'usz' ) ); ?></label>
                        </fieldset>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label
                            for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'usz' ) ); ?></label>
                    </th>
                    <td><input type="text" name="name" class="tg-name oneline"
                            id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
                </tr>

                <tr>
                    <th scope="row"><label
                            for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'usz' ) ); ?></label>
                    </th>
                    <td><input type="text" name="class" class="classvalue oneline option"
                            id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
                </tr>

            </tbody>
        </table>
    </fieldset>
</div>

<div class="insert-box">
    <input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

    <div class="submitbox">
        <input type="button" class="button button-primary insert-tag"
            value="<?php echo esc_attr( __( 'Insert Tag', 'usz' ) ); ?>" />
    </div>

    <br class="clear" />

</div>
<?php

    }
    public function tag_generator_city( $contact_form, $args = '' ){
        $args = wp_parse_args( $args, array() );
        $type = 'us_city';
        $description = __( "Generate a form-tag for a US Zip codes field.", 'usz' );
?>
<div class="control-box">
    <fieldset>
        <legend><?php echo sprintf( esc_html( $description ) ); ?></legend>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php echo esc_html( __( 'Field type', 'usz' ) ); ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <?php echo esc_html( __( 'Field type', 'usz' ) ); ?></legend>
                            <label><input type="checkbox" name="required" />
                                <?php echo esc_html( __( 'Required field', 'usz' ) ); ?></label>
                        </fieldset>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label
                            for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'usz' ) ); ?></label>
                    </th>
                    <td><input type="text" name="name" class="tg-name oneline"
                            id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
                </tr>

                <tr>
                    <th scope="row"><label
                            for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'usz' ) ); ?></label>
                    </th>
                    <td><input type="text" name="class" class="classvalue oneline option"
                            id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
                </tr>

            </tbody>
        </table>
    </fieldset>
</div>

<div class="insert-box">
    <input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

    <div class="submitbox">
        <input type="button" class="button button-primary insert-tag"
            value="<?php echo esc_attr( __( 'Insert Tag', 'usz' ) ); ?>" />
    </div>

    <br class="clear" />

</div>
<?php

    }
    public function tag_generator_state( $contact_form, $args = '' ){
        $args = wp_parse_args( $args, array() );
        $type = 'us_state';
        $description = __( "Generate a form-tag for a US Zip codes field.", 'usz' );
?>
<div class="control-box">
    <fieldset>
        <legend><?php echo sprintf( esc_html( $description ) ); ?></legend>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php echo esc_html( __( 'Field type', 'usz' ) ); ?></th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <?php echo esc_html( __( 'Field type', 'usz' ) ); ?></legend>
                            <label><input type="checkbox" name="required" />
                                <?php echo esc_html( __( 'Required field', 'usz' ) ); ?></label>
                        </fieldset>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label
                            for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'usz' ) ); ?></label>
                    </th>
                    <td><input type="text" name="name" class="tg-name oneline"
                            id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
                </tr>

                <tr>
                    <th scope="row"><label
                            for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'usz' ) ); ?></label>
                    </th>
                    <td><input type="text" name="class" class="classvalue oneline option"
                            id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
                </tr>

            </tbody>
        </table>
    </fieldset>
</div>

<div class="insert-box">
    <input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

    <div class="submitbox">
        <input type="button" class="button button-primary insert-tag"
            value="<?php echo esc_attr( __( 'Insert Tag', 'usz' ) ); ?>" />
    </div>

    <br class="clear" />

</div>
<?php

    }


	/**
	 * Modify contact form properties for signatures
	 *
	 * @since    4.0.0
	 */
	public function contact_form_properties( $properties, $instance ) 
	{
	   	if (! is_array($properties)){
	   		return $properties;
	   	}

	   	if (!class_exists('WPCF7_FormTagsManager')){
	   		return $properties;
	   	}

	   	// We need to know if the current form has a signature field
	   	$manager = WPCF7_FormTagsManager::get_instance();
	   	$scanned = $manager->scan( $properties['form'] );

	   	if ( empty( $scanned ) )
                return $properties;

		for ( $i = 0, $size = count( $scanned ); $i < $size; $i++ ) {
			// if ( !empty( $scanned[$i]) && $scanned[$i]['basetype'] == "usz_address_code"){

			   	$settings = $properties['additional_settings'];

			// }
        }


	    return $properties;
	}

}
<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * This class adds the plugin settings page in admin screen under Zip Codes tab
 *
 * @link       presstigers.com
 * @since      1.0.0
 * @package    Pt_Us_Zip_Codes
 * @subpackage Pt_Us_Zip_Codes/admin
 * @author     Presstigers <support@presstigers.com>
 */
class Pt_Us_Zip_Codes_Admin_Settings {

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'zip_codes_page'));
        add_action('admin_init', array($this, 'register_usz_settings'));
    }

    /**
     * Adds the top menu page
     * page: US Zip Codes
     * @since	1.0.0
     * @author	Presstigers
     */
    public function zip_codes_page() {
        add_menu_page(
                __('US Address Lookup by Zip Code', 'usz'), __('US Address Lookup', 'usz'), 'manage_options', 'us-zip-codes', array($this, 'zip_codes_page_content')
        );
    }

    public function zip_codes_page_content() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <?php
            /**
             * Display the tabs
             */
            $_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : "";
            $this->tabs_nav($_tab);

            /**
             * Display tab content
             */
            $this->tab_content($_tab);
            ?>

        </div>
        <?php
    }

    public function tabs_nav($current = 'general') {

        $current = empty($current) ? 'general' : $current;

        $tabs = array(
            'general' => __('General', 'usz'),
            'cf7' => __('Contact Form 7', 'usz'),
            'ninja_forms' => __('Ninja Forms', 'usz'),
            'gravity_forms' => __('Gravity Forms', 'usz'),
            'formidable_forms' => __('Formidable Forms', 'usz'),
        );

        echo '<nav class="usz-tab-wrapper">';

        foreach ($tabs as $tab => $name) {
            $class = ( $tab == $current ) ? 'nav-tab-active' : '';
            echo '<a href="?page=us-zip-codes&tab=' . $tab . '" class="' . $class . '">' . $name . '</a>';
        }

        echo '</nav>';
    }

    public function tab_content($current = 'general') {

        /**
         * WP form removed from the switch statement
         */
        switch ($current) {
            case 'cf7':
                $this->cf7_content();
                break;
            case 'ninja_forms':
                $this->ninja_forms_content();
                break;
            case 'gravity_forms':
                $this->gravity_forms_content();
                break;
            case 'formidable_forms':
                $this->formidable_forms_content();
                break;
            default:
                $this->general_content();
                break;
        }
    }

    /**
     * General options tab content
     * @since	1.0.0
     * @author	Presstigers
     */
    public function general_content() {
        ?>
        <form>
            <table class="form-table" role="presentation">
                <tr scope="row">
                    <th>
                        <label for="usz-config-database">
                            <?php _e('Zip Code Plugin', 'usz') ?>
                        </label>
                    </th>
                    <td>
                        <?php
                        //pointer-events: none;
                        $config_check = get_option('usz_db_configured') ? " disabled " : "";
                        ?>
                        <a href="#" class="button button-primary <?php echo $config_check; ?>"
                           id="usz-config-database"><?php _e('Configure Database', 'usz') ?></a>
                        <p><?php __('Hold on! It takes a few minutes', 'usz') ?></p>
                        <p id="usz-configure-message"></p>

                    </td>
                </tr>
                <tr scope="row">
                    <?php
                    echo sprintf(
                            '<th>%s</th>', __('Requirements', 'usz')
                    );
                    echo sprintf(
                            '<td>%s</td>', __('Before using this plugin, you need to configure it by clicking Configure Database button.', 'usz')
                    );
                    ?>
                </tr>

            </table>
        </form>
        <?php
    }

    /**
     * Contact Form 7 options tab content
     * @since	1.0.0
     * @author	Presstigers
     */
    public function cf7_content() {
        ?>
        <form action="options.php" method="POST">
            <?php
            settings_fields('usz_cf7_settings');
            do_settings_sections('usz_cf7_settings');
            $cf7_check = ( get_option('usz_integration_cf7') == 'on' ) ? 'checked' : '';
            ?>
            <table class="form-table" role="presentation">
                <tr scope="row">
                    <th>
                        <label for="usz_integration_cf7">
                            <?php _e('Integration with CF7', 'usz') ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" name="usz_integration_cf7" class="" id="usz_integration_cf7"
                               <?php echo $cf7_check ?>>
                               <?php
                               echo sprintf(
                                       '<p><em>%s</em></p>', __('Check if you need to enable integration with CF7', 'usz')
                               );
                               ?>
                    </td>
                </tr>
                <tr>
                    <?php
                    echo sprintf(
                            '<th>%s</th>', __('Guide lines', 'usz')
                    );
                    echo sprintf(
                            '<td>%s</td>', __('<p>You must enable/check "Integration with CF7" before using it.<br/>
                You will get the shortcodes generators in contact form 7 edit screen to add these into you form.<br/>
                <b>Important: </b>Address field is always required when using this plugin.</p>', 'usz')
                    );
                    ?>
                </tr>

            </table>
            <?php submit_button(); ?>
            <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . "images/cf7.png"); ?>" alt="CF7 Forms Guide"
                 class="guide-image">
        </form>

        <?php
    }

    /**
     * Ninja Forms options tab content
     * @since	1.0.0
     * @author	Presstigers
     */
    public function ninja_forms_content() {
        ?>
        <form action="options.php" method="POST">
            <?php
            settings_fields('usz_ninja_settings');
            do_settings_sections('usz_ninja_settings');
            $ninja_check = ( get_option('usz_integration_ninja') == 'on' ) ? 'checked' : '';
            ?>
            <table class="form-table" role="presentation">
                <tr scope="row">
                    <th>
                        <label for="usz_integration_ninja">
                            <?php _e('Integration with Ninja Forms', 'usz') ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" name="usz_integration_ninja" class="" id="usz_integration_ninja"
                               <?php echo $ninja_check ?>>
                               <?php echo sprintf('<p><em>%s</em></p>', __('Check if you need to enable integration with Ninja Forms', 'usz')); ?>

                    </td>
                </tr>
                <tr>
                    <?php
                    echo sprintf(
                            '<th>%s</th>', __('Guide lines', 'usz')
                    );
                    echo sprintf(
                            '<td>%s</td>', __('<p>You must enable/check "Integration with Ninja Forms" before using it.<br/>
                New custom field buttons will be added to Ninja form builder page in "Common Fields" panel.<br/>You can drag and drop these fields like any other fields to build the form.<br/><b>Important: </b>Address field is always required when using this plugin.</p>', 'usz'));
                    ?>
                </tr>
            </table>
            <?php submit_button(); ?>
            <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . "images/ninja.png"); ?>" alt="Ninja Forms Guide"
                 class="guide-image">

        </form>

        <?php
    }

    /**
     * Gravity Forms options tab content
     * @since	1.0.0
     * @author	Presstigers
     */
    public function gravity_forms_content() {
        ?>
        <form action="options.php" method="POST">
            <?php
            settings_fields('usz_gravity_settings');
            do_settings_sections('usz_gravity_settings');
            $gravity_check = ( get_option('usz_integration_gravity') == 'on' ) ? 'checked' : '';
            ?>
            <table class="form-table" role="presentation">
                <tr scope="row">
                    <th>
                        <label for="usz_integration_gravity">
                            <?php _e('Integration with Gravity Forms', 'usz') ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" name="usz_integration_gravity" class="" id="usz_integration_gravity"
                               <?php echo $gravity_check ?>>
                               <?php echo sprintf('<p><em>%s</em></p>', __('Check if you need to enable integration with Gravity Forms', 'usz')); ?>

                    </td>
                </tr>
                <tr>
                    <?php
                    echo sprintf(
                            '<th>%s</th>', __('Guide lines', 'usz')
                    );
                    echo sprintf(
                            '<td>%s</td>', __('<p>You must enable/check "Integration with Gravity Forms" before using it.<br/>
                New custom field buttons will be added to Gravity Form builder page in "Standard Fields" panel.<br/>
                You can drag and drop these fields like any other fields to build the form.<br/>
                <b>Important: </b>Address field is always required when using this plugin.</p>', 'usz')
                    );
                    ?>
                </tr>
            </table>
            <?php submit_button(); ?>
            <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . "images/gravity.png"); ?>" alt="Gravity Forms Guide"
                 class="guide-image">

        </form>

        <?php
    }

    /**
     * Formidable Forms options tab content
     * @since	1.0.0
     * @author	Presstigers
     */
    public function formidable_forms_content() {
        ?>
        <form action="options.php" method="POST">
            <?php
            settings_fields('usz_formidable_settings');
            do_settings_sections('usz_formidable_settings');
            $formidable_check = ( get_option('usz_integration_formidable') == 'on' ) ? 'checked' : '';
            ?>
            <table class="form-table" role="presentation">
                <tr scope="row">
                    <th>
                        <label for="usz_integration_formidable">
                            <?php _e('Integration with Formidable Forms', 'usz') ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" name="usz_integration_formidable" class="" id="usz_integration_formidable"
                               <?php echo $formidable_check ?>>
                               <?php echo sprintf('<p><em>%s</em></p>', __('Check if you need to enable integration with Formidable Forms', 'usz')); ?>

                    </td>
                </tr>
                <tr>
                    <?php
                    echo sprintf(
                            '<th>%s</th>', __('Guide Lines', 'usz')
                    );
                    echo sprintf(
                            '<td>%s</td>', __('<p>You must enable/check "Integration with Formidable Forms" before using it.<br/>
                New custom field buttons will be added to Formidable Form builder page in "Add Fields" panel.<br/>
                You can drag and drop these fields like any other fields to build the form.<br/>
                <b>Important: </b>Address field is always required when using this plugin.</p>', 'usz')
                    );
                    ?>
                </tr>
            </table>
            <?php submit_button(); ?>
            <img src="<?php echo esc_url(plugin_dir_url(__FILE__) . "images/formidable.png"); ?>" alt="Formidable Forms Guide"
                 class="guide-image">

        </form>

        <?php
    }

    /**
     * Registering the Form setting options
     * @since	1.0.0
     * @author	Presstigers
     */
    public function register_usz_settings() {
        register_setting('usz_cf7_settings', 'usz_integration_cf7');

        register_setting('usz_ninja_settings', 'usz_integration_ninja');

        register_setting('usz_gravity_settings', 'usz_integration_gravity');

        register_setting('usz_formidable_settings', 'usz_integration_formidable');
    }

}

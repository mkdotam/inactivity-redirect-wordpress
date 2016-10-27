<?php 

class MkInactivityRedirectSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Inactivity Redirect Settings', 
            'manage_options', 
            'mk-ir-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'mk_ir_option_name' );
        ?>
        <div class="wrap">
            <h1>Inactivity Redirect Settings</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'mk_ir_option_group' );
                do_settings_sections( 'mk-ir-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'mk_ir_option_group', // Option group
            'mk_ir_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Inactivity Redirect Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'mk-ir-setting-admin' // Page
        );  

        add_settings_field(
            'timeout', // ID
            'Timeout', // Title 
            array( $this, 'timeout_callback' ), // Callback
            'mk-ir-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'redirect_url', 
            'Redirect URL', 
            array( $this, 'redirect_url_callback' ), 
            'mk-ir-setting-admin', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['timeout'] ) )
            $new_input['timeout'] = absint( $input['timeout'] );

        if( isset( $input['redirect_url'] ) )
            $new_input['redirect_url'] = sanitize_text_field( $input['redirect_url'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function timeout_callback()
    {
        printf(
            '<input type="text" id="timeout" name="mk_ir_option_name[timeout]" value="%s" />',
            isset( $this->options['timeout'] ) ? esc_attr( $this->options['timeout']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function redirect_url_callback()
    {
        printf(
            '<input type="text" id="redirect_url" name="mk_ir_option_name[redirect_url]" value="%s" />',
            isset( $this->options['redirect_url'] ) ? esc_attr( $this->options['redirect_url']) : ''
        );
    }
}


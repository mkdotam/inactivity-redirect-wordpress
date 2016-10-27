<?php
/**
 * @package MK Inactivity Redirect
 * @version 1.0
 */
/*
Plugin Name: MK Inactivity Redirect
Plugin URI: https://github.com/mkdotam/inactivity-redirect-wordpress
Description: This plugin redirects user to the specified page after given period of inactivity (in seconds)
Author: Mikayel Ghazaryan
Version: 1.0
Author URI: http://mk.am/
*/

require_once plugin_dir_path(__FILE__) . '/MkInactivityRedirectSettingsPage.php';

if (is_admin()) {
  if (class_exists('MkInactivityRedirectSettingsPage')) {
    $my_settings_page = new MkInactivityRedirectSettingsPage();
  }
}

function mk_inactivity_scripts() {
  $options_got = get_option( 'mk_ir_option_name' );
  $options = [
    'redirect_url' => (isset($options_got['redirect_url'])) ? $options_got['redirect_url'] : "/",
    'timeout' => (isset($options_got['timeout'])) ? $options_got['timeout'] : 60,
  ];

  wp_register_script('mk_inactivity', plugin_dir_url(__FILE__) . 'inactivity.js', ['jquery'], 0, true);
  wp_enqueue_script( 'mk_inactivity' );
  wp_localize_script( 'mk_inactivity', 'options', $options);
}

add_action( 'wp_enqueue_scripts', 'mk_inactivity_scripts' );

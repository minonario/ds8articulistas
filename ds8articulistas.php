<?php
/**
 * @package DS8 articulistas
 */
/*
Plugin Name: DS8 Articulistas
Plugin URI: https://deseisaocho.com/
Description: FD <strong>Articulistas</strong>
Version: 1.0
Author: JLMA
Author URI: https://deseisaocho.com/wordpress-plugins/
License: GPLv2 or later
Text Domain: ds8articulistas
*/


if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'DS8ARTICULISTAS_VERSION', '3.4' );
define( 'DS8ARTICULISTAS_MINIMUM_WP_VERSION', '5.0' );
define( 'DS8_AUTHOR_BOX_ASSETS', plugins_url('/assets/', __FILE__));
define( 'DS8ARTICULISTAS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

register_activation_hook( __FILE__, array( 'DS8Articulistas', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'DS8Articulistas', 'plugin_deactivation' ) );

require_once DS8ARTICULISTAS_PLUGIN_DIR . '/includes/helpers.php';
require_once( DS8ARTICULISTAS_PLUGIN_DIR . 'class.ds8articulistas.php' );

//add_action( 'init', array( 'DS8Articulistas', 'init' ) );

global $simple_ds8_box;
$simple_ds8_box = DS8Articulistas::get_instance();

/*if ( is_admin() ) {
	require_once( DS8ARTICULISTAS__PLUGIN_DIR . 'class.ds8articulista-admin.php' );
	add_action( 'init', array( 'DS8Clasificado_Admin', 'init' ) );
}*/
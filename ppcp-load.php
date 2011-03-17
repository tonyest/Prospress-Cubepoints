<?php
/*
Plugin Name: Prospress Cubepoints
Plugin URI: http://prospress.org
Description: Add Cubepoints functionality to Prospress marketplaces
Author: tonyest
Version: 0.1.0
Author URI: http://prospress.org/
*/
/* array cp_modules for compatability with Cubepoints 3 module system */
// $cp_modules[] = array (
// 	name => 'Prospress Cubepoints',
// 	version => '0.1.0',
// 	url => 'http://prospress.org.au',
// 	description => 'Cubepoints module for Prospress auctions',
// 	api_version => '1.0 (Do not change)',
// 	author => 'Anthony Yin-Xiong Khoo',
// 	author_url => 'http://prospress.org.au',
// 	admin_function => 'ppcp_admin_menu',
// );

//define plugin constants
if( !defined( 'PPCP_PLUGIN_DIR' ) )
	define( 'PPCP_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . basename(dirname(__FILE__)) );
if( !defined( 'PPCP_PLUGIN_URL' ) )
	define( 'PPCP_PLUGIN_URL', WP_PLUGIN_URL . '/' . basename(dirname(__FILE__)) );

//define language library
load_plugin_textdomain( 'ppcp', PPCP_PLUGIN_DIR . '/languages', dirname( plugin_basename( __FILE__ ) ) . '/languages' );

/**
 * Load Module only if Prospress and Cubepoints plugins are active
 *
 * @package Prospress-Cubepoints
 * @since 0.1
 */
add_action('plugins_loaded', 'ppcp_load');
function ppcp_load(){

	if( function_exists( 'cp_module_register' ) && class_exists( 'PP_Market_System' ) ){
		cp_module_register(
							__('Prospress Cubepoints', 'ppcp'),
							'ppcp',
							'0.1.0',
							'tonyest',
							'http://prospress.org',
							'http://prospress.org',
							__('Add Cubepoints functionality to Prospress marketplaces', 'ppcp'),
							true
		);
		
		require_once(PPCP_PLUGIN_DIR.'/ppcp-admin-menu.php');
		require_once(PPCP_PLUGIN_DIR.'/functions/ppcp-templatetags.php');
		require_once(PPCP_PLUGIN_DIR.'/functions.php');

		add_action('init','ppcp_cubepoints_mode');
		add_action('admin_init','ppcp_admin_init' , 10);
		add_filter('pp_money_format','ppcp_currency_format');//add cubepoints format
		add_filter('pp_set_currency','ppcp_currency_type');
	}
	else {
		add_action( 'admin_notices', 'ppcp_activation_error' );	
	}
}

/**
 * Error message when mother plugins Prospress & Cubepoints are not activated
 *
 * @package Prospress-Cubepoints
 * @since 1.0
 */
function ppcp_activation_error(){
	echo "<div id='ppnp_activation_error' class='error fade'><p><strong>".__('Warning.', 'ppcp')."</strong> ";
	echo __( "Prospress-Cubepoints requires activation of both plugins Prospress version 1.1 or later & Cubepoints version 3 or later.", 'ppcp')."</div>";
}

/**
 * Check whether the plugin is active by checking the active_plugins list.
 * EDIT:copy of plugins.php function fails for unknown reason (needs further investigation this is a quick fix)
 * when solved best to use is_plugin_active();
 * @since 2.5.0
 *
 * @param string $plugin Base plugin path from plugins directory.
 * @return bool True, if in the active plugins list. False, not in the list.
 */
function ppcp_is_plugin_active( $plugin ) {
	return	in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
}

function ppcp_admin_init(){
	//register prospress cubepoints options
	register_setting( 'ppcp_options' , 'win_points' , 'ptsVal' );
	register_setting( 'ppcp_options' , 'sell_points' , 'ptsVal' );
	register_setting( 'ppcp_options' , 'bid_points' , 'ptsVal' );
	$message = "Prospress - Cubepoints mode:&nbsp;&nbsp;&nbsp;Payment Settings disabled. Auctions are now based on cubepoints instead of 'real' currency";
	if ( is_ppcp_mode() ) {
		add_settings_error( 'currency_type', 'ppcp_mode', $message, 'error' );
		settings_errors('ppcp_mode');
	} else {
		// add_action( 'admin_notices', 'ppcp_activation_error' );
		//check screen
		//settings_errors('settings_updated');
		//sticky error needs investigation
	}
}

?>
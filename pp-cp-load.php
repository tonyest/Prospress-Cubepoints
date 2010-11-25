<?php
/*
Plugin Name: Prospress Cubepoints
Plugin URI: http://prospress.org
Description: Add Cubepoints functionality to Prospress marketplaces
Author: Anthony Yin-Xiong Khoo, Prospress.org
Version: 1.0.0
Author URI: http://prospress.org/
*/
$cp_modules[] = array (
	name => 'Prospress Cubepoints',
	version => '1.0.0',
	url => 'http://prospress.org.au',
	description => 'Cubepoints module for Prospress auctions',
	api_version => '1.0 (Do not change)',
	author => 'Anthony Yin-Xiong Khoo',
	author_url => 'http://prospress.org.au',
	admin_function => 'pp_cp_admin_prospress',
);
if( !defined( 'PP_CP_PLUGIN_DIR' ) )
	define( 'PP_CP_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . basename(dirname(__FILE__)) );
if( !defined( 'PP_CP_PLUGIN_URL' ) )
	define( 'PP_CP_PLUGIN_URL', WP_PLUGIN_URL . '/' . basename(dirname(__FILE__)) );

load_plugin_textdomain( 'prospress', PP_CP_PLUGIN_DIR . '/languages', dirname( plugin_basename( __FILE__ ) ) . '/languages' );

function pp_cp_activate(){
	// Safely prevent activation on installations pre 3.0 or with php 4
	if ( !function_exists( 'register_post_status' ) || version_compare( PHP_VERSION, '5.0.0', '<' ) ) {
		deactivate_plugins( basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );
		if( !function_exists( 'register_post_status' ) )
			wp_die(__( "Sorry, but you can not run Prospress Cubepoints. It requires WordPress 3.0 or newer. Consider <a href='http://codex.wordpress.org/Updating_WordPress'>upgrading</a> your WordPress installation, it's worth the effort.<br/><a href=" . admin_url( 'plugins.php' ) . ">Return to Plugins Admin page &raquo;</a>"), 'prospress' );
		else
			wp_die(__( "Sorry, but you can not run Prospress Cubepoints. It requires PHP 5.0 or newer. Please <a href='http://www.php.net/manual/en/migration5.php'>migrate</a> your PHP installation to run Prospress Cubepoints.<br/><a href=" . admin_url( 'plugins.php' ) . ">Return to Plugins Admin page &raquo;</a>"), 'prospress' );
	}

	do_action( 'pp_cp_activation' );
}
register_activation_hook( __FILE__, 'pp_cp_activation' );

function pp_cp_deactivate(){
	do_action( 'pp_deactivation' );
}
//register_deactivation_hook( __FILE__, 'pp_deactivate' );

function pp_cp_uninstall(){
	do_action( 'pp_uninstall' ); // delete data Prospress creates upon uninstallation, never delete user generated data
}
//register_uninstall_hook( __FILE__, 'pp_uninstall' );

/**
 * Check whether the plugin is active by checking the active_plugins list.
 * EDIT:copy of plugins.php function fails for unknown reason (needs further investigation this is a quick fix)
 * when solved best to use is_plugin_active();
 * @since 2.5.0
 *
 * @param string $plugin Base plugin path from plugins directory.
 * @return bool True, if in the active plugins list. False, not in the list.
 */
function pp_cp_is_plugin_active( $plugin ) {
return	in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
}

/**
 * Load Module only if Prospress and Cubepoints plugins are active
 *
 * @package Prospress
 * @since 0.1
 */
if(pp_cp_is_plugin_active('cubepoints/cubepoints.php')&&pp_cp_is_plugin_active('Prospress/pp-load.php')){
	include_once(PP_CP_PLUGIN_DIR.'/pp-cp-admin-prospress.php');
	include_once(PP_CP_PLUGIN_DIR.'/functions/pp-cp-templatetags.php');
	include_once(PP_CP_PLUGIN_DIR.'/functions/pp-cp-cubepoints-mode.php');
	add_action('init','pp_cp_cubepoints_mode');
	add_option( 'pp_cp_win_pts' , 5 );
	add_option( 'pp_cp_sell_pts' , 5 );
	add_option( 'pp_cp_bid_pts' , 5) ;
	add_filter('pp_money_format','pp_cp_format_currency_type');//add cubepoints format
	add_filter('pp_set_currency','pp_cp_add_currency_type');
	add_option( 'pp_cp_cubepoints_mode' , 'enabled' );
}
else{
	//throw error;
}
add_action('admin_init','cp_scripts');

function cp_scripts(){
	wp_enqueue_script( 'pp-cp-admin-prospress.', PP_CP_PLUGIN_URL .'/js/pp-cp-admin.js');
	wp_enqueue_style('pp-cubepoints-sytle',PP_CP_PLUGIN_URL .'/css/pp-cubepoints-style.css');
}

?>
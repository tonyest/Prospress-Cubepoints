<?php
/*
Plugin Name: Prospress Cubepoints
Plugin URI: http://prospress.org
Description: Add Cubepoints functionality to Prospress marketplaces
Author: tonyest
Version: 0.1.0
Author URI: http://prospress.org/
*/

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
 * In Cubepoints-mode Prospress uses Cubepoints as currency.
 * Standard invoices and payments are disabled and points automatically deducted from
 * user totals.  Standard Prospress Cubepoints actions are disabled.
 *
 * @package Prospress-Cubepoints
 * @since 0.1
 */
add_action('plugins_loaded', 'ppcp_load');
function ppcp_load(){

	if( function_exists( 'cp_module_register' ) && class_exists( 'PP_Market_System' ) ){
		cp_module_register(
							__('Prospress Cubepoints', 'ppcp'),	//module
							'ppcp',	//id
							'0.1.0', //version
							'tonyest', //author
							'http://prospress.org', //author url
							'http://prospress.org', //plugin url
							__('Add Cubepoints functionality to Prospress marketplaces', 'ppcp'), //description
							true //can deactivate
		);
		
		require_once(PPCP_PLUGIN_DIR.'/ppcp-admin-menu.php');
		require_once(PPCP_PLUGIN_DIR.'/functions/bid-functions.php');
		require_once(PPCP_PLUGIN_DIR.'/functions/functions.php');
		
		if ( cp_module_activated('ppcp') ) {
			// add submenu to Cubepoints
			add_action('cp_admin_pages','ppcp_submenu_page');
			// hook cubepoints allocations to auction win
			add_action( 'post_completed', 'ppcp_win' );

			if ( is_ppcp_mode() ) {
				// avoid sending invoices as cubepoints are in-house
				remove_action( 'post_completed' , 'pp_generate_invoice' );
				// add options submenu
				add_action( 'admin_head' , 'ppcp_cubepoints_mode_menu' );
				// provide custom messages for Prospress-Cubepoints validation behaviour
				add_filter( 'bid_message','ppcp_validate_post' , 1 , 2 );
				// Standardise bid increments to whole numbers as compatible with Cubepoints systems
				add_filter( 'increment_bid_value' , 'ppcp_floor_bid_increment' , 1 , 1 );
				// format Cubepoints currency type in Prospress
				add_filter('pp_money_format','ppcp_currency_format');//add cubepoints format
			}
			// custom bid validation and bid cubepoints allocation
			add_filter( 'bid_pre_db_insert','ppcp_validate_bid' , 1 , 1 );
			
			// enqueue styles and register settings to admin pages
			add_action('admin_init','ppcp_admin_init' , 10);
			// add Cubepoints 'CPS' currency type to Prospress
			add_filter('pp_set_currency','ppcp_currency_type');
			
		} else {
			// Plugin is not inactive, clean up possible cross plugin options conflicts
			ppcp_deactivate();
		}
	}
	else {
		add_action( 'admin_notices', 'ppcp_activation_error' );	
	}
}

/**
 * Dispalay error message when mother plugins Prospress & Cubepoints are not activated
 *
 * @package Prospress-Cubepoints
 * @since 0.1
 */
function ppcp_activation_error(){
	echo "<div id='ppnp_activation_error' class='error fade'><p><strong>".__('Warning.', 'ppcp')."</strong> ";
	echo __( "Prospress-Cubepoints requires activation of both plugins Prospress version 1.1 or later & Cubepoints version 3 or later.", 'ppcp')."</div>";
}
/**
 *
 * Cleans up possible cross plugin options conflicts
 *
 * @package Prospress-Cubepoints
 * @since 0.1
 */
register_deactivation_hook( __FILE__, 'ppcp_deactivate' );
function ppcp_deactivate() {
	if ( 'CPS' == get_option('currency_type') ) {
		delete_option( 'currency_type' ); //remove CPS from active currency, Prospress will default to USD
		update_option( 'cp_module_activation_ppcp', false ); // de-activate Prospress-Cubepoints in Cubepoints Module system
	}
}
/**
 *
 * Cleans up options on uninstall
 *
 * @package Prospress-Cubepoints
 * @since 0.1
 */
register_uninstall_hook( __FILE__, 'ppcp_uninstall' ); 
function ppcp_uninstall() {
	
	delete_option( 'ppcp_win_points' );
	delete_option( 'ppcp_sell_points' );
	delete_option( 'ppcp_bid_points' );
	delete_option( 'cp_module_activation_ppcp' );
	
	if ( 'CPS' == get_option('currency_type') )
		delete_option( 'currency_type' );
		
}

?>
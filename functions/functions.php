<?php

/**
 * Conditional checks for the Cubepoints custom currency type.
 * This is the indicator of Prospress - Cubepoints mode.
 *
 * @package Prospress Cubepoints
 * @since 0.1
 *
 * @uses 
 */
function is_ppcp_mode() {
	 return ( 'CPS' == get_option('currency_type') )? true : false ;
}

/**
 * 
 * Sanitization filter for Prospress Cubepoints options
 *
 * @package Prospress Cubepoints
 * @subpackage admin
 * @since 0.1
 *
 * @uses intval
 *
 */
function ptsVal($args) {
	$args['value'] = intval($args['value']);
	return $args;
}


/* ============== Hook Callback Functions ============== */

/**
 * Add Prospress-Cubepoints submenu to Cubepoints
 *
 * @since 0.1
 *
 */
function ppcp_submenu_page() {
	add_submenu_page('cp_admin_manage', 'CubePoints - ' .__('Cubepoints Auctions','ppcp'), __('Cubepoints Auctions','ppcp'), 8, 'ppcp_auction_options', 'ppcp_admin_menu');
}

/**
 * 
 * Register Prospress-Cubepoints settings depending on mode.
 * 
 * @since 0.1
 *
 */
function ppcp_admin_init(){

	wp_enqueue_style( 'ppcp-admin', PPCP_PLUGIN_URL.'/css/ppcp-admin.css', null, '0.1' );

	//register prospress cubepoints options
	register_setting( 'ppcp_options' , 'ppcp_win_points' , 'ptsVal' );
	register_setting( 'ppcp_options' , 'ppcp_sell_points' , 'ptsVal' );
	register_setting( 'ppcp_options' , 'ppcp_bid_points' , 'ptsVal' );
}
/**
 * 
 * Add General notice while Cubepoints auction mode is enabled
 * 
 * @since 0.1
 *
 */
add_action('all_admin_notices','ppcp_notices');
function ppcp_notices(){
	if ( is_ppcp_mode() ) {
		add_settings_error( 'currency_type', 'ppcp_mode', __( "Prospress - Cubepoints mode:&nbsp;&nbsp;&nbsp;Payment Settings disabled.<br/ > WARNING: Auctions transactions are now run on cubepoints and not real currency.", 'ppcp'), 'updated' );
		settings_errors('ppcp_mode');
	}
}
/**
 * Removes Prospress payments settings page in Cubepoints mode
 * 
 *
 * @package Prospress
 * @subpackage pp-cubepoints
 * @since 0.1
 *
 * @uses 
 */
function ppcp_cubepoints_mode_menu() {
	global $submenu;
	unset( $submenu['outgoing_invoices'][2] );
}

/**
 * Format cubepoints currency type around auction value [prefix, value, suffix]
 * 
 *
 * @package Prospress Cubepoints
 * @since 0.1
 *
 * @uses 
 */
function ppcp_currency_format($currency) {
	global $currency_symbol;
	$currency_symbol = get_option('cp_prefix'); //reset symbol to only prefix to avoid formatting errors
	return (get_option( 'currency_type' )=='CPS')?array(get_option('cp_prefix'),(int)$currency[1],get_option('cp_suffix')):$currency;
}

/**
 * Load cubepoints custom currency type
 * 
 *
 * @package Prospress Cubepoints
 * @since 0.1
 *
 * @uses 
 */
function ppcp_currency_type($currencies) {
	$currencies['CPS'] = array( 'currency_name' => __('Cubepoints'), 'symbol' => get_option('cp_prefix').' '.get_option('cp_suffix') );
	return $currencies;
}

/**
 * 
 * filter for increment value to return integers only (compatability with cubepoints functions)
 *
 * @package Prospress Cubepoints
 * @subpackage admin
 * @since 0.1
 *
 * @uses intval
 *
 */
function ppcp_floor_bid_increment( $eqn ) {
	$eqn['increment'] = floor($eqn['increment']);
	return $eqn;
}

?>
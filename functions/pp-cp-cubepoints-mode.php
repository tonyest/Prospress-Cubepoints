<?php
/**
 * In Cubepoints-mode Prospress uses Cubepoints as currency.
 * Standard invoices and payments are disabled and points automatically deducted from
 * user totals.  Standard Prospress Cubepoints actions are disabled.
 *
 * @package Prospress
 * @subpackage pp-cubepoints
 * @since 0.1
 *
 * @uses 
 */
function pp_cp_cubepoints_mode() {

	if ( is_pp_cp_mode() ) {
		remove_action('post_completed', 'pp_generate_invoice' );

		add_action( 'admin_head', 'pp_cp_cubepoints_mode_menu' );
		add_filter('bid_pre_db_insert','pp_cp_validate_bid',1,1);
		add_filter( 'bid_message','pp_cp_validate_post', 1,2);
		add_filter( 'increment_bid_value' , 'pp_cp_cubepoints_int' , 1 , 1 );
	}
		add_action('get_auction_bid','pp_cp_bid');	
		add_action( 'generate_invoice', 'pp_cp_win' );
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
function pp_cp_cubepoints_mode_menu() {
	global $submenu;
	unset( $submenu['outgoing_invoices'][2] );
}
?>
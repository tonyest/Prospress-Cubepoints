<?php
/**
 * In Cubepoints-mode Prospress uses cubepoints as currency.
 * Standard invoices and payments are disabled and points automatically deducted from
 * user totals.  Standard Prospress Cubepoints actions are disabled.
 *
 * @package Prospress
 * @subpackage pp-cubepoints
 * @since 0.1
 *
 * @uses 
 */
function pp_cp_cubepoints_mode(){
	//  ADD ACTION, AUTO SET CURRENCY TYPE TO CUBEPOINTS
	if ( 'CPS' == get_option('currency_type') ){
		remove_action('get_auction_bid','pp_cp_add_bid_points');
		remove_action('post_completed', 'pp_cp_win_pts' );
		remove_action('post_completed', 'pp_generate_invoice' );
		
		add_filter('validate_bid','pp_cp_validate_bid',1,4);
		add_filter( 'bid_message','pp_cp_validate_post', 1,2);
		add_filter('bid_pre_db_insert','pp_cp_validate_bid',1,1);
		add_action( 'admin_head', 'pp_cp_cubepoints_mode_menu' );
	} elseif ( 'disabled' == get_option('pp_cp_cubepoints_mode')){
			add_action('get_auction_bid','pp_cp_add_bid_points');
			add_action( 'generate_invoice', 'pp_cp_win_pts' );
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
function pp_cp_cubepoints_mode_menu() {
	global $submenu;
	unset( $submenu['outgoing_invoices'][2] );
}
?>
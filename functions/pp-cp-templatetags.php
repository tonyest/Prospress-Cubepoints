<?php
/**
 * Initialise Prospress Cubepoints Options
 * 
 *
 * @package Prospress
 * @subpackage pp-cubepoints
 * @since 0.1
 *
 * @uses 
 */
function pp_cp_install(){

}
add_action( 'pp_cp_activation', 'pp_cp_install' );
/**
 * Return formatted url for cp_admin_ in prospress module
 * 
 *
 * @package Prospress
 * @subpackage pp-cubepoints
 * @since 0.1
 *
 * @uses 
 */
function pp_curPageURL($page = "prospress") {

	$link = "?page=cp_admin_modules&cp_module=pp_cp_admin_".$page;
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["PHP_SELF"].$link;
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["PHP_SELF"].$link;
	}
	return $pageURL;
}

/**
 * Format cubepoints currency type around auction value [prefix, value, suffix]
 * 
 *
 * @package Prospress
 * @subpackage pp-cubepoints
 * @since 0.1
 *
 * @uses 
 */
function pp_cp_format_currency_type($currency){
	return (get_option( 'currency_type' )=='CPS')?array(get_option('cp_prefix'),$currency[1],get_option('cp_suffix')):$currency;
}

/**
 * Load cubepoints custom currency type
 * 
 *
 * @package Prospress
 * @subpackage pp-cubepoints
 * @since 0.1
 *
 * @uses 
 */
function pp_cp_add_currency_type($currencies){
	$currencies['CPS'] = array( 'currency_name' => __('Cubepoints'), 'symbol' => get_option('cp_prefix').' '.get_option('cp_suffix') );
	return $currencies;
}

/**
 * Hooks into an auction a successful bid and awards points
 * 
 *
 * @package Prospress
 * @subpackage pp-cubepoints
 * @since 0.1
 *
 * @uses is_user_logged_in,get_option,cp_log,cp_currentUser
 */
function pp_cp_add_bid_points ($bid){
	if(function_exists('cp_alterPoints')&&is_user_logged_in()&&get_option('cp_mode_enabled')==true){
			//iterate through users for current winning bidder
		foreach ( $userids as $id ) {
			$id = (int) $id;
			if(is_winning_bidder($id, $bid['post_id'] )){
				cp_alterPoints($id, get_winning_bid_value($bid['post_id']));//refund amount to current winner
			}//if
			cp_alterPoints($bid['bidder_id'],-$bid['bid_value']); //deduct points from new winner
			cp_log('Winning bid:points held while winning',$bid['bidder_id'],$bid['bid_value'], 'http://example.com/?p='.$bid['post_id']);
		}//foreach
	}elseif(function_exists('cp_alterPoints')&&is_user_logged_in()&&get_option('currency_type')=='CPS'&&$bid['bid_status']=='winning'){

			if(!is_winning_bidder('', $bid['post_id'] )){
				$cp_bid_pts = get_option('cp_bid_pts');
				cp_alterPoints(cp_currentUser(), $cp_bid_pts);
				cp_log('winning bid', cp_currentUser(), $cp_bid_pts, is_winning_bidder(cp_currentUser(), $bid['post_id'] ));
			}//if

	}//endif
	return;
}
/**
 * Hooks into validate_bid to check sufficient Cubepoints for bid
 * 
 *
 * @package Prospress
 * @subpackage pp-cubepoints
 * @since 0.1
 *
 * @uses CUBEPOINTS MODE
 */
function pp_cp_validate_bid( $bid ){
	if( $bid['bid_value'] > cp_getPoints( $bid['bidder_id'] ) && $bid['bid_status'] != 'invalid' ) {
		$bid['bid_status'] = 'invalid';
		$bid['message_id'] = 99;
		return $bid;
	}
	else
		return $bid;
}

/**
 * Prints appropriate bid message for unvalidated points?
 * 
 *
 * @package Prospress
 * @subpackage pp-cubepoints
 * @since 0.1
 *
 * @uses CUBEPOINTS MODE
 */
function pp_cp_validate_post( $message, $message_id ){
	if(  $message_id == 99 )
		return  __( 'You do not have enough points for this bid.', 'prospress' );
	else
		return $message;
}

/**
 * Hooks to completed auction, 
 * Cubepoints mode disabled: iterates through registered users and allocates
 * pre-specified amount of cubepoints to winning users.
 * Cubepoints mode enabled: iterates through registered users and deducts amount
 * credits amount to the seller
 *
 * @package Prospress
 * @subpackage pp-cubepoints
 * @since 0.1
 *
 * @uses is_winning_bidder,cp_alterPoints,cp_log,is_user_logged_in
 */
function pp_cp_win_pts($args){	
	if( function_exists('cp_alterPoints') && is_user_logged_in()){
		if (get_option('cp_mode_enabled')== true){
			$userids = $_REQUEST['users'];
			foreach ( $userids as $id ) {
				$id = (int) $id;
				if(is_winning_bidder( $user_id, $args['post_id'] )){
					cp_alterPoints($id,-$args['amount']);
					cp_log('Winning bid - points deducted', $id,-$args['amount'], 'bid');
				}
			cp_alterPoints($args['payee_id'],$args['amount']);
			cp_log('Item sold', $id,$args['amount'], 'bid');
			}
		}else{	
			$userids = $_REQUEST['users'];
			$cp_win_pts = get_option('cp_win_pts');
			foreach ( $userids as $id ) {
				$id = (int) $id;
				if(is_winning_bidder( $user_id, $args['post_id'] )){
					cp_alterPoints($id,$cp_win_pts);
					cp_log('winning bid', $id,$cp_win_pts, 'bid');
				}
			continue;
			}
		}
	}
}

/**
 * remove bid points
 * 
 *
 * @package Prospress
 * @subpackage pp-cubepoints
 * @since 0.1
 *
 * @uses 
 */
function pp_cp_rm_bid_points (){
		if( function_exists('cp_alterPoints') && is_user_logged_in()&&get_option('currency_type')=='CPS'){
			cp_alterPoints(cp_currentUser(), get_option('cp_bid_points'));
			update_option('cp_bid_subtotal',0);
			cp_log('hey', cp_currentUser(), get_option('cp_bid_points'), 'bid');
	}
	return;
}

?>
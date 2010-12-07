<?php
/**
 * Initialise Prospress Cubepoints Options
 * 					
 *
 * @package Prospress Cubepoints
 * @since 0.1
 *
 * @uses 
 *//*						DEPRECATED   -   NOTHING TO INSTALL!!
function pp_cp_install() {

}
//add_action( 'pp_cp_activation', 'pp_cp_install' );
*/
/**
 * Return formatted url for cp_admin_ in prospress module
 * 
 *
 * @package Prospress Cubepoints
 * @since 0.1
 *
 * @uses 
 *//*						DEPRECATED   -   SILLY FUNCTION FROM CUBEPOINTS THAT ISN'T NEEDED WITH SETTINGS API	
function cp_pp_curPageURL($page = "menu") {
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
*/
/**
 * Conditional checks for the Cubepoints custom currency type.
 * This is the indicator of Prospress - Cubepoints mode.
 *
 * @package Prospress Cubepoints
 * @since 0.1
 *
 * @uses 
 */
function is_pp_cp_mode() {
	 return ( 'CPS' == get_option('currency_type') )? true : false ;
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
function pp_cp_currency_format($currency) {
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
function pp_cp_currency_type($currencies) {
	$currencies['CPS'] = array( 'currency_name' => __('Cubepoints'), 'symbol' => get_option('cp_prefix').' '.get_option('cp_suffix') );
	return $currencies;
}


			/*-------	Bid Functions	--------*/
/**
* Hooks into validate_bid to check sufficient Cubepoints for bid
* 
*
* @package Prospress Cubepoints
* @since 0.1
*
* @uses CUBEPOINTS MODE
*/
function pp_cp_validate_bid( $bid ) {
	global $wpdb;
	$max =	get_winning_bid( $bid['post_id'] );						
	$max_bid = $max->post_content;
	//case for increasing max bid
	$bid_value = ($bid['bidder_id'] == $max->post_author)? (intval($bid['bid_value']) - intval($max_bid)) : (int)$bid['bid_value'] ;
	if ( $bid_value > cp_getPoints( $bid['bidder_id'] ) && $bid['bid_status'] != 'invalid' ) {
		$bid['bid_status'] = 'invalid';
		$bid['message_id'] = 99;
		return $bid;
	} else if ( !ctype_digit((string)$bid['bid_value']) && $bid['bid_status'] != 'invalid') {
		$bid['bid_status'] = 'invalid';
		$bid['message_id'] = 98;
		return $bid;
	} else {
		$bid['bid_value'] = floor($bid['bid_value']);
		return $bid;
	}
}

/**
 * Prints appropriate bid message for unvalidated points?
 * 
 *
 * @package Prospress Cubepoints
 * @since 0.1
 *
 * @uses CUBEPOINTS MODE
 */
function pp_cp_validate_post( $message, $message_id ) {
	switch( $message_id ) {
		case 99 :
			return  __( 'You do not have enough points for this bid.', 'prospress' );
			break;
		case 98 :	//message for pp-cp invalid number format to whole number
			return  __( 'Invalid bid. Please enter a valid whole number. e.g. 7 or 58', 'prospress' );
			break;
		case 7 :	//override standard invalid number format to pp-cp mode whole number
			return  __( 'Invalid bid. Please enter a valid whole number. e.g. 7 or 58', 'prospress' );
			break;
		default:
			return $message;
			break;
	}
}

/**
 * Hooks to completed auction, 
 * Cubepoints mode disabled: iterates through registered users and allocates
 * pre-specified amount of cubepoints to winning users.
 * Cubepoints mode enabled: iterates through registered users and deducts amount
 * credits amount to the seller
 *
 * @package Prospress Cubepoints
 * @since 0.1
 *
 * @uses is_winning_bidder,cp_alterPoints,cp_log,is_user_logged_in
 * $args  'post_id', 'payer_id', 'payee_id', 'amount', 'status', 'type' 
 * cp_log ($type, $uid, $points, $source)
 */
function pp_cp_win( $post_id ) {
		
	if ( function_exists('cp_alterPoints') && function_exists('cp_log') ) {
		$bid = get_winning_bid($post_id);					
		$bidder_id = $bid->post_author;
		$bid_value = $bid->winning_bid_value;
		$max_bid = $bid->post_content;
		//get post info for cp_log and generate link to ended auction page
		$post = get_post($post_id);
		$title = $post->post_title;
		$source = '<a href="'.home_url().'?post_type=auctions&p='.$post_id.'">'.$title.'</a>';		

		if ( is_pp_cp_mode() ){
			//auction is won -  award points to seller & confirm to winner (final bid amount will be equal to purchase amount)						
			cp_alterPoints( $bidder_id , $max_bid - $bid_value );			

			cp_log( __('Item won for: ','cp').pp_money_format( $bid_value ).__(' Points unfrozen: '.($max_bid - $bid_value) , 'cp' ) , $bidder_id ,  $max_bid - $bid_value , $source );
			cp_log( __('Item sold','cp') , $bidder_id ,  $bid_value , $source);
			//TODO - adjust for actual win value and unfreeze max bid points

		} else {
			if ( !is_pp_cp_mode() && checked($sell_points['enabled'], 'on') ) {
				//award set points to seller and log
				cp_alterPoints((int)$args['payee_id'],(int)$sell_points['value']);
				cp_log( __('Item sold','cp') , (int)$args['payee_id'] ,  (int)$sell_points['value'] , $source );
			}
			if (checked($win_points['enabled'], 'on') ){
				//award set points to buyer and log
				cp_alterPoints( (int)$args['payer_id'] , (int)$win_points['value'] );
				cp_log( __('Item won','cp') , (int)$args['payer_id'] ,  (int)$win_points['value'] , $source );
			}
		}
		
	}
}

/**
 * Hooks into an auction a successful bid and awards points
 * 
 *
 * @package Prospress Cubepoints
 * @since 0.1
 *
 * @uses is_user_logged_in,get_option,cp_log,cp_currentUser
 * $bid = "post_id", "bidder_id", "bid_value", "bid_date", "bid_date_gmt" , 'bid_status', 'message_id'
 */
function pp_cp_bid( $bid ) {

	if ( function_exists( 'cp_alterPoints' ) && function_exists( 'cp_log' ) ) {
		
		$bidder_id = (int)$bid['bidder_id'];
		//get post info for cp_log and generate link to ended auction page
		$post = get_post($bid['post_id']);
		$title = $post->post_title;
		$source = '<a href="'.home_url().'?post_type=auctions&p='.$bid['post_id'].'">'.$title.'</a>';		

		//successful bid - award set points to winning bidder
		if ( is_pp_cp_mode() && 'winning' == $bid['bid_status'] ) {

			$bid_value = $bid['bid_value'];		
			//get previous winner bid details			
			$prev =	get_winning_bid( $bid['post_id'] );						
			$prev_bidder = $prev->post_author;
			$prev_bid = $prev->winning_bid_value;
			$max_bid = $prev->post_content;

			if(	$prev_bidder == $bidder_id ) {
				//increase max bid
				cp_alterPoints( $bidder_id , ( $max_bid - $bid_value ) );		
				cp_log( __('Maximum bid increased. Total points held: ','cp').$bid_value , $prev_bidder,  ( $max_bid - $bid_value ) , $source );
			} else {
				//new winner
				//refund bid amount as points to user
				cp_alterPoints( $prev_bidder , $max_bid );			
				cp_log( __('Outbid! points returned','cp') , $prev_bidder ,  $max_bid , $source );
				//remove bid amount as points from user
				cp_alterPoints( $bidder_id , -$bid_value );
				cp_log( __('Successful Bid! points frozen','cp') , $bidder_id ,  -$bid_value , $source);
			}
		} else if ( !is_pp_cp_mode() && isset($bid_points['enabled']) && 'winning' == $bid['bid_status'] ) {
			// Normal Cubepoints
			$bid_pts = (float)get_option('pp_cp_bid_pts');
			//award set points to bidder
			cp_alterPoints( $bidder_id , $bid_pts );
			cp_log( __('Successful Bid','cp') , $bidder_id ,  $bid_pts , $source);	
		}
	}//if
	return;
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
function pp_cp_cubepoints_int( $args ) {
	$args['increment'] = floor($args['increment']);
	return $args;
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

/**
 * 
 * Filter bid increments in Prospress Cubepoints mode to int only as cubepoints is not configured for double values
 *
 * @package Prospress Cubepoints
 * @subpackage admin
 * @since 0.1
 *
 * @uses 
 *
 */
function pp_cp_bid_format( $args ) {

			//	$args['coefficient']
			//	$args['constant']
			return $args;
}
//add_filter( 'increment_bid_equation' , 'pp_bid_format' , 1 , 1 );


/*

TODO
cubepoints module
	add points equal or proportional to sale value
	add points when users win [user set val] (multiple winners)
	add points when users post images in auctions [user set val]

cubepoints mode
	freeze points on bid
	subtract points on purchse
	
Menu settings


DONE
cubepoints module
	add points when users sell [user set val]
	add points when users win [user set val] (non-multiple winners)
	Add points when users bid [user set val]  
	
cubepoints mode
	validate bid against user points total

Menu settings
	disabled fields - disable functions
	*/
?>
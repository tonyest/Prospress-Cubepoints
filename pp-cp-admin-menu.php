<?php
function pp_cp_admin_menu() {
	if ( !current_user_can('manage_options') ) {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	//update options if values submitted, zero set if disabled
 	if ( $_POST['submit'] == 'submit' ) {
		if( !empty($_POST['win_pts']) ) 
			update_option( 'pp_cp_win_pts' , (int)$_POST['win_pts'] );
		else
			update_option( 'pp_cp_win_pts', 0 );
			
		if( !empty($_POST['bid_pts']) ) 
			update_option( 'pp_cp_bid_pts' , (int)$_POST['bid_pts'] );
		else
			update_option( 'pp_cp_bid_pts', 0 );

		if( !empty($_POST['sell_pts']) ) 
			update_option( 'pp_cp_sell_pts' , (int)$_POST['sell_pts'] );
		else
			update_option( 'pp_cp_sell_pts', 0 );
  	}
	// string for disabling input fields in cubepoints mode
	$disabled = ( is_pp_cp_mode() )? "disabled=true" : "" ;

?>

<div class="pp-cp-admin-menu">
	<h2>CubePoints - Prospress Settings</h2>
	<p><?php
	 	if ($_POST['submit'] == 'submit')
			echo '<div class="updated"><p><strong>',__('Settings Updated','cp'),'</strong></p></div>';
			
		_e('Configure Cubepoints in Prospress', 'cp'); 
	?></p>
	<form name="pp_cp_form" method="post" action="<?php echo cp_pp_curPageURL(); ?>">

		<p>Enabling Cubepoints mode in Prospress will change the auction system to use Cubepoints as a currency.  When Cubepoints mode is active the entire market system runs on a virtual currency; users may bid on auctions or purchase items using their accumulated Cubepoints.  IMPORTANT: In Cubepoints-mode Prospress payments module is de-activated as are the General settings below. 
		</p>

		<h3><?php _e('General Settings','cp'); ?></h3>
		<p class="pp-cp-admin-menu general-settings">
			<table class="pp-cp-admin-menu form-table">
				<tr valign="top">
					<th scope="row"><label for="win_pts"><?php _e('Number of points for purchase (win)', 'cp'); ?>:</label></th>
					<td valign="middle"><input type="text" <?php echo $disabled;?> id="win_pts" name="win_pts" value="<?php echo get_option('pp_cp_win_pts'); ?>" size="20" /></td>					
					<td><input type="button" value="<?php _e('Do not add points for winning auctions','cp'); ?>" class="button" id="win_pts_button" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="sell_pts"><?php _e('Number of points added for selling an item','cp'); ?>:</label></th>
					<td valign="middle"><input type="text" <?php echo $disabled;?> id="sell_pts" name="sell_pts" value="<?php echo get_option('pp_cp_sell_pts'); ?>" size="20" /></td>
					<td><input type="button" value="<?php _e('Do not add points for selling items','cp'); ?>" class="button" id="sell_pts_button" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="bid_pts"><?php _e('Number of points for each winning bid','cp'); ?>:</label></th>
					<td valign="middle"><input type="text" <?php echo $disabled;?> id="bid_pts" name="bid_pts" value="<?php echo get_option('pp_cp_bid_pts'); ?>" size="20" /></td>
					<td><input type="button" value="<?php _e('Do not add points winning bids','cp'); ?>" class="button" id="bid_pts_button" /></td>
				</tr>
			</table>
		</p>
		<p class="pp-cp-admin-menu submit">
			<button type="submit" name="submit" value="submit"><?php _e('Update Options','cp'); ?></button>
		</p>
	</form>
</div>
<?php 	}// close pp_cp_admin_menu	?>

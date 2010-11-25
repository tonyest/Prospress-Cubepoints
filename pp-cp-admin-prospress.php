<?php
function pp_cp_admin_prospress() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
 	if ($_POST['cp_pp_admin_form_submit'] == 'Y') {
		if(!empty($_POST['cp_win_pts'])) {
			update_option('cp_win_pts', (int)$_POST['cp_win_pts']);
		}
		if(!empty($_POST['cp_bid_pts'])) {
			update_option('cp_bid_pts', (int)$_POST['cp_bid_pts']);
		}
		if(!empty($_POST['cp_sell_pts'])) {
			update_option('cp_sell_pts', (int)$_POST['cp_sell_pts']);
		}
		echo '<div class="updated"><p><strong>',__('Settings Updated','cp'),'</strong></p></div>';
  	}
?>



<div class="wrap">
	<h2>CubePoints - Prospress Settings</h2>
<?php _e('Configure Cubepoints in Prospress', 'cp'); ?><br /><br />
	<form name="pp_cp_form" method="post" action="<?php echo pp_curPageURL(); ?>">
		<input type="hidden" name="cp_pp_admin_form_submit" value="Y" />
		<p>Enabling Cubepoints mode in Prospress will change the auction system to use Cubepoints as a currency.  When Cubepoints mode is active the entire market system runs on a virtual currency; users may bid on auctions or purchase items using their accumulated Cubepoints.  IMPORTANT: In Cubepoints-mode Prospress payments module is de-activated as are the General settings below. 
		</p>

		<h3><?php _e('General Settings','cp'); ?></h3>
		<div class="general-settings">
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="cp_win_pts"><?php _e('Number of points for purchase (win)', 'cp'); ?>:</label>
					</th>
					<td valign="middle" width="190">				
		<?php
		$win_pts = '<input type="text" id="cp_win_pts" name="cp_win_pts" value="'.get_option('cp_win_pts').'" size="20" /></td>';

					echo $win_pts.'<td><input type="button" value="win button" class="button" id="cp_win_pts_button" />';
		?>
				
						</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cp_sell_pts"><?php _e('Number of points added for selling an item','cp'); ?>:</label>
					</th>
					<td valign="middle"><input type="text" id="cp_sell_pts" name="cp_sell_pts" value="<?php echo get_option('cp_sell_pts'); ?>" size="20" /></td>
					<td>
				
						<input type="button" value="<?php _e('Do not add points for selling items','cp'); ?>" class="button" id="cp_sell_pts_button" /></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="cp_bid_pts"><?php _e('Number of points for each winning bid','cp'); ?>:</label>
					</th>
					<td valign="middle"><input type="text" id="cp_bid_pts" name="cp_bid_pts" value="<?php echo get_option('cp_bid_pts'); ?>" size="20" /></td>
					<td>
				
						<input type="button" value="<?php _e('Do not add points winning bids','cp'); ?>" class="button" id="cp_bid_pts_button" /></td>
				</tr>
			</table>
		</div>
		<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Update Options','cp'); ?>" />
		</p>
	</form>
</div>

<?php
}
/*
cubepoints module
	add points equal or proportional to sale value
	Add points when users bid [user set val]   
	add points when users win [user set val]   xx
	add points when users sell [user set val]  xx
	add points when users post images in auctions [user set val]


cubepoints mode
	validate bid against user points total
	freeze points on bid
	subtract points on purchse
	

	*/
	
	

	
	
	
	
	
?>

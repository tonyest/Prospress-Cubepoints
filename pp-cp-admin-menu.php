<?php
function pp_cp_admin_menu() {
	
	if ( !current_user_can('manage_options') ) {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	$pp_cp_mode = is_pp_cp_mode();
	$message = 'Prospress - Cubepoints mode:&nbsp;&nbsp;&nbsp;Normal points options disabled.';
	if( $pp_cp_mode )
		add_settings_error( 'cubepoints-mode', 'pp-cp-mode', $message, 'error' );
	$win_points = get_option('win_points');
	$sell_points = get_option('sell_points');
	$bid_points = get_option('bid_points');		
?>
<div class="wrap">
	<h2>CubePoints - Prospress Settings</h2>
	<?php settings_errors(); ?>
	<p><?php _e('Configure Cubepoints in Prospress', 'cp'); ?></p>
	<form method="post" action="options.php">
		<?php settings_fields( 'pp_cp_options' ); ?>
		<p>Enabling Cubepoints mode in Prospress will change the auction system to use Cubepoints as a currency.  When Cubepoints mode is active the entire market system runs on a virtual currency; users may bid on auctions or purchase items using their accumulated Cubepoints.  IMPORTANT: In Cubepoints-mode Prospress payments module is de-activated as are the General settings below. 
		</p>

		<h3><?php _e('General Settings','cp'); ?></h3>
		<p class="pp-cp-admin-menu general-settings">
			<table class="pp-cp-admin-menu form-table">
				<tr valign="top">
					<th scope="row"><label for="win_points"><?php _e('Number of points for purchase (win)', 'cp'); ?>:</label></th>
					<td valign="middle">
						<input type="text" <?php disabled($pp_cp_mode);?> id="win_points" name="win_points[value]" value="<?php echo $win_points['value']; ?>" size="10" ></input>
					</td>					
					<td>
						<input type="checkbox" <?php disabled($pp_cp_mode);?> name="win_points[enabled]" <?php checked($win_points['enabled'],'on'); ?> ><?php _e('Add points for winning auctions','cp'); ?>
						</input>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="sell_points"><?php _e('Number of points added for selling an item','cp'); ?>:</label>
					</th>
					<td valign="middle">
						<input type="text" <?php disabled($pp_cp_mode);?> id="sell_points" name="sell_points[value]" value="<?php echo $win_points['value']; ?>" size="10" /></td>
					<td>
						<input type="checkbox" <?php disabled($pp_cp_mode);?> name="sell_points[enabled]" <?php checked($sell_points['enabled'] , 'on' ); ?> ><?php _e('Add points for selling items','cp'); ?></input></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="bid_points"><?php _e('Number of points for each winning bid','cp'); ?>:</label></th>
					<td valign="middle">
						<input type="text" <?php disabled($pp_cp_mode);?> id="bid_points" name="bid_points[value]" value="<?php echo $bid_points['value']; ?>" size="10" />
					</td>
					<td>
						<input type="checkbox" <?php disabled($pp_cp_mode);?> name="bid_points[enabled]" <?php checked($bid_points['enabled'], 'on'); ?> ><?php _e('Add points winning bids','cp'); ?></input>
					</td>
				</tr>
			</table>
		</p>
		<p class="pp-cp-admin-menu submit">
			<button type="submit" <?php disabled($pp_cp_mode);?> name="submit" value="Save" class="button-primary" >
				<?php _e('Update Options','cp'); ?>
			</button>
		</p>
	</form>
</div>
<?php 	}// close pp_cp_admin_menu	?>

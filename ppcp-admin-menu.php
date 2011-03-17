<?php
function ppcp_admin_menu() {
	
	if ( !current_user_can('manage_options') ) {
		wp_die( __('You do not have sufficient permissions to access this page.', 'ppcp') );
	}
	
	$ppcp_mode = is_ppcp_mode();
		
	$message = __( "Prospress - Cubepoints mode is active:&nbsp;&nbsp;&nbsp;Normal points options disabled.", 'ppcp');
	if( $ppcp_mode )
		add_settings_error( 'cubepoints-mode', 'ppcp-mode', $message, 'error' );
		
	$ppcp_win_points = get_option('ppcp_win_points', '5' );
	$ppcp_sell_points = get_option('ppcp_sell_points', '5' );
	$ppcp_bid_points = get_option('ppcp_bid_points', '5' );		
	?>
	<div class="wrap">
		<h2>CubePoints - Prospress Settings</h2>
		<?php settings_errors(); ?>
		<p><?php _e('Configure Cubepoints in Prospress', 'ppcp'); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'ppcp_options' ); ?>
			<p>Enabling Cubepoints mode in Prospress will change the auction system to use Cubepoints as a currency.  When Cubepoints mode is active the entire market system runs on a virtual currency; users may bid on auctions or purchase items using their accumulated Cubepoints.  IMPORTANT: In Cubepoints-mode Prospress payments module is de-activated as are the General settings below. 
			</p>

			<h3><?php _e('General Settings','cp'); ?></h3>
			<p class="ppcp-admin-menu general-settings">
				<table class="ppcp-admin-menu form-table">
					<tr valign="top">
						<th scope="row"><label for="ppcp_win_points"><?php _e('Number of points for purchase (win)', 'ppcp'); ?>:</label></th>
						<td valign="middle">
							<input type="text" class="win-points" <?php disabled($ppcp_mode);?> id="ppcp_win_points" name="ppcp_win_points[value]" value="<?php echo $ppcp_win_points['value']; ?>" size="10" ></input>
						</td>					
						<td>
							<input type="checkbox" class="win-points" <?php disabled($ppcp_mode);?> name="ppcp_win_points[enabled]" <?php checked($ppcp_win_points['enabled'],'on'); ?> ><?php _e('Enable points for winning auctions','ppcp'); ?>
							</input>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="ppcp_sell_points"><?php _e('Number of points added for selling an item','ppcp'); ?>:</label>
						</th>
						<td valign="middle">
							<input type="text" class="sell-points" <?php disabled($ppcp_mode);?> id="ppcp_sell_points" name="ppcp_sell_points[value]" value="<?php echo $ppcp_win_points['value']; ?>" size="10" /></td>
						<td>
							<input type="checkbox" class="sell-points" <?php disabled($ppcp_mode);?> name="ppcp_sell_points[enabled]" <?php checked($ppcp_sell_points['enabled'] , 'on' ); ?> ><?php _e('Enable points for selling items','ppcp'); ?></input></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="ppcp_bid_points"><?php _e('Number of points for each winning bid','ppcp'); ?>:</label></th>
						<td valign="middle">
							<input type="text" class="bid-points" <?php disabled($ppcp_mode);?> id="ppcp_bid_points" name="ppcp_bid_points[value]" value="<?php echo $ppcp_bid_points['value']; ?>" size="10" />
						</td>
						<td>
							<input type="checkbox" class="bid-points" <?php disabled($ppcp_mode);?> name="ppcp_bid_points[enabled]" <?php checked($ppcp_bid_points['enabled'], 'on'); ?> ><?php _e('Enable points winning bids','ppcp'); ?></input>
						</td>
					</tr>
				</table>
			</p>
			<p class="ppcp-admin-menu submit">
				<button type="submit" <?php disabled($ppcp_mode);?> name="submit" value="Save" class="button-primary" >
					<?php _e('Update Options','cp'); ?>
				</button>
			</p>
		</form>
	</div>
	<?php 	
}// ppcp_admin_menu
?>
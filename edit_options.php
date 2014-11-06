<?php
 /*hook for admin menus for the plugin*/
 add_action( 'admin_menu', 'include_sermon_menu' );

 /*function with the menu-building code to setup the menu for the plugin*/
 function include_sermon_menu() {
	//Add new submenu under Settings
	add_options_page( 'Include Sermon Options', 'Include Sermon', 'manage_options', 'include_sermon', 'include_sermon_options' );
 }
 
 /*function to setup the page for editing the plugin options*/
 function include_sermon_options() {
	//checking if the user is not allowed to change this options
	if(!current_user_can( 'manage_options' )){
		//if he is not allowed to change these options he will get this following message:
		wp_die( __( 'You are not allowed to edit these options! <br/> Please contact the Administrator.', 'include-sermon-lang' ) );
	}
	//now the page with the options is beeing created
	
	//getting the stored data
	$button_color = get_option( 'include-sermon-button-color' );
	
	//checking if the user has really send the form
	if( isset( $_POST['checkpost'] ) && $_POST['checkpost'] == 'rg' ) {
		//getting the postet values and storing them into the database
		$button_color = $_POST['button-color'];
		update_option( 'include-sermon-button-color', $button_color );
		//telling the user that his setting were successfully stored
		echo "<div class='updated'><p><strong>".__( 'settings saved', 'include-sermon-lang' )."</strong></p></div>";
	}
	?>
	<!--now the options start-->
	<div class="wrap">
		<form name='options' action='' method='post'>
			<!--setting the checking variable to verify that the user has send this form-->
			<input type='hidden' name='checkpost' value='rg'>
			<!--for better look the options are listed in a table-->
			<table class='form-table'>
				<!--table header starts-->
				<thead>
					<!--title-->
					<tr><th colspan='2'><h2><?php _e( 'Here you can change the options', 'include-sermon-lang' );?></h2><hr></th></tr>
				</thead>
				<!--table header ends-->
				<!--table body starts-->
				<tbody>
					<tr>
						<!--label for the button-color input field-->
						<th><label for='button-color'><h4><?php _e( 'Choose a color for the button who starts the MP3 Download', 'include-sermon-lang' );?></h4></label></th>
						<td>
							<!--input field for setting the button-color-->
							<input type='text' name='button-color' maxlength='6' placeholder="<?php _e( 'Button Color', 'include-sermon-lang' );?>" required='required' value="<?php echo $button_color;?>">
							<!--instructing the user which strings he is allowed to use-->
							<p style='font-size:9px'><?php _e( 'You can use hexcode or words like "blue" or "red"', 'include-sermon-lang' );?></p>
						</td>
					</tr>
					<tr>
						<!--submit button to send the form-->
						<th><p class='submit'><input type='submit' name='submit' class='button-primary' value="<?php _e( 'Save', 'include-sermon-lang' );?>"></p></th>
						<td></td>
					</tr>
				</tbody>
				<!--tabe body ends-->
			</table>
			<!--table ends-->
		</form>
		<!--closing form-->
	</div>
	<?php
 }
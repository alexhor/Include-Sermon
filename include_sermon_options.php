<?php
class HS_IncludeSermonOptions {
	/*adding all needed actions and filters*/
	function __construct (){
		//hook for admin menus for the plugin
		add_action( 'admin_menu', array( &$this, 'include_sermon_menu' ) );
	}

	/*function with the menu-building code to setup the menu for the plugin*/
	function include_sermon_menu() {
		//Add new sub-menus under Settings and Posts
		add_options_page( 'Include Sermon Options', 'Include Sermon', 'manage_options', 'include_sermon', array( &$this, 'include_sermon_options' ) );
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
		$category = get_option( 'include-sermon-category' );
		$color = get_option( 'include-sermon-color' );
		$width = get_option( 'include-sermon-video-width' );
		$height = get_option( 'include-sermon-video-height' );
		
		//checking if the user has really send the form
		if( isset( $_POST['inc_serm_checkpost'] ) && $_POST['inc_serm_checkpost'] == 'rg' ) {
			//getting the postet values and storing them into the database
			$button_color = $_POST['inc_serm_button-color'];
			$category = $_POST['inc_serm_category'];
			$color = $_POST['inc_serm_color'];
			$width = $_POST['inc_serm_width'];
			$height = $_POST['inc_serm_height'];
			update_option( 'include-sermon-button-color', $button_color );
			update_option( 'include-sermon-category', $category);
			update_option( 'include-sermon-color', $color);
			update_option( 'include-sermon-video-width', $width);
			update_option( 'include-sermon-video-height', $height);
			update_option( 'include-sermon-options-set', 1);
			
			//telling the user that his setting were successfully stored
			echo "<div class='updated'><p><strong>".__( 'settings saved', 'include-sermon-lang' )."</strong></p></div>";
		}
		?>
		<!--now the options start-->
		<div class="wrap">
			<form name='inc_serm_options' action='' method='post'>
				<!--setting the checking variable to verify that the user has send this form-->
				<input type='hidden' name='inc_serm_checkpost' value='rg'>
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
							<th><label for='inc_serm_button-color'><h4><?php _e( 'Choose a color for the button who starts the MP3 Download', 'include-sermon-lang' );?></h4></label></th>
							<td>
								<!--input field for setting the button-color-->
								<input type='text' name='inc_serm_button-color' maxlength='7' placeholder="<?php _e( 'Button Color', 'include-sermon-lang' );?>" required='required' value="<?php echo esc_attr($button_color);?>">
								<!--instructing the user which strings he is allowed to use-->
								<p style='font-size:9px'><?php _e( 'You can use hexcode or words like "blue" or "red"', 'include-sermon-lang' );?></p>
							</td>
						</tr>
						<tr>
							<!--label for the button-color input field-->
							<th><label for='Category'><h4><?php _e( 'Give the ID of the category the post should be posted in', 'include-sermon-lang' );?></h4></label></th>
							<td>
								<!--input field for setting the category the post is posted in-->
								<?php wp_dropdown_categories( array( hide_empty => '0', name => 'inc_serm_category', selected => $category ) ); ?>
							</td>
						</tr>
						<tr>
							<!--label for the color input field-->
							<th><label for='color'><h4><?php _e( 'Choose a color for the text of the button who starts the MP3 Download', 'include-sermon-lang' );?></h4></label></th>
							<td>
								<!--input field for setting the category the post is posted in-->
								<input type='text' name='inc_serm_color' maxlength='7' placeholder="<?php _e( 'Color', 'include-sermon-lang' );?>" required='required' value="<?php echo esc_attr($color);?>">
							</td>
						</tr>
						<tr>
							<!--label for the color input field-->
							<th><label for='width'><h4><?php _e( 'Which size should the video have?', 'include-sermon-lang' );?></h4></label></th>
							<td>
								<!--input field for setting the width of the video-->
								<input type='text' name='inc_serm_width' maxlength='3' placeholder="<?php _e( 'width', 'include-sermon-lang' );?>" required='required' value="<?php echo esc_attr($width);?>">
								x
								<!--input field for setting the height of the video-->
								<input type='text' name='inc_serm_height' maxlength='3' placeholder="<?php _e( 'height', 'include-sermon-lang' );?>" required='required' value="<?php echo esc_attr($height);?>">
								<p style='font-size:12px; margin-left:5%'><?php _e( '(In pixel)', 'include-sermon-lang' );?></p>
							</td>
						</tr>
						<tr>
							<td class='submit'><input type='submit' class='button-primary' name='inc_serm_save' value="<?php _e( 'Save', 'include-sermon-lang' );?>"></td>
							<td></td>
					</tbody>
					<!--tabe body ends-->
				</table>
				<!--table ends-->
			</form>
			<!--closing form-->
		</div>
		<?php
	}
}

$HS_IncludeSermonOptions = NEW HS_IncludeSermonOptions;
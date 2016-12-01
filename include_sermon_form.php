<?php
defined( 'ABSPATH' ) or die( "Please don't do that!" );

class HS_IncludeSermonForm {
	// set variables for catching error
	private $given_data = array();
	private $false_data = array();
	private $form_no = 0;
	
	/*
	 * adding all needed actions and filters
	 */
	function __construct () {
		//hook for admin menus for the plugin
		add_action( 'admin_menu', array( &$this, 'include_sermon_menu_posts' ) );
		// add action for the verification form
		add_action( 'init', array( &$this, 'verify_info' ) );
		// hooking in init action for creating the post
		add_action( 'init', array( &$this, 'create_post' ) );
	}
	
	/*
	 * function with the menu-building code to setup the menu for the plugin
	 */
	public function include_sermon_menu_posts () {
		//Add new sub-menus under Posts
		if( !isset( $_POST['inc_serm_check'] ) ) { add_posts_page( __( 'New Sermon', 'include_sermon' ), __( 'New Sermon', 'include_sermon' ), 'edit_posts', 'include_sermon', array( &$this, 'show_form' ) );}
	}
	
	/*
	 * function to create a new sermon post
	 */
	public function show_form() {
		// the form hasn't been submited yet
		if( $this->form_no == 0 ) {
			?>
			<div class='wrapper'>
				<form action='' method='post'>
					<!-- header -->
					<h1><?php _e( 'Create Sermon Post', 'include_sermon' ); ?></h1>
					
					<!--input for the videolink from Vimeo (at the moment only videos from Vimeo can be used for this-->
					<label for='inc_serm_video'><?php _e( 'The Videolink from Vimeo (e.g.: http://vimeo.com/89224153)', 'include_sermon' );?></label><br>
					<input type='text' name='inc_serm_video' id='inc_serm_video' placeholder="<?php _e( 'Videolink', 'include_sermon' );?>" value="<?php echo esc_attr( @$this->given_data['video'] ); ?>">
					<input type='checkbox' name='inc_serm_no_video' id='inc_serm_no_video' <?php if( isset( $this->given_data['no_video'] ) ) echo 'checked'; ?>><label for='inc_serm_no_video'><?php _e( 'No Videolink available', 'include_sermon' ); ?></label><br>
					<?php if( isset( $this->false_data['video'] ) ) { echo '<div class="notice notice-error">' . __( 'This is not a valid videolink. Please correct this or check "No Videolink available"', 'include_sermon' ) . '</div>'; } ?>
					
					<br>
					
					<!--input for the link to your sermon audiofile-->
					<label for='inc_serm_audio'><?php _e( 'The shared link from Dropbox for the Audio file', 'include_sermon' );?></label><br>
					<input type='text' name='inc_serm_audio' id='inc_serm_audio' placeholder="<?php _e( 'Audiolink', 'include_sermon' );?>" value="<?php echo esc_attr( @$this->given_data['audio'] ); ?>">
					<input type='checkbox' name='inc_serm_no_audio' id='inc_serm_no_audio' <?php if( isset( $this->given_data['no_audio'] ) ) echo 'checked'; ?>><label for='inc_serm_no_audio'><?php _e( 'No Audiolink available', 'include_sermon' ); ?></label><br>
					<?php if( isset( $this->false_data['audio'] ) ) { echo '<div class="notice notice-error">' . __( 'This is not a valid audiolink. Please correct this or check "No Audiolink available"', 'include_sermon' ) . '</div>'; } ?>
					
					<?php /* create nonce */ wp_nonce_field( 'inc_serm_extract_info', 'inc_serm_nonce' ); ?>
					
					<!--button to send the form-->
					<p class='submit'><input type="submit" class='button-primary' value="<?php _e( 'Post Sermon', 'include_sermon' );?>"/></p>
				</form>
			</div>
			<?php
		}
		else {
			?>
			<div class='wrapper'>
					<form action='' method='post'>
						<!-- header -->
						<h2><?php _e( 'Please check the generated info for mistakes', 'include_sermon' ); ?></h2>
						
						<!-- edit the date -->
						<label for='inc_serm_date'><?php _e( 'Date', 'include_sermon' ); ?></label>
						<input type='text' name='inc_serm_date' id='inc_serm_date' placeholder="<?php _e( 'Date', 'include_sermon' ); ?>" value="<?php echo esc_attr( @$this->given_data['date'] ); ?>" required>
						<?php if( isset( $this->false_data['date'] ) ) echo '<div class="notice notice-error">' . __( "No date was found (is required)", 'include_sermon' ) . '</div>'; ?><br>
						
						<!-- edit the subject -->
						<label for='inc_serm_subject'><?php _e( 'Subject', 'include_sermon' ); ?></label>
						<input type='text' name='inc_serm_subject' id='inc_serm_subject' placeholder="<?php _e( 'Subject', 'include_sermon' ); ?>" value="<?php echo esc_attr( @$this->given_data['subject'] ); ?>"><br>
						
						<!-- edit the preachers name -->
						<label for='inc_serm_preacher'><?php _e( 'Preacher', 'include_sermon' ); ?></label>
						<input type='text' name='inc_serm_preacher' id='inc_serm_preacher' placeholder="<?php _e( 'Preacher', 'include_sermon' ); ?>" value="<?php echo esc_attr( @$this->given_data['preacher'] ); ?>"><br>
						
						<?php
						// create nonce
						wp_nonce_field( 'inc_serm_verify_info', 'inc_serm_nonce' );
						?>
						
						<input type="hidden" name="inc_serm_video" value="<?php echo esc_url( @$this->given_data['video'] ); ?>">
						<input type="hidden" name="inc_serm_audio" value="<?php echo esc_url( @$this->given_data['audio'] ); ?>">
						
						<!--button to send the form-->
						<p class='submit'><input type="submit" class='button-primary' value="<?php _e( 'Confirm Info', 'include_sermon' );?>"/></p>
					</form>
				</div>
			<?php
		}
		?>
		<style>
			form label {
				display: inline-block;
				min-width: 100px;
			}
			form input[type="text"] {
				min-width: 300px;
			}
		</style>
		<script>
			jQuery('#inc_serm_no_video').change(function(){
				if(jQuery(this).is(':checked')){
						jQuery('#inc_serm_video').prop('disabled', true);
					} else {
						jQuery('#inc_serm_video').prop('disabled', false);
				}
			});
			jQuery('#inc_serm_no_video').trigger('change');
			
			jQuery('#inc_serm_no_audio').change(function(){
				if(jQuery(this).is(':checked')){
						jQuery('#inc_serm_audio').prop('disabled', true);
					} else {
						jQuery('#inc_serm_audio').prop('disabled', false);
				}
			});
			jQuery('#inc_serm_no_audio').trigger('change');
		</script>
		<?php
	}
	
	/*
	 * extracting the sermon info from the given links and showing it to the user for verification
	 */
	public function verify_info() {
		// check if we come from the given form
		if( !isset( $_POST['inc_serm_nonce'] ) || !wp_verify_nonce( $_POST['inc_serm_nonce'], 'inc_serm_extract_info' ) || !check_admin_referer( 'inc_serm_extract_info', 'inc_serm_nonce' ) ) return;
		// check if all data is given
		if( !isset( $_POST['inc_serm_no_video'] ) && ( !isset( $_POST['inc_serm_video'] ) || !$this->verify_video( $_POST['inc_serm_video'] ) ) ) $this->false_data['video'] = true;
		if( !isset( $_POST['inc_serm_no_audio'] ) && ( !isset( $_POST['inc_serm_audio'] ) || !$this->verify_audio( $_POST['inc_serm_audio'] ) ) ) $this->false_data['audio'] = true;
		// save the given video and audiolink
		@$this->given_data['audio'] = trim( $_POST['inc_serm_audio'] );
		@$this->given_data['video'] = trim( $_POST['inc_serm_video'] );
		if( isset( $_POST['inc_serm_no_video'] ) ) { $this->given_data['no_video'] = true; $this->given_data['video'] = ''; }
		if( isset( $_POST['inc_serm_no_audio'] ) ) { $this->given_data['no_audio'] = true; $this->given_data['audio'] = ''; }
		
		if( isset( $this->false_data['video'] ) || isset( $this->false_data['audio'] ) ) return;
		$this->form_no = 1;
		
		// if no audio link is given, we can't extract any information and the user has to do it himself
		if( isset( $_POST['inc_serm_no_audio'] ) || !isset( $this->given_data['audio'] ) || empty( $this->given_data['audio'] ) ) return;
		
		// extract the sermons info from the given audio link
		if( !preg_match( "#^https?://(www\.)?dropbox\.com/(.*)#", $this->given_data['audio'], $matches ) ){ $this->false_data['audio'] = true; return; }
		$matches = explode( '/', $matches[2] );
		if( !is_array( $matches ) ){ $this->false_data['audio'] = true; return; }
		$info = urldecode( $matches[2] );
		// get the date
		if( preg_match( "/[0-9]{4}-[0,1][0-9]-[0-3][0-9]/", $info, $date ) ) {
			// put the date in the right format
			$date = explode( '-', $date[0] );
			$this->given_data['date'] = $date[2] . '.' . $date[1] . '.' . $date[0];
		}
		else $this->false_data['date'] = true; 
		// get the preacher and subject
		if( preg_match( "/[0-9]{4}-[0,1][0-9]-[0-3][0-9] *(.*)/", $info, $matches ) && preg_match( "/(.*)(\..{2,4})(\?dl=[0|1])$/", $matches[1], $infos ) && preg_match( "/(.*) *- *(.*)/", $infos[1], $matches ) ) {
			// save the matches
			$this->given_data['subject'] = $matches[1];
			$this->given_data['preacher'] = $matches[2];
		}
		else {
			$this->false_data['preacher'] = true;
			$this->false_data['subject'] = true;
		}
	}
	
	/*
	 * check if given videolink is valid
	 */
	private function verify_video( $videolink ) {
		return preg_match( "#^https?://(www\.)?vimeo\.com/[0-9]{9}$#", $videolink );
	}
	
	/*
	 * check if given audiolink is valid
	 */
	private function verify_audio( $audiolink ) {
		return preg_match( "#^https?://(www\.)?dropbox\.com/.+$#", $audiolink );
	}
	
	/*
	 * create the post
	 */
	public function create_post () {
		// check if we come from the verification form
		if( !isset( $_POST['inc_serm_nonce'] ) || !wp_verify_nonce( $_POST['inc_serm_nonce'], 'inc_serm_verify_info' ) || !check_admin_referer( 'inc_serm_verify_info', 'inc_serm_nonce' ) ) return;
		
		// check all the data is given
		$data = array( 'inc_serm_date' => 'date', 'inc_serm_subject' => 'subject', 'inc_serm_preacher' => 'preacher', 'inc_serm_video' => 'video', 'inc_serm_audio' => 'audio' );
		$error = false;
		foreach( $data as $key => $new ) {
			if( !isset( $_POST[ $key ] ) ) {
				$this->false_data[ $new ] = true;
				continue;
			}
			$this->given_data[ $new ] = $_POST[ $key ];
		}
		// check if something went wrong
		if( !empty( $this->false_data ) ) return false;
		
		// init variables for later use
		$tags = array();
		$i = 0;
		// check date and add to tags (the date is the only necessary argument)
		if( !preg_match( "/^[0-3][0-9].[0,1][0-9].[0-9]{4}$/", $this->given_data['date'] ) ) { $this->false_data['date'] = true; $this->form_no = 1; return false; }
		$this->given_data['date'] = esc_attr( $this->given_data['date'] );
		array_push( $tags, $this->given_data['date'] );
		
		// check preacher and add to tags
		if( !empty( $this->given_data['preacher'] ) ) {
			$this->given_data['preacher'] = esc_attr( $this->given_data['preacher'] );
			array_push( $tags, $this->given_data['preacher'] );
			$i++;
		}
		
		// check subject and add to tags
		if( !empty( $this->given_data['subject'] ) ) {
			$this->given_data['subject'] = esc_attr( $this->given_data['subject'] );
			array_push( $tags, $this->given_data['subject'] );
			// if the preacher is given as well, add a seperator
			if( !empty( $this->given_data['preacher'] ) ) $title = $this->given_data['subject'] . ' // ' . $this->given_data['preacher'];
			else $title = $this->given_data['subject'];
		}
		else {
			// if the preacher is given, add a seperator
			if( !empty( $this->given_data['preacher'] ) ) $title = sprintf( __( "Service on the %s", 'include_sermon' ), $this->given_data['date'] ) . ' // ' . $this->given_data['preacher'];
			else $title = sprintf( __( "Service on the %s", 'include_sermon' ), $this->given_data['date'] );
		}
		
		// validate the given links
		if( !empty( $this->given_data['video'] ) && !$this->verify_video( $this->given_data['video'] ) ) { $this->false_data['video'] = true; }
		if( !empty( $this->given_data['audio'] ) && !$this->verify_audio( $this->given_data['audio'] ) ) { $this->false_data['audio'] = true; }
		// check if something went wrong
		if( !empty( $this->false_data ) ) return;
		
		$video = '';
		$message = '';
		$button = '';
		
		/* generate messages if one or more links are missing */
			// if no videolink is given, show a message
			if( empty( $this->given_data['video'] ) && !empty( $this->given_data['audio'] ) ) {
				$message = '<div>' . __( 'We are sorry, but for technical reasons there is no video file from this service', 'include_sermon' ) . '</div>';
			}
			// if neither video- nor audiolink is given, show a message
			else if( empty( $this->given_data['video'] ) && empty( $this->given_data['audio'] ) ) {
				$message = '<div>' . __( 'We are sorry, but for technical reasons there is no audio or video file from this service', 'include_sermon' ) . '</div>';
			}
		
		
		/* generate iframe for video */
			if( !empty( $this->given_data['video'] ) ) {
				// splitting the video link into pieces
				$array = explode( '/', $this->given_data['video'] );
				// ... and put it in the right URL
				$videolink = 'https://player.vimeo.com/video/' . $array[3];
				// getting the saved options from the database
				$width = get_option( 'include-sermon-video-width' );
				$height = get_option( 'include-sermon-video-height' );
				// generate the iframe
				$video = '<div><iframe src="' . esc_url( $videolink ) . '" width="' . $width . '" height="' . $height . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
			}
		
		/* generate button for audio download */
			if( !empty( $this->given_data['audio'] ) ) {
				// check if the download parameter is there and replace it
				if( preg_match( '/\?dl=[0|1]$/', $this->given_data['audio'] ) ) $this->given_data['audio'] = str_replace( '?dl=0', '?dl=1', $this->given_data['audio'] );
				// if the download parameter isn't set yet, add it
				else $this->given_data['audio'] .= '?dl=1';
				// get the color settings from the database
				$button_color = '#'.get_option( 'include-sermon-button-color' );	// TODO(hornigal): put this in a sylesheet that is included once if a sermon post is shown
				$color = '#'.get_option( 'include-sermon-color' );
				// generate the button
				$button = '<div><a style="text-decoration:none; background-color:' . $button_color . '; border-radius:3px; padding:5px; color:' . $color . '; border-color:black; border:1px;" href="' . esc_url( $this->given_data['audio'] ) . '" title="' . __( "Download as MP3" ,'include_sermon' ) . '" target="_blank">' . __( "Download as MP3", 'include_sermon' ) . '</a></div>';
			}
		
		//getting the stored category and post status for the post
		$category_id = get_option( 'include-sermon-category' );
		$post_status = get_option( 'include-sermon-post-status' );
		
		//putting all collected data into an array for posting it
		$post = array(
						'post_content'   => $video . $message . $button,
						'post_title'     => $title,
						'post_status'    => $post_status,
						'post_type'      => 'post',
						'post_category'  => array($category_id),
						'tags_input'     => $tags
					); 
		
		//creating the post
		if( !$id = wp_insert_post($post) ) return false;	// TODO(hornigal): tell user something went wrong
		
		// send the user to the created post
		wp_redirect( get_permalink( $id ) );
	}
}

$HS_IncludeSermonForm = NEW HS_IncludeSermonForm;
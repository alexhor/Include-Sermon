<?php
defined( 'ABSPATH' ) or die( "Please don't do that!" );

class HS_IncludeSermonForm {
	/*adding all needed actions and filters*/
	function __construct () {
		//hook for admin menus for the plugin
		add_action( 'admin_menu', array( &$this, 'include_sermon_menu_posts' ) );
		//hooking in init action for saving from data
		add_action( 'init', array( &$this, 'include_sermon_post' ) );
	}
	
	/*function with the menu-building code to setup the menu for the plugin*/
	function include_sermon_menu_posts () {
		//Add new sub-menus under Posts
		if( !isset( $_POST['inc_serm_check'] ) ) { add_posts_page( __( 'New Sermon', 'include_sermon' ), __( 'New Sermon', 'include_sermon' ), 'edit_posts', 'include_sermon', array( &$this, 'include_sermon_form' ) );}
	}
	
	/*function to create a new sermon post*/
	function include_sermon_form() {
	?>
	<div class='wrap'>
		<form action='' method='post'>
			<!--input for the videolink from Vimeo (at the moment only videos from Vimeo can be used for this-->
			<label for='video'><p><?php _e( 'The Videolink from Vimeo (e.g.: http://vimeo.com/89224153)', 'include_sermon' );?></p></label>
			<input type='input' name='inc_serm_video' placeholder="<?php _e( 'Videolink', 'include_sermon' );?>" required='required'/>
			<br><br>
			
			<!--input for the link to your sermon audiofile-->
			<label for='audio'><p><?php _e( 'The shared link from Dropbox for the Audio file', 'include_sermon' );?></p></label>
			<input type='input' name='inc_serm_audio' placeholder="<?php _e( 'Audiolink', 'include_sermon' );?>" required='required'/>
			<br>
			
			<!--instruction for what to do if there is no audio- or videofile available-->
			<p><?php _e( '(If there is no audio- or videofile for some reason, just type "no")', 'include_sermon' );?></p>
			<input type='hidden' name='inc_serm_gansgeheime' value="ADpuer"/>
			
			<!--button to send the form-->
			<p class='submit'><input type="submit" class='button-primary' value="<?php _e( 'Post Sermon', 'include_sermon' );?>"/></p>
		</form>
	</div>
	<?php
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*function to create the post*/
	function include_sermon_post (){
		
		/*checking if the user send the form*/
		if( isset( $_POST['inc_serm_gansgeheime'] ) && esc_attr( $_POST['inc_serm_gansgeheime'] ) == "ADpuer" ){
		
			//decoding the given download link
			$audiolink = esc_attr( urldecode( $_POST['inc_serm_audio'] ) );
			
			//checking if an audiolink is given
			if( $audiolink != 'no' ){                
                //the '-' is a marking point marking the ending of the subject and beginning of the preachers name and we want to have the last of these '-'
                preg_match_all( '/(.*)-(.*)/', $audiolink , $matches );
				$preacher = trim( preg_replace( '/.mp3\?dl=[0,1]/', '', end( $matches )[0] ) );
                
                //finding dates in the url
                preg_match_all( '/[0-9]{4}-[0,1][0-9]-[0-3][0-9](.*)/', $audiolink, $matches );
                //the last date should be the one in the filename (there could be dates in the url as well)
                $subject = end( $matches )[0];
                //getting everything after the subject
                preg_match_all( '/-(.*)/', $subject, $matches );
                //... and deleting it from the subject string
                $subject = trim( substr( $subject, 0, strlen( $subject ) - strlen( end( $matches[0] ) ) ) );
			}
			
			
            if( $_POST['inc_serm_video'] != 'no' ){
                //splitting the video link into pieces
                $array = explode( '/', esc_attr( $_POST['inc_serm_video'] ) );
                //... and including it into the right URL
                $videolink = "//player.vimeo.com/video/".$array[3];
            }
			
			//getting some required data from the database
			$button_color = '#'.get_option( 'include-sermon-button-color' );
			$color = '#'.get_option( 'include-sermon-color' );
			
			//checking if there is no video file given
			if( $_POST['inc_serm_video'] == 'no' ){
				//checking if there is no audio file given either
				if( $audiolink == 'no' ){
					//putting an information message into the content that there is no audio- and video file from this service
					$content = __( "We are sorry, but for technical reasons there is no audio or video file from this service", 'include_sermon' );
				}
				else{
					//and activating the direct download from Dropbox, when clicking on the link
					$audiolink = str_replace( 'dl=0', 'dl=1', $audiolink );
					$content = __( "We are sorry, but for technical reasons there is no video file from this service", 'include_sermon' ) . '<br><a style="text-decoration:none; background-color:' . $button_color . '; border-radius:3px; padding:5px; color:' . $color . '; border-color:black; border:1px;" href=' . $audiolink . ' title="' . __( "Download as MP3", 'include_sermon' ) . ' target="_blank">' . __( "Download as MP3", 'include_sermon' ) . "</a>";
				}
			}
			//checking if there is no audio file given
			elseif( $audiolink == 'no' ){
				//putting the video into the content
				$content = "<iframe src='$videolink' width='400' height='225' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
			}
			else{
				//activating the direct download from Dropbox, when clicking on the link
				$audiolink = str_replace( 'dl=0', 'dl=1', $audiolink );
				
				//getting the saved options from the database
				$width = get_option( 'include-sermon-video-width' );
				$height = get_option( 'include-sermon-video-height' );
				
				//putting the audio download link and the video into the content
				$content = '<div>
								<iframe src="' . $videolink . '" width="' . $width . '" height="' . $height . '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
							</div>
							<div>
								<a style="text-decoration:none; background-color:' . $button_color . '; border-radius:3px; padding:5px; color:' . $color . '; border-color:black; border:1px;" href="' . $audiolink . '" title="' . __( "Download as MP3" ,'include_sermon' ) . '" target="_blank">' . __( "Download as MP3", 'include_sermon' ) . '</a>
							</div>';
			}
			
			//setting the post title
			$title = $subject . ' // ' . $preacher;
			//getting the stored category and post status for the post
			$category_id = get_option( 'include-sermon-category' );
			$post_status = get_option( 'include-sermon-post-status' );
			
			//putting all collected data into an array for posting it
			$post = array(
							'post_content'   => $content,// The full text of the post.
							'post_title'     => $title, // The title of your post.
							'post_status'    => $post_status, // Default 'draft'.
							'post_type'      => 'post', // Default 'post'.
							'post_category'  => array($category_id), // Default empty.
							'tags_input'     => array( $preacher, $subject ) // Default empty.
						); 
			
			//creating the post
			wp_insert_post($post);
		}
	}
}

$HS_IncludeSermonForm = NEW HS_IncludeSermonForm;
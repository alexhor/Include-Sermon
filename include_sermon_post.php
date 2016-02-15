<?php
defined( 'ABSPATH' ) or die( "Please don't do that!" );

class HS_IncludeSermonPost {
	/*adding all needed actions and filters*/
	function __construct (){
		//adding the function for creating the post
		add_action( 'init', array( &$this, 'include_sermon_post' ) );
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
					$content = "Wegen technischer Probleme gibt es keine Audio- und Videoaufzeichnung von diesem Gottesdienst.";
				}
				else{
					//and activating the direct download from Dropbox, when clicking on the link
					$audiolink = str_replace( 'dl=0', 'dl=1', $audiolink );
					$content = "Wegen technischer Probleme gibt es keine Videoaufzeichnung von diesem Gottesdienst.<br><a style=\"text-decoration:none; background-color:$button_color; border-radius:3px; padding:5px; color:$color; border-color:black; border:1px;\" href='$audiolink' title='Download als MP3' target='_blank'>Download als MP3</a>";
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
				$content = "<div>
								<iframe src='$videolink' width='$width' height='$height' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
							</div>
							<div>
								<a style=\"text-decoration:none; background-color:$button_color; border-radius:3px; padding:5px; color:$color; border-color:black; border:1px;\" href='$audiolink' title='Download als MP3' target='_blank'>Download als MP3</a>
							</div>";
			}
			
			//setting the post title
			$title = $subject.' // '.$preacher;
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

$HS_IncludeSermonPost = NEW HS_IncludeSermonPost;
<?php
class HS_IncludeSermonPost {
	/*adding all needed actions and filters*/
	function __construct (){
		//adding the function for creating the post
		add_action( 'init', array( &$this, 'include_sermon_post' ) );
	}
	
	/*function to create the post*/
	function include_sermon_post (){
		
		/*checking if the user send the form*/
		if( isset( $_POST['inc_serm_gansgeheime'] ) && $_POST['inc_serm_gansgeheime'] != "ADp" ){
		
			//decoding the given download link
			$audiolink = urldecode( $_POST['inc_serm_audio'] );
			
			//checking if an audiolink is given
			if( $audiolink != 'no' ){
				//splitting the audio link into pieces
				$explode = explode( ' ', $audiolink );
				//setting reference var
				$i = 0;
				
				//going through the splitted audio link
				while( isset($explode[$i] ) ){
					//the '-' is a marking point who marks the ending of the subject and beginning of the ...s name and we want to have the last of these '-'
					if( $explode[$i] == '-' ){ $a = $i; }
					$i++;
				}
				
				//starting to turn the splitted audio link into the subjects name
				$subject = $explode[1];
				
				//going through the splitted audio link to build the subjects name
				for( $i=2; $i != $a; $i++ ){
					$subject .= ' '.$explode[$i];
					if( $i > 20 ){ break; }
				}

				$a++;
				//starting to turn the splitted audio link into the ...s name
				$prediger = $explode[$a];
				$a++;
				
				//going through the splitted audio link to build the subjects name
				while( isset( $explode[$a] ) ){
					$prediger .= ' '.$explode[$a];
					$a++;
				}
				//cutting off the last few signs, not belonging to the ...s name
				$prediger = substr( $prediger, 0, -9);
			}
			
			
			//splitting the video link into pieces
			$array = explode( '/', $_POST['inc_serm_video'] );
			//... and including it into the right URL
			$videolink = "//player.vimeo.com/video/".$array[3];
			
			//getting some required data from the database
			$button_color = get_option( 'include-sermon-button-color' );
			$color = get_option( 'include-sermon-color' );
			
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
					$content = "Wegen technischer Probleme gibt es keine Videoaufzeichnung von diesem Gottesdienst. <a style=\"text-decoration:none; background-color:$button_color; border-radius:3px; padding:5px; color:$color; border-color:black; border:1px;\" href='$audiolink' title='Download als MP3' target='_blank'>Download als MP3</a>";
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
			$title = $subject.' // '.$prediger;
			//getting the stored category for the post
			$category_id = get_option( 'include-sermon-category' );
			
			//putting all collected data into an array for posting it
			$post = array(
							'post_content'   => $content,// The full text of the post.
							'post_title'     => $title, // The title of your post.
							'post_status'    => 'publish', // Default 'draft'.
							'post_type'      => 'post', // Default 'post'.
							'post_category'  => array($category_id), // Default empty.
							'tags_input'     => array( $prediger, $subject ) // Default empty.
						); 
			
			//creating the post
			wp_insert_post($post);
		}
	}
}

$HS_IncludeSermonPost = NEW HS_IncludeSermonPost;
<?php
class HS_IncludeSermonForm {
	/*adding all needed actions and filters*/
	function __construct () {
		//hook for admin menus for the plugin
		add_action( 'admin_menu', array( &$this, 'include_sermon_menu_posts' ) );
	}
	
	/*function with the menu-building code to setup the menu for the plugin*/
	function include_sermon_menu_posts () {
		//Add new sub-menus under Posts
		if( !isset( $_POST['inc_serm_check'] ) ) { add_posts_page( 'New Sermon', 'New Sermon', 'edit_posts', 'include_sermon', array( &$this, 'include_sermon_form' ) );}
	}
	
	/*function to create a new sermon post*/
	function include_sermon_form() {
	?>
	<div class='wrap'>
		<form action='' method='post'>
			<!--input for the videolink from Vimeo (at the moment only videos from Vimeo can be used for this-->
			<label for='video'><p><?php _e( 'Den Videolink des Videos von Vimeo (in etwa so: http://vimeo.com/89224153)', 'include_sermon' );?></p></label>
			<input type='input' name='inc_serm_video' placeholder="<?php _e( 'Videolink', 'include_sermon' );?>" required='required'/>
			<br><br>
			
			<!--input for the link to your sermon audiofile-->
			<label for='audio'><p><?php _E( 'In der Dropbox auf die Audiodatei mit rechts klicken und auf Link freigeben gehen.<br />Dann Rechtsklick auf den Download Button und Link-Adresse kopieren und hier unter Audiolink einfügen', 'include_sermon' );?></p></label>
			<input type='input' name='inc_serm_audio' placeholder="<?php _e( 'Audiolink', 'include_sermon' );?>" required='required'/>
			<br>
			
			<!--instruction for what to do if there is no audio- or videofile available-->
			<p><?php _e( '(Wenn es aus irgendwelchen Gründen keine Video-und/oder Audiopredigt gibt, dann einfach "no" in die Felder eintragen)', 'include_sermon' );?></p>
			<input type='hidden' name='inc_serm_gansgeheime' value="ADpuer0z wf4rwedf"/>
			
			<!--button to send the form-->
			<p class='submit'><input type="submit" class='button-primary' value="<?php _e( 'Post erstellen', 'include_sermon' );?>"/></p>
		</form>
	</div>
	<?php
	}
}

$HS_IncludeSermonForm = NEW HS_IncludeSermonForm;
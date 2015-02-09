<?php
class HS_IncludeSermonGeneral {
	function __construct() {
		add_action( 'admin_notices', array( &$this,'notification' ) );
		add_action( 'wp_before_admin_bar_render', array( &$this, 'include_sermon_edit_adminbar' ) );
    }

    function notification() {
		if ( !get_option( 'include-sermon-options-set' ) ){
			echo "<div class='error' style='font-size:20px; height:27px;'>".__( 'Please edit the settings for Include Sermon', 'include_sermon' ).'</div>';
		}
    }
	
	function include_sermon_edit_adminbar (){
		global $wp_admin_bar;
		$wp_admin_bar->add_menu( array( parent => 'new-content', title => __( 'Sermon', 'include_sermon' ), href => admin_url( 'edit.php?page=include_sermon' ) ) );
	}
}

 $HS_IncludeSermonGeneral = NEW HS_IncludeSermonGeneral;
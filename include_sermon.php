<?php
/**
 * Plugin Name: Include Sermon
 * Description: A Plugin to automatically include a video- and audiofile, the title of the sermon and the preachers name onti your Wordpress blog
 * Version: 1.3
 * Author: Hornig Software
 * Author URI: http://hornig-software.com
 * License: GPL2
 */
 
 /*  Copyright 2014  Hornig Software (email : alexander@hornig-software.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
defined( 'ABSPATH' ) or die( "Please don't do that!" );

__("<link rel='stylesheet' type='text/css' href='style.css'>");

/*turning debugging on for testing*/
define('WP_DEBUG', true);

/*Setting standard settings when plugin is beeing installed*/
function HS_IncludeSermonInstall() {
	$button_color = "0076B3";
	$category = get_option( 'default_category' );
	$color = "FFFFFF";
	$width = "375";
	$height = "210";
	$post_status = "draft";
	add_option( 'include-sermon-button-color', $button_color );
	add_option( 'include-sermon-category', $category);
	add_option( 'include-sermon-color', $color);
	add_option( 'include-sermon-video-width', $width);
	add_option( 'include-sermon-video-height', $height);
	add_option( 'include-sermon-post-status', $post_status);
}
register_activation_hook( __FILE__, 'HS_IncludeSermonInstall' );

/*textfiles to internationalize the plugin*/
load_plugin_textdomain( 'include-sermon-lang', false, basename( dirname( __FILE__ ) ) . '/lang' );
 
/*file for creating the post*/
require_once( 'include_sermon_post.php' );
 
/*file for adding the from to create a post*/
require_once( 'include_sermon_form.php' );
 
/*file for adding the options in the administration area*/
require_once( 'include_sermon_options.php' );

/*adding the admin bar entry for adding new sermon posts*/
function include_sermon_edit_adminbar (){
	global $wp_admin_bar;
	$wp_admin_bar->add_menu( array( parent => 'new-content', title => __( 'Sermon', 'include_sermon' ), href => admin_url( 'edit.php?page=include_sermon' ) ) );
}
add_action( 'wp_before_admin_bar_render', 'include_sermon_edit_adminbar' );
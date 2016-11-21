<?php
/**
 * Plugin Name: Include Sermon
 * Description: A Plugin to automatically include a video- and audiofile, the title of the sermon and the preachers name onto your Wordpress blog
 * Version: 1.3
 * Author: Hornig Software
 * Author URI: http://hornig-software.com
 * License: MIT
 */
 
/*  Copyright(c) 2014  Hornig Software (email : alexander@hornig-software.com)

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:
 
 
 The above copyright notice and this permission notice shall be included in
 all copies or substantial portions of the Software.
 
 
 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.  IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 THE SOFTWARE.
*/
defined( 'ABSPATH' ) or die( "Please don't do that!" );

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
load_plugin_textdomain( 'include_sermon', false, basename( dirname( __FILE__ ) ) . '/lang' );
 
/*file for creating the post*/
require_once( 'include_sermon_post.php' );
 
/*file for adding the from to create a post*/
require_once( 'include_sermon_form.php' );
 
/*file for adding the options in the administration area*/
require_once( 'include_sermon_options.php' );

/*adding the admin bar entry for adding new sermon posts*/
function include_sermon_edit_adminbar (){
	global $wp_admin_bar;
	$wp_admin_bar->add_menu( array( 'id' => 'new-sermon', 'parent' => 'new-content', 'title' => __( 'Sermon', 'include_sermon' ), 'href' => admin_url( 'edit.php?page=include_sermon' ) ) );
}
add_action( 'wp_before_admin_bar_render', 'include_sermon_edit_adminbar' );

/*addin settings option on plugin activation page*/
function include_sermon_plugin_settings_link( $actions, $user_object ) {
    $new['settings'] = '<a href="' . admin_url( 'options-general.php?page=include_sermon' ) . '">' . __( 'Settings', 'include_sermon' )  . '</a>';
    return array_merge( $new, $actions );
}
add_filter( "plugin_action_links_" . plugin_basename( __FILE__ ), 'include_sermon_plugin_settings_link', 10, 2 );

<?php
/**
 * Plugin Name: Include Sermon
 * Description: A Plugin to automatically include a video- and audiofile, the title of the sermon and the preachers name onti your Wordpress blog
 * Version: 1.0
 * Author: Alexander Hornig
 * License: GPL2
 */
 
 /*  Copyright 2014  Alexander Hornig  (email : alexanderhornig@hotmail.com)

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
?><link rel='stylesheet' type='text/css' href='style.css'><?php

/*turning debugging on for testing*/
define('WP_DEBUG', true);

/*textfiles to internationalize the plugin*/
load_plugin_textdomain( 'include-sermon-lang', false, basename( dirname( __FILE__ ) ) . '/lang' );
 
/*file for creating the post*/
require_once( 'include_sermon_post.php' );
 
/*file for adding the from to create a post*/
require_once( 'include_sermon_form.php' );
 
/*file for adding the options in the administration area*/
require_once( 'include_sermon_options.php' );
 
/*file for general functions*/
require_once( 'general.php' );
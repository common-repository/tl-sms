<?php
/*
Plugin Name: Text Local SMS
Plugin URI: http://wp-plugins.in/TLSMS
Description: Best SMS plugin for WordPress, send SMS in WordPress easily, all countries support.
Version: 1.0.0
Author: Alobaidi
Author URI: http://wp-plugins.in
License: GPLv2 or later
*/

/*  Copyright 2016 Alobaidi (email: wp-plugins@outlook.com)

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


defined('ABSPATH') or die('Silence is Golden.');


function tlsms_plugin_row_meta($links, $file) {
	if ( strpos( $file, 'tl-sms.php' ) !== false ) {
		$new_links = array(
							'<a href="http://wp-plugins.in/TLSMS" target="_blank">Explanation of Use</a>',
							'<a href="https://profiles.wordpress.org/alobaidi#content-plugins" target="_blank">More Plugins</a>',
							'<a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=24967" target="_blank">Elegant Themes</a>'
						);
		
		$links = array_merge( $links, $new_links );
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'tlsms_plugin_row_meta', 10, 2 );


function tlsms_include_js_css_admin(){
	wp_enqueue_style( 'tlsms-css', plugins_url( '/css/tlsms-style.css', __FILE__ ), array(), null);
	wp_enqueue_script( 'tlsms-ajax', plugins_url( '/js/tlsms-ajax.js', __FILE__ ), array('jquery'), null, false);
}
add_action('admin_enqueue_scripts', 'tlsms_include_js_css_admin');


include_once dirname( __FILE__ ). '/dashboard-widget.php';

include_once dirname( __FILE__ ). '/settings.php';
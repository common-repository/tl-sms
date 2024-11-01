<?php

defined('ABSPATH') or die('Silence is Golden.');


// if not uninstalled plugin
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit(); // out.


/*esle:
	if uninstalled plugin, this options will be deleted.
*/
delete_option('tlsms_api_key');
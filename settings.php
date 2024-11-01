<?php

defined( 'ABSPATH' ) or die( 'Silence is Golden.' );


function tlsms_add_setting(){
	add_settings_field( "tlsms_api_key", 'SMS API Key', "tlsms_setting_callback", "general", 'default', array('label_for' => 'tlsms_api_key' ) );
	register_setting( 'general', 'tlsms_api_key' );
}
add_action( 'admin_init', 'tlsms_add_setting' );


function tlsms_setting_callback(){
	?>
    	<input id="tlsms_api_key" class="regular-text" name="tlsms_api_key" type="text" value="<?php echo esc_attr( get_option('tlsms_api_key') ); ?>">
    	<p class="description">Enter your API Key to send SMS. <a href="http://www.txtlocal.co.uk/?tlrx=348100" target="_blank">Get API Key</a></p>
    <?php
}
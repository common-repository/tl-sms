<?php

defined('ABSPATH') or die('Silence is Golden.');


function tlsms_dashboard_widget() {
	wp_add_dashboard_widget('tlsms_dashboard_widget_id', 'Send SMS', 'tlsms_dashboard_widget_callback');	
}
add_action('wp_dashboard_setup', 'tlsms_dashboard_widget');


function tlsms_dashboard_widget_callback() {
	?>
	<form action="<?php echo esc_url( admin_url('/') ); ?>" method="POST" autocomplete="off" id="tlsms">

		<p>
			<label for="tlsms_numbers">Mobile Number
				<input name="tlsms_numbers" id="tlsms_numbers" type="text" value="" class="regular-text">
				<span class="tlsms-desc">Mobile number in international format (i.e. 447123456789), without "+".<br>You can send SMS to more than 1 mobile, use comma (i.e 447123456789, 447123123123).</span>
			</label>
		</p>

		<p>
			<label for="tlsms_sender">Sender Name
				<input name="tlsms_sender" id="tlsms_sender" type="text" value="" class="regular-text">
				<span class="tlsms-desc">Length of sender name should be less than 12, 11 maximum and 3 minimum.</span>
			</label>
		</p>

		<p>
			<label for="tlsms_message">Message
				<textarea name="tlsms_message" id="tlsms_message"></textarea>
				<span class="tlsms-desc">In English only. Length of message should be less than 767, 766 maximum and 1 minimum.</span>
			</label>
		</p>

		<p><label for="tlsms_test"><input name="tlsms_test" id="tlsms_test" type="checkbox" value="1">Test Mode <span class="tlsms-desc">(no messages will be sent and your credit balance will be unaffected).</span></label></p>

		<p class="tlsms_sp"><input name="tlsms_submit" id="tlsms_submit" class="button button-primary" type="submit" value="Send"></p>

		<p id="tlsms-ajax-icon"><img src="<?php echo esc_url( plugins_url('/images/ajax-icon.gif', __FILE__) ); ?>"></p>

		<div id="tlsms-result"></div>

	</form>
	<?php
}


function tlsms_send_message(){
	if( $_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['tlsms_submit']) ){
		
		if( empty($_POST['tlsms_numbers']) or empty($_POST['tlsms_sender']) or empty($_POST['tlsms_message']) ){
			echo '<p class="tlsms-error">Please fill all fields.</p>';
			exit();
		}

		if( !preg_match( '/^[0-9-,\s]+$/', $_POST['tlsms_numbers'] ) ){
			echo '<p class="tlsms-error">Characters are not allowed! Digits only, or comma between each mobile number only.</p>';
			exit();
		}

		if( strlen($_POST['tlsms_sender']) > 11 ){
			echo '<p class="tlsms-error">Length of sender name should be less than 12, 11 maximum and 3 minimum.</p>';
			exit();
		}

		if( strlen($_POST['tlsms_sender']) < 3 ){
			echo '<p class="tlsms-error">Length of sender name should be more than 2, 11 maximum and 3 minimum.</p>';
			exit();
		}

		if( strlen($_POST['tlsms_message']) > 766 ){
			echo '<p class="tlsms-error">Length of message should be less than 767, 766 maximum and 1 minimum.</p>';
			exit();
		}

		if( !get_option('tlsms_api_key') ){
			$admin_url = admin_url('options-general.php');
			echo '<p class="tlsms-error">Please enter your API Key in <a href="'.$admin_url.'" target="_blank">Settings</a>.</p>';
			exit();
		}

		$api_key = get_option('tlsms_api_key');
		$get_numbers = trim($_POST['tlsms_numbers'], '+');
		$numbers = sanitize_text_field($get_numbers);
		$sender = sanitize_text_field($_POST['tlsms_sender']);
		$message = sanitize_text_field($_POST['tlsms_message']);

		if( isset($_POST['tlsms_test']) ){
			$test = true;
		}else{
			$test = false;
		}

		$data = array(
					'apiKey' 	=> $api_key,
					'numbers' 	=> implode(',', array($numbers) ),
					'sender' 	=> urlencode($sender),
					'message' 	=> rawurlencode($message),
					'test'		=> $test
				);

		$post_args = array('body' => $data);

		$api = wp_remote_post('http://api.txtlocal.com/send/', $post_args);

		$result = wp_remote_retrieve_body($api);

		$json = json_decode($result, true);

		$status = $json['status'];

		if( isset($_POST['tlsms_test']) ){
			if( $status == 'success' ){
				$class = 'tlsms-sent';
			}else{
				$class = 'tlsms-error';
			}
			echo '<p class="'.$class.'">Testing is '.$status.':</p>';
			echo "<pre>";
			print_r($json);
			echo "</pre>";
			exit();
		}

		if( $status == 'success' ){
			echo '<script>document.getElementById("tlsms_message").value = "";</script>';
			echo '<p class="tlsms-sent">Your SMS message was sent!</p>';
			exit();
		}else{
			echo '<p class="tlsms-error">Something went wrong:</p>';
			echo "<pre>";
			print_r($json);
			echo "</pre>";
			exit();
		}

		exit();
	}
}
add_action('admin_init', 'tlsms_send_message');
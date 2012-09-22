<?php

session_start();

require_once('config.php');

$step_3_url = 'http://yourdomain/step-3.php';

if ( isset( $_GET['oauth_token'] ) && isset( $_GET['uid'] ) && isset( $_SESSION['myapp'] ) ) {
	
	$ch = curl_init(); 
	
	$headers = array( 'Authorization: OAuth oauth_version="1.0", oauth_signature_method="PLAINTEXT", oauth_consumer_key="' . $app_key . '", oauth_token="'  .$_GET['oauth_token'] . '", oauth_signature="' . $app_secret . '&' . $_SESSION['myapp']['oauth_request_token_secret'] . '"' );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers ); 
	
	curl_setopt( $ch, CURLOPT_URL, "https://api.dropbox.com/1/oauth/access_token" );  
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );  
	$access_token_response = curl_exec( $ch );
	
	parse_str( $access_token_response, $parsed_access_token );

	error_log( $access_token_response );
	
	$json_access = json_decode( $access_token_response );

	if ( isset( $json_access->error ) ) {
		echo '<br><br>FATAL ERROR: ' . $json_access->error . '<br><br>';
		die();
	}
	
	$_SESSION['myapp']['uid'] = $parsed_access_token['uid'];
	$_SESSION['myapp']['oauth_access_token'] = $parsed_access_token['oauth_token'];
	$_SESSION['myapp']['oauth_access_token_secret'] = $parsed_access_token['oauth_token_secret'];
	
	header( 'Location: ' . $step_3_url );
	
}
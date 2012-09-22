<?php

session_start();

require_once('config.php');

$step_2_url = 'http://yourdomain/step-2.php';

$ch = curl_init(); 

$headers = array( 'Authorization: OAuth oauth_version="1.0", oauth_signature_method="PLAINTEXT", oauth_consumer_key="' . $app_key . '", oauth_signature="' . $app_secret . '&"' );

curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers ); 
curl_setopt( $ch, CURLOPT_URL, "https://api.dropbox.com/1/oauth/request_token" );  
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );  
$request_token_response = curl_exec( $ch );

parse_str( $request_token_response, $parsed_request_token );

$json_access = json_decode( $request_token_response );

if ( isset( $json_access->error ) ) {
	echo '<br><br>FATAL ERROR: ' . $json_access->error . '<br><br>';
	die();
}

$_SESSION['myapp'] = array();
$_SESSION['myapp']['oauth_request_token'] = $parsed_request_token['oauth_token'];
$_SESSION['myapp']['oauth_request_token_secret'] = $parsed_request_token['oauth_token_secret'];

header( 'Location: https://www.dropbox.com/1/oauth/authorize?oauth_token=' . $parsed_request_token['oauth_token'] . '&oauth_callback=' . $step_2_url );

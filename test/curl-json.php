<?php

$url = 'http://10.0.1.149:8080/SimpleTalk/test/henry/notify-sync';
$ch = curl_init( $url );
# Setup request to send json via POST.
$payload = json_encode( array( "member_id"=> "Samma" , "sync_type" => "A" , "timestampe" => "123456789") );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
# Setup content-type as JSON
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
# Return response instead of printing.
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
# Basic Authentication
$username = "api";
$password = "dvp";
curl_setopt( $ch, CURLOPT_USERPWD, $username . ":" . $password );
# Send request.
$response = curl_exec( $ch );
# Close Resource
curl_close( $ch );
# Parse to Array
$result = json_decode( $response, true );
# Print response.
echo var_dump($result);

<?php
if (isset ( $_REQUEST ['device_id'] )) {
	$deviceId = $_REQUEST ['device_id'];
	$mode = $_REQUEST ['mode'];
	$version = $_REQUEST ['version'];
	$status = 0; // nothing
	             // echo $version . '<br>';
	$data = file ( "./{$deviceId}/update/version.txt" );
	foreach ( $data as $v ) {
		$line = explode ( ":", $v );
		if ($line [0] == 'ver') {
			$newver = trim ( $line [1] );
			// echo "new: {$newver}";
		} else if ($line [0] == 'len') {
			$newlen = $line [1];
			// echo "len: {$newlen}";
		}
	}
	if ($version < $newver) {
		echo $newlen;
	}
}
?>
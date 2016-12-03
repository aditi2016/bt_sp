<?php
	session_start();
	function httpGet($url){
	    $ch = curl_init();

	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	//  curl_setopt($ch,CURLOPT_HEADER, false);

	    $output=curl_exec($ch);

	    curl_close($ch);
	    return $output;
	}
	$dbHandle = mysqli_connect("localhost","root","redhat111111","blueteam_service_providers");
	$dbHandleBluenet = mysqli_connect("localhost","root","redhat111111","bluenet");
	
?>
<?php
session_start();
include_once 'functions.php';
if (isset($_POST['name'])) {
	
    $name = mysqli_real_escape_string($dbHandle,$_POST['name']);
	$mobile = mysqli_real_escape_string($dbHandle,$_POST['mobile']);
	$date = date("Y-m-d H:i:s");  
    mysqli_query($dbHandle, "INSERT INTO bluenet_v3.user(name, mobile, password, creation) 
                                VALUES ('$name', '$mobile', '$mobile', '$date');");
    $id = mysqli_insert_id($dbHandle);
    $data = httpGet($url);
	$username = "rajnish90";
    $password = "redhat123";
    $senderid = "BLUETM";
    $message = "Dear Customer,\nThanks for feedback.\nYour Blueteam paswword is ".$mobile."\nContact us\n9599075955\nhttp://blueteam.in/app/";
    $url = "http://www.smsjust.com/blank/sms/user/urlsms.php?".
        "username=".$username.
        "&pass=".$password.
        "&senderid=".$senderid.
        "&dest_mobileno=".$mobile.
        "&msgtype=TXT".
        "&message=".urlencode($message).
        "&response=Y"
        ;
    echo $id ;
		
}

mysqli_close($dbHandle);
?>
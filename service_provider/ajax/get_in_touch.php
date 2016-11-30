<?php
session_start();
include_once 'functions.php';
if (isset($_POST['mobile'])) {
	
	$mobile = $_POST['mobile'];
	
    $username = "rajnish90";
    $password = "redhat123";
    $senderid = "BLUETM";
    $message = "Dear Customer,\nThanks for contacting us.\nContact us\n9599075955\nhttp://blueteam.in/app/";
    $url = "http://www.smsjust.com/blank/sms/user/urlsms.php?".
        "username=".$username.
        "&pass=".$password.
        "&senderid=".$senderid.
        "&dest_mobileno=".$mobile.
        "&msgtype=TXT".
        "&message=".urlencode($message).
        "&response=Y"
        ;
    mysqli_query($dbHandle, "INSERT INTO contact_requests(mobile, message) VALUES ('$mobile', '$message');");
    $data = httpGet($url);
	
    echo "Succesfully" ;
		
}

mysqli_close($dbHandle);
?>
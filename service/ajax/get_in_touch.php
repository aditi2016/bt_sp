<?php
session_start();

if (isset($_POST['mobile'])) {
	
	$mobile = $_POST['mobile'];
	
    $username = "rajnish90";
    $password = "redhat123";
    $senderid = "BLUETM";
    $message = "Dear Customer, \n Thanks for contacting us. \n Contact us \n 9599075955 \n http://blueteam.in/app/";
    $url = "http://www.smsjust.com/blank/sms/user/urlsms.php?".
        "username=".$username.
        "&pass=".$password.
        "&senderid=".$senderid.
        "&dest_mobileno=".$mobile.
        "&msgtype=TXT".
        "&message=".urlencode($message).
        "&response=Y"
        ;
    //echo $url;
    $data = httpGet($url);
	
    echo "Succesfully" ;
		
}
function httpGet($url){
    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
//  curl_setopt($ch,CURLOPT_HEADER, false);

    $output=curl_exec($ch);

    curl_close($ch);
    return $output;
}
?>
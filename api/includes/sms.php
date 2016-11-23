<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 3/9/16
 * Time: 11:01 PM

sendSMS("9599075955", "test sms")
 *
 * www.smsjust.com/blank/sms/user/urlsms.php?username=rajnish90&pass=redhat12345
 * &senderid=BLUETM&dest_mobileno=9560625626&msgtype=TXT&message=testbuapi&response=Y

 */

function sendSMS($to, $message){
    $username = "rajnish90";
    $password = "redhat123";
    $senderid = "BLUETM";

    $url = "http://www.smsjust.com/blank/sms/user/urlsms.php?".
        "username=".$username.
        "&pass=".$password.
        "&senderid=".$senderid.
        "&dest_mobileno=".$to.
        "&msgtype=TXT".
        "&message=".urlencode($message).
        "&response=Y"
        ;
    //echo $url;
    return httpGet($url);
}

function sendProSMS($to, $message){
    $username = "rajnish90";
    $password = "redhat123";
    $senderid = "BLUETM";

    $url = "http://www.smsjust.com/blank/sms/user/urlsms.php?".
        "username=".$username.
        "&pass=".$password.
        "&senderid=".$senderid.
        "&dest_mobileno=".$to.
        "&msgtype=TXT".
        "&message=".urlencode($message).
        "&response=Y"
    ;
    //echo $url;
    return httpGet($url);
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
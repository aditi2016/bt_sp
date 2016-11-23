<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 3/9/16
 * Time: 11:01 PM
 */

function internalServerError($error)
{
    header('HTTP/1.1 500 Internal Server Error');
    $emailIds = array("rahul_lahoria@yahoo.com", "pwnpnwr785@gmail.com", "vikas.niper2012@gmail.com", "kumar.anil8892@yahoo.com");
    foreach ($emailIds as $to)
        sendMail($to, "Alert! error occurred in apis", $error);
}

function apiTakingMoreTime($error,$time)
{

    $emailIds = array("rahul_lahoria@yahoo.com", "pwnpnwr785@gmail.com", "vikas.niper2012@gmail.com", "kumar.anil8892@yahoo.com");
    foreach ($emailIds as $to)
        sendMail($to, "Alert! Api Took ".$time, $error);
}

function sendMail($to, $subject, $message)
{

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= 'From: <blueteam.requests@blueteam.in>' . "\r\n";
    //$headers .= 'Cc: myboss@example.com' . "\r\n";

    mail($to, $subject, $message, $headers);
}
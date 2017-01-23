<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 12/13/16
 * Time: 3:41 PM
 */

// appid = 220179075101428
// app secret = 911c170641c8b38c8407f199f0b47c9d

// 596434263827904_798097066994955
//EAADIQGZBO8vQBADjP8zQ0ZAqHB0ZC0eULuwb8grjEGys44MnRIsQdfegrFsJhVlHXkAgp3WfY89pQiz1aeLy3NU8ZBqTa9yxW8yzwdPw7HEyIMG8ZBZAqWD021L3UoBYQTuu7kIF5OANsxaYYhT85ZBjrqduGbUj8ZAspV5tZBegnxAZDZD
//EAADIQGZBO8vQBAA13ZBVVSWAAhgPulqN8BFYCWU5OOTdEJ2phD00IwC5hGZA1TQZCfjBaKYFGRNO1eKmQDdRFyKnlZC773JwmDJ62YZCZBiVL5dr7J27uJpboRBiL6uHzOA7izum02VHspssk3vpQomAhMPvl1nDxIoco2q3vcWYQZDZD
$page_access_token = 'EAADIQGZBO8vQBADjP8zQ0ZAqHB0ZC0eULuwb8grjEGys44MnRIsQdfegrFsJhVlHXkAgp3WfY89pQiz1aeLy3NU8ZBqTa9yxW8yzwdPw7HEyIMG8ZBZAqWD021L3UoBYQTuu7kIF5OANsxaYYhT85ZBjrqduGbUj8ZAspV5tZBegnxAZDZD';
//EAACEdEose0cBAA0jZBVCgsJs1j3fxwQAh4pyVCGM91KExOjfBKPpafvkK7nZBU2mMxAKJ7sQHdcViVFT6aYGehPOgS9CF89AC7vqpCI66EzMveg6dpIB7QdoZBjIHZCvWZAWz23X8Tyjsv8VxOeUk5rZCRYJy2BZBi5DtPDlPE1fTlCbmayQC4JMv7h6LpndfkZD
$page_id = '1798596937073751';

$data['picture'] = "http://api.file-dog.shatkonlabs.com/files/rahul/1336";
$data['link'] = "http://www.blueteam.in/";
$data['message'] = "Diet-chat is not just about eating , its about learning to live!";
$data['caption'] = "All Customer Service App";
$data['description'] = "Diet-chat is not just about eating , its about learning to live. ";

$data['access_token'] = $page_access_token;

$post_url = 'https://graph.facebook.com/'.$page_id.'/feed';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $post_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$return = curl_exec($ch);
var_dump($return);
curl_close($ch);
?>
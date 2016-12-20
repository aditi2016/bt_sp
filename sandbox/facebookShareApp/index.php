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

$page_access_token = 'EAACEdEose0cBACTVMANtGS5bQNqs9poweKuQwER70crIaGVAJE5Bey0tZAPm2oJL8ShnlWsmP1clt5qXotFIuZBDIxc8ZAaR4bvgrLDSImE6g2cN2b6k4ZClmVQ3ZAjlAfdsySy9FanX0qoX3ZBe7jSxxeBSqZArnnhcK4TTMv5GQZDZD';
$page_id = '596434263827904';

$data['picture'] = "http://api.file-dog.shatkonlabs.com/files/rahul/1053";
$data['link'] = "http://www.blueteam.com/";
$data['message'] = "Get Fit and Fine, We pray for you best health!";
$data['caption'] = "All Customer Service App";
$data['description'] = "Choose your Yoga Trainer, based on there reliability and quality ";

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
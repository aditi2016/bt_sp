<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 12/25/16
 * Time: 2:47 PM
 */

$page_access_token = 'EAACEdEose0cBADHMaX1ZAy8EAUvfZAscZABp1krBhpZAGGYHwHpJTTWfl9bOMZCKOnb25Bx757VDUyXy9XjQUQl5ZCF6iPzWZCKi2J6ZAtJC6KOxIOZBUuokjZBqIeGW9rZBZAaNY5MhOMQYQhjDYXfBjwNdmoLlwEUbV0QmL14ZCGTmpywZDZD';
$userId = "1171173419603719";

$groupId = "1445540972348326";
//
//$data['picture'] = "http://api.file-dog.shatkonlabs.com/files/rahul/1336";
$data['link'] = "https://www.facebook.com/596434263827904/posts/807212239416771";
$data['message'] = "lets make home more interesting https://www.facebook.com/596434263827904/posts/807212239416771";
$data['place'] = "596434263827904";
$data['tags'] = "1171173419603719,100002809855250";
//$data['caption'] = "All Customer Service App";
//$data['description'] = "Diet-chat is not just about eating , its about learning to live. ";

$data['access_token'] = $page_access_token;

$post_url = 'https://graph.facebook.com/'.$groupId.'/feed';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $post_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
echo "out";
$return = curl_exec($ch);
var_dump($return);
curl_close($ch);
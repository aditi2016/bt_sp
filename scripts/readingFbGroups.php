<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 12/25/16
 * Time: 2:47 PM
 */

$page_access_token = 'EAACEdEose0cBACOegEqRjv5bDcFZAZAS1U1HZC1dq5ZBB10lT7NKX2VOOlipZACeqZBJzZC9isPSv85haEacsBT08ixNnLZBnpPyQ3HDBpemTZClVmOXqjfPV2ZA5ZA6yoEOjchDw8IWYSWAPrfWL7TjsCZBIJHjebtkSSmN1VSvc4UIKgZDZD';
$userId = "1171173419603719";

$groupId = "1445540972348326";

$data['picture'] = "http://api.file-dog.shatkonlabs.com/files/rahul/1336";
$data['link'] = "http://www.blueteam.in/";
$data['message'] = "Diet-chat is not just about eating , its about learning to live!";
$data['caption'] = "All Customer Service App";
$data['description'] = "Diet-chat is not just about eating , its about learning to live. ";

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
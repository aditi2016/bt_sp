<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 12/13/16
 * Time: 3:41 PM
 */

// appid = 220179075101428
// app secret = 911c170641c8b38c8407f199f0b47c9d

$page_access_token = 'EAADIQGZBO8vQBAOxGPzgo4iQo4VNkLYYWoOMjpSxrb2HZB6ASnH4tU5zqZCRdiiwIGUO7tv4jwzUZCDznkvEbJVxQZB5PHeWbx6GRV6qEZBMUL4e4mGoTYXfK3YvO4alWlljDWzDlNhbBSUgdVorEc00KDrkosFFtXD0mUK8WWkXo4wfyuhWBJ';
$page_id = '596434263827904';

$data['picture'] = "http://www.example.com/image.jpg";
$data['link'] = "http://www.example.com/";
$data['message'] = "Your message";
$data['caption'] = "Caption";
$data['description'] = "Description";

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
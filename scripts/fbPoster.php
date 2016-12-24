<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 12/24/16
 * Time: 10:18 PM
 */

$page_access_token = 'EAADIQGZBO8vQBAN86wOjzGZBFSFw2W8BqQZCBjrS9XwQGKnZCmaeWg17X8ZBXh2R9Arwgb9UkDOPXXUq0dVR8rLJpb3ojKUvOTG5ZBdR52etFT0qpn5rmLZBLuW5ZA5w5ESuce5vIpLxZAsSjoRaBVdov6R1Y5L8fxK4ZD';

$page_id = '596434263827904';

$dbHandle = mysqli_connect("localhost","root","redhat@11111p","ragnar_social");

$posts = mysqli_query($dbHandle, "
                SELECT
                  a.`id`, a.`company_id`, a.`title`, a.`description`, a.`link`, a.`raw_img_id`,
                  a.gen_img_id, b.logo_id
                FROM `posts` as a
                  inner join companies as b
                  WHERE a.gen_img_id = 0
                      and a.`status` = 'approved'
                      and a.id NOT IN (SELECT post_id FROM post_tracks WHERE 1)
                      and a.company_id = b.id and a.gen_img_id != 0 limit 0,1");

$post = mysqli_fetch_array($posts);

$data['picture'] = "http://api.file-dog.shatkonlabs.com/files/rahul/".$post['gen_img_id'];
$data['link'] = $post['link'];
$data['message'] = $post['description'].". http://www.blueteam.in/";
$data['caption'] = "Get ". $post['title']." services";
$data['description'] = $post['description'];

$data['access_token'] = $page_access_token;

$post_url = 'https://graph.facebook.com/'.$page_id.'/feed';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $post_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$return = curl_exec($ch);
$fbReturn = json_decode($return);
var_dump($fbReturn);
curl_close($ch);

if($fbReturn->id){
    $sql = "INSERT INTO
                `post_tracks`(`post_id`, `social_network_id`, `publish_id`, `publish_datatime`, `creation`)
              VALUES (".$post['id'].",1,'".$fbReturn->id."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
    mysqli_query($dbHandle, $sql);
}

mysqli_close($dbHandle);
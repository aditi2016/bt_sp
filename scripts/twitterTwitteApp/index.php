<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 12/13/16
 * Time: 5:39 PM
 */

// Consumer Key (API Key) :  GJ8nBBmK5sUFpy7WkZyk6ZOIj
//  Consumer Secret (API Secret) : soBlTyK67eyoGQqt166K4ozDXA1qZnNElVdE28ZmxZe3z3laQ7
//

require "vendor/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;



// Set keys
/*
$consumerKey = 'GJ8nBBmK5sUFpy7WkZyk6ZOIj';
$consumerSecret = 'soBlTyK67eyoGQqt166K4ozDXA1qZnNElVdE28ZmxZe3z3laQ7';
$accessToken = '292829217-mmSdUyy92ZF6qOVs1v2qJ5NQ3ztpAn7im7axROuJ';
$accessTokenSecret = 'Sg4qeC7CO1L6sBC4otiQUihexIPYVK4nqpDHoEOF4wTOt';*/


$consumerKey = 'MPboILZOxGkHMJJ3uE9R8sza1';
$consumerSecret = 'qugVOpu1xgCqNnVAVyp1D94coKIpP8OWBZHzbbtpqBxhqnSCon';
$accessToken = '810109676230758400-FKet7dBddu5zXhLIWHOycyFxiueg1Fw';
$accessTokenSecret = 'OQLGNYplpgKmB1sV0hLkYvfKHdTmFoH8dswe866mpJwTf';

$tweet = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$tweet->setTimeouts(10, 15);
//$content = $connection->get("account/verify_credentials");

$dbHandle = mysqli_connect("localhost","root","redhat@11111p","ragnar_social");

$posts = mysqli_query($dbHandle, "
                SELECT
                  a.`id`, a.`company_id`, a.`title`, a.`description`, a.`link`, a.`raw_img_id`,
                  a.gen_img_id, b.logo_id
                FROM `posts` as a
                  inner join companies as b
                  WHERE a.gen_img_id != 0
                      and a.`status` = 'approved'
                      and a.id NOT IN (SELECT post_id FROM post_tracks WHERE social_network_id = 2)
                      and a.company_id = b.id and a.gen_img_id != 0 limit 0,1");

$post = mysqli_fetch_array($posts);

// Set status message
$tweetMessage = $post['description'].". http://www.blueteam.in/";
unlink("Tmpfile.png");
file_put_contents("Tmpfile.png", fopen("http://api.file-dog.shatkonlabs.com/files/rahul/".$post['gen_img_id'],'r'));



$media1 = $tweet->upload('media/upload', ['media' => 'Tmpfile.png']);

$twitte = [
    'status' => $post['description'].". http://www.blueteam.in/",
    'media_ids' => implode(',', [$media1->media_id_string])
];

// Check for 140 characters
if(strlen($tweetMessage) <= 140)
{
    // Post the status message
    $return  = $tweet->post('statuses/update', $twitte);
    var_dump($return);
    $id = $return->id_str;
    if($id){
        $sql = "INSERT INTO
                `post_tracks`(`post_id`, `social_network_id`, `publish_id`, `publish_datatime`, `creation`)
              VALUES (".$post['id'].",1,'".$id."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')";
        mysqli_query($dbHandle, $sql);
    }

}
mysqli_close($dbHandle);
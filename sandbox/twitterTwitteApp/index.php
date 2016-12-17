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
$consumerKey = 'GJ8nBBmK5sUFpy7WkZyk6ZOIj';
$consumerSecret = 'soBlTyK67eyoGQqt166K4ozDXA1qZnNElVdE28ZmxZe3z3laQ7';
$accessToken = '292829217-mmSdUyy92ZF6qOVs1v2qJ5NQ3ztpAn7im7axROuJ';
$accessTokenSecret = 'Sg4qeC7CO1L6sBC4otiQUihexIPYVK4nqpDHoEOF4wTOt';

$tweet = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
$tweet->setTimeouts(10, 15);
//$content = $connection->get("account/verify_credentials");



// Set status message
$tweetMessage = 'Brand have best technology will win the #growthhacking war!';

$media1 = $tweet->upload('media/upload', ['media' => '../oie_png.png']);

$twitte = [
    'status' => 'Brand Takes Care of all customers services Needs',
    'media_ids' => implode(',', [$media1->media_id_string])
];

// Check for 140 characters
if(strlen($tweetMessage) <= 140)
{
    // Post the status message
    $return  = $tweet->post('statuses/update', $twitte);
    var_dump($return);
}
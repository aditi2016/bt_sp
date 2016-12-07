<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 12/6/16
 * Time: 8:56 PM
 */

$dbHandle = mysqli_connect("localhost","root","redhat111111","blueteam_service_providers");

function getCoordinates($address){
    $address = urlencode($address);
    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=" . $address;
    $response = file_get_contents($url);
    $json = json_decode($response,true);

    $lat = $json['results'][0]['geometry']['location']['lat'];
    $lng = $json['results'][0]['geometry']['location']['lng'];

    return array($lat, $lng);
}


$serviceProvider = mysqli_query($dbHandle, "SELECT `address` FROM `service_providers` WHERE 1");
while ( $sp = mysqli_fetch_array($serviceProvider)) {

    $coords = getCoordinates($sp['address']);
    //POINT(49.227239 17.564932)
    mysqli_query($dbHandle, "UPDATE `service_providers`
                              SET `gps_location`=POINT(".$coords[0].",".$coords[01].") WHERE id=".$sp['id']);
    print_r($coords);

}


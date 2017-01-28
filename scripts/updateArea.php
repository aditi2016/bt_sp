<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 12/6/16
 * Time: 8:56 PM
 */

/*
 * SET @p = POINTFROMTEXT('POINT(51.227239 17.564931)');

SELECT CalculateDistanceKm(X(@p), Y(@p), X(gps_location), Y(gps_location)) AS distance
FROM service_providers;
 *
 *
 * */
$dbHandle = mysqli_connect("localhost","root","redhat@11111p","blueteam_service_providers");

function getCoordinates($address){
    $address = urlencode($address);
    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=" . $address;
    $response = file_get_contents($url);
    $json = json_decode($response,true);

    $lat = $json['results'][0]['geometry']['location']['lat'];
    $lng = $json['results'][0]['geometry']['location']['lng'];

    return array($lat, $lng);
}

$areas = mysqli_query($dbHandle, "SELECT id, name FROM `areas` 
                                    WHERE gps_location = '' or gps_location is null");

while ( $area = mysqli_fetch_array($areas)) {

    $coords = getCoordinates($area['name']);
    $sql = "UPDATE `blueteam_service_providers`.`areas`
              SET `gps_location` = GeomFromText( 'POINT(".$coords[0]." ".$coords[1].")' ) WHERE `areas`.`id` =".$area['id'].";";
    
    echo $sql;
    mysqli_query($dbHandle, $sql);
    print_r($coords);

}


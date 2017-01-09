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

function getLastString($value){
    $exploded_name = explode(',', $value);
    $exploded_trimmed = array_slice($exploded_name, -3);
    $imploded_name = implode(',', $exploded_trimmed);
    return $imploded_name ;
}

$serviceProvider = mysqli_query($dbHandle, "SELECT id,`address` FROM `service_providers` 
                                                WHERE gps_location = '' or gps_location is null");

while ( $sp = mysqli_fetch_array($serviceProvider)) {

    $coords = getCoordinates($sp['address']);
    if(empty(array_filter($coords))){
        $address = getLastString($sp['address']);
        $newCoords = getCoordinates($address);
        $sql = "UPDATE `blueteam_service_providers`.`service_providers`
              SET `gps_location` = GeomFromText( 'POINT(".$newCoords[0]." ".$newCoords[1].")' ) WHERE `service_providers`.`id` =".$sp['id'].";";
    }
    //POINT(49.227239 17.564932)
    else {
        $sql = "UPDATE `blueteam_service_providers`.`service_providers`
              SET `gps_location` = GeomFromText( 'POINT(".$coords[0]." ".$coords[1].")' ) WHERE `service_providers`.`id` =".$sp['id'].";";
    }
    echo $sql;
    mysqli_query($dbHandle, $sql);
    print_r($coords);

}


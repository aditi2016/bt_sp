<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/29/16
 * Time: 6:07 PM
 */

function getLocationDetails($id){


    $locDetails = getGPSLocationDetails($id);

    $sqlCountry = "INSERT INTO `countries`(`name`) VALUES (:country) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id);";
    $sqlState = "INSERT INTO `states`(`name`, `country_id`) VALUES (:name,:country_id) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id);";
    $sqlCity = "INSERT INTO `cities`(`name`, `state_id`) VALUES (:name,:state_id) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id);";
    $sqlArea = "INSERT INTO `areas`(`city_id`, `name`) VALUES (:city_id, :name) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id);";

    try {
        $db = getDB();

        $stmt = $db->prepare($sqlCountry);
        $stmt->bindParam("country", $locDetails['country']['name']);
        $stmt->execute();
        $locDetails['country']['id'] = $db->lastInsertId();

        $stmt = $db->prepare($sqlState);
        $stmt->bindParam("name", $locDetails['state']['name']);
        $stmt->bindParam("country_id", $locDetails['country']['id']);
        $stmt->execute();
        $locDetails['state']['id'] = $db->lastInsertId();

        $stmt = $db->prepare($sqlCity);
        $stmt->bindParam("name", $locDetails['city']['name']);
        $stmt->bindParam("state_id", $locDetails['state']['id']);
        $stmt->execute();
        $locDetails['city']['id'] = $db->lastInsertId();

        $stmt = $db->prepare($sqlArea);
        $stmt->bindParam("name", $locDetails['area']['name']);
        $stmt->bindParam("city_id", $locDetails['state']['id']);
        $stmt->execute();
        $locDetails['area']['id'] = $db->lastInsertId();


        $db = null;

        echo '{"location_details": ' . json_encode($locDetails) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}

function getGPSLocationDetails($loc){
    $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=$loc&sensor=true";
    $details = json_decode(httpGet($url));
    $return = array();
    $area_accuracy = 0;

    foreach ($details->results as $value){

        if(isset($value->address_components)) {
            foreach ($value->address_components as $acValue) {
                //var_dump($acValue);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              ;
                if (isset($acValue->types)) {
                    if (!isset($return['country']) && !is_bool(array_search('country', $acValue->types)) ) {
                        $return['country'] = array('name' => $acValue->long_name);
                    } elseif (!isset($return['state']) && !is_bool(array_search('administrative_area_level_1', $acValue->types))) {
                        $return['state'] = array('name' => $acValue->long_name);

                    } elseif (!isset($return['city']) && !is_bool(array_search('administrative_area_level_2', $acValue->types)) ) {
                        $return['city'] = array('name' => $acValue->long_name);
                    } elseif ($area_accuracy <= 0 && !is_bool(array_search('sublocality_level_1', $acValue->types) )) {
                        $return['area'] = array('name' => $acValue->long_name);
                        $area_accuracy = 1;
                    } elseif ($area_accuracy <= 1 && !is_bool(array_search('sublocality_level_2', $acValue->types)) ) {
                        $return['area'] = array('name' => $acValue->long_name);
                        $area_accuracy = 2;
                    } elseif ($area_accuracy <= 2 && !is_bool(array_search('sublocality_level_3', $acValue->types) )) {
                        $return['area'] = array('name' => $acValue->long_name);
                        $area_accuracy = 3;
                    } elseif (!isset($return['postalCode']) != "" && !is_bool(array_search('postal_code', $acValue->types) )) {
                        $return['postalCode'] = array('name' => $acValue->long_name);

                    }
                }
            }

        }


    }

    return $return;


}
<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/29/16
 * Time: 6:07 PM
 */

function getLocationDetails($id){


    $locDetails = getGPSLocationDetails($id);

    $sql = "INSERT INTO `campaign_requests`(`service_provider_id`, `type`, `amount`, `creation`)
               VALUES (:service_provider_id,:type,:amount,:creation);";

    try {
        /*$db = getDB();
        $stmt = $db->prepare($sql);
        //$service_provider->status = "new";

        $stmt->bindParam("service_provider_id", $id);
        $stmt->bindParam("type", $campaigningRequest->type);
        $stmt->bindParam("amount", $campaigningRequest->amount);
        $stmt->bindParam("creation", date("Y-m-d H:i:s"));

        $stmt->execute();

        $campaigningRequest->id = $db->lastInsertId();

        $db = null;*/

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
                //var_dump($acValue);die()                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               ;
                if (isset($acValue->types)) {
                    if (!isset($return['country']) && array_search('country', $acValue->types)) {
                        $return['country'] = array('name' => $acValue->long_name);
                    } elseif (!isset($return['state']) && array_search('administrative_area_level_1', $acValue->types)) {
                        $return['state'] = array('name' => $acValue->long_name);

                    } elseif (!isset($return['city']) && array_search('administrative_area_level_2', $acValue->types)) {
                        $return['city'] = array('name' => $acValue->long_name);
                    } elseif ($area_accuracy <= 0 && array_search('sublocality_level_1', $acValue->types)) {
                        $return['area'] = array('name' => $acValue->long_name);
                        $area_accuracy = 1;
                    } elseif ($area_accuracy <= 1 && array_search('sublocality_level_2', $acValue->types)) {
                        $return['area'] = array('name' => $acValue->long_name);
                        $area_accuracy = 2;
                    } elseif ($area_accuracy <= 2 && array_search('sublocality_level_3', $acValue->types)) {
                        $return['area'] = array('name' => $acValue->long_name);
                        $area_accuracy = 3;
                    } elseif (!isset($return['postalCode']) != "" && array_search('postal_code', $acValue->types)) {
                        $return['postalCode'] = array('name' => $acValue->long_name);

                    }
                }
            }
           break;
        }


    }

    return $return;


}
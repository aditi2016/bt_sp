<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/27/16
 * Time: 8:50 PM
 */

function insertServices($id){

    $request = \Slim\Slim::getInstance()->request();


    $services = json_decode($request->getBody());
    if (is_null($services)){
        echo $request->getBody();
        die();
    }


    $sql = "INSERT INTO `service_provider_service_mapping`
                  ( `service_provider_id`, `service_id`, `price`, `negotiable`, `hourly`, `status`)
                  VALUES
                      (:id,:service_id,:price,:negotiable,:hourly,'verified')
                  ON DUPLICATE KEY UPDATE
                      price = :price1, negotiable = :negotiable1, hourly = :hourly1, id=LAST_INSERT_ID(id);";

    try {
        $db = getDB();

        foreach($services->services as $value){
            $stmt = $db->prepare($sql);
            //$service_provider->status = "new";

            $value->negotiable = (isset($value->negotiable)&&$value->negotiable)?"yes":"no";
            $value->hourly = (isset($value->hourly)&&$value->hourly)?"yes":"no";

            $stmt->bindParam("id", $id);
            $stmt->bindParam("service_id", $value->id);
            $stmt->bindParam("price", $value->price);
            $stmt->bindParam("price1", $value->price);
            $stmt->bindParam("negotiable", $value->negotiable);
            $stmt->bindParam("negotiable1", $value->negotiable);
            $stmt->bindParam("hourly", $value->hourly);
            $stmt->bindParam("hourly1", $value->hourly);

            $stmt->execute();

        }
        $services->id = $db->lastInsertId();

        $db = null;

        echo '{"services": ' . json_encode($services) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
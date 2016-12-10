<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/27/16
 * Time: 5:33 PM
 */

function getAllCityAreas($id){

    $sql = "SELECT `id`, `city_id`, `name`, `postal_code`, X(`gps_location`) as lat, Y(`gps_location`) as lng FROM `areas` WHERE city_id = :id ";



    try {
        $db = getDB();

        //get all service providers of the id
        $stmt = $db->prepare($sql);

        $stmt->bindParam("id", $id);

        $stmt->execute();
        $areas = $stmt->fetchAll(PDO::FETCH_OBJ);


        $db = null;
        echo '{"areas": ' . json_encode($areas) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
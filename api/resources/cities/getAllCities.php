<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/27/16
 * Time: 5:33 PM
 */

function getAllCities(){

    $sql = "SELECT * FROM `cities` WHERE 1 ";



    try {
        $db = getDB();

        //get all service providers of the id
        $stmt = $db->prepare($sql);


        $stmt->execute();
        $cities = $stmt->fetchAll(PDO::FETCH_OBJ);


        $db = null;
        echo '{"cities": ' . json_encode($cities) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
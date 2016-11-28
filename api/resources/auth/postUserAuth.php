<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/22/16
 * Time: 2:13 PM
 */


function userAuth(){

    $request = \Slim\Slim::getInstance()->request();

    $user = json_decode($request->getBody());


    $sql = "SELECT * FROM service_providers WHERE mobile_no =:mobile and password=:password ";
    $sqlServices = "SELECT * FROM `service_provider_service_mapping` WHERE `service_provider_id` = :service_provider_id";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("mobile", $user->mobile);
        $stmt->bindParam("password", $user->password);


        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = $db->prepare($sqlServices);

        $stmt->bindParam("service_provider_id", $users[0]->id);

        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_OBJ);

        $users[0]->services = $services;
        $db = null;

        if(count($users) == 1)
            echo '{"user": ' . json_encode($users[0]) . '}';
        else
            echo '{"auth": "false"}';


    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}



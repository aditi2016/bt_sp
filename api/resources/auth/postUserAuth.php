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
    $sqlServices = "SELECT a.service_id, a.price, a.negotiable, a.hourly,
                          b.name, b.pic_id
                      FROM service_provider_service_mapping as a
                        INNER JOIN services as b
                        WHERE a.service_id = b.id AND a.service_provider_id = :service_provider_id";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("mobile", $user->mobile);
        $stmt->bindParam("password", $user->password);


        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);

        var_dump($users);
        $stmt2 = $db->prepare($sqlServices);

        $stmt2->bindParam("service_provider_id", $users[0]->id);

        $stmt2->execute();
        $services = $stmt2->fetchAll(PDO::FETCH_OBJ);

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



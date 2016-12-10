<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/27/16
 * Time: 6:25 PM
 */

function checkRegisteredByMobile($mobile){

    $sql = "SELECT * FROM `service_providers` WHERE mobile_no = :mobile AND `password` != \"\"";



    try {
        $db = getDB();

        //get all service providers of the id
        $stmt = $db->prepare($sql);

        $stmt->bindParam("mobile", $mobile);

        $stmt->execute();
        $serviceProvider = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;

        if(count($serviceProvider) == 0)
            echo '{"status": false}';
        else
            echo '{"status": true}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
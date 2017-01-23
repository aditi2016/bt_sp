<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 1/17/17
 * Time: 2:10 PM
 */

function insertCallLogs($mobile){

    $sqlId = "SELECT * FROM phone_details.`mobiles` WHERE `mobile` = :mobile";
    $sqlSelectCallLogs = "SELECT * FROM phone_details.`call_logs` WHERE `mobile_id` = :mobile_id AND creation BETWEEN timestamp(DATE_SUB(NOW(), INTERVAL 1 MINUTE)) AND timestamp(NOW())";

    $sqlInsertMobile = "INSERT INTO phone_details.`mobiles`(`name`, `mobile`)
                  VALUES ('',:mobile)";

    $sqlInsertCallLog = "INSERT INTO phone_details.`call_logs`(`mobile_id`, `type`)
                VALUES (:id,'other')";

    try {
        $db = getDB();
        $stmt = $db->prepare($sqlId);
        //$service_provider->status = "new";

        $stmt->bindParam("mobile", $mobile);


        $stmt->execute();
        $mobiles = $stmt->fetchAll(PDO::FETCH_OBJ);

        $mobile_id = 0;

        if(count($mobiles) >= 1){
            $mobile_id = $mobiles[0]->id;
        }
        else{
            $stmt = $db->prepare($sqlInsertMobile);
            //$service_provider->status = "new";

            $stmt->bindParam("mobile", $mobile);


            $stmt->execute();
            $mobile_id = $db->lastInsertId();

        }

        $stmt = $db->prepare($sqlSelectCallLogs);
        $stmt->bindParam("mobile_id", $mobile_id);
        $stmt->execute();
        $justEntered = $stmt->fetchAll(PDO::FETCH_OBJ);

        if(count($justEntered) >= 1){
            $returnId = $justEntered[0]->id;
        }else{
        
        $stmt = $db->prepare($sqlInsertCallLog);
        $stmt->bindParam("id", $mobile_id);
        $stmt->execute();
        $returnId = $db->lastInsertId();
        }

        echo '{"service_providers": ' . $returnId . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
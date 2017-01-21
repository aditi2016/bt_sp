<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 1/17/17
 * Time: 2:10 PM
 */

function updateCallLogs($id){
    $request = \Slim\Slim::getInstance()->request();
    $service = json_decode($request->getBody());
    if (is_null($service)){
      echo '{"error":{"text":"Invalid Json"}}';
      die();
    }
    if(!isset($service->name))
        $service->name = "";
    if(!isset($service->remarks))
        $service->remarks = "";

    $sqlMobile = "UPDATE phone_details.mobiles SET name = :name WHERE id = :mobile_id ";

    $sqlCallLog = "UPDATE phone_details.call_logs SET remarks = :remark , type = :type WHERE id = :id";

    try {
        $db = getDB();
        $stmt = $db->prepare($sqlMobile);
        $stmt->bindParam("name", $service->name);
        $stmt->bindParam("mobile_id", $service->mobile_id);
        $stmt->execute();
        
        $stmt = $db->prepare($sqlCallLog);
        $stmt->bindParam("type", $service->type);
        $stmt->bindParam("remark", $service->remarks);
        $stmt->bindParam("id", $id);
        $stmt->execute();


        echo '{"mobiles": "done"}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
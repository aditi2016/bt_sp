<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/20/16
 * Time: 7:42 PM
 *
 * name,mobile,service
 */

function insertServiceProviderFeedbackRequest($id){

    $request = \Slim\Slim::getInstance()->request();


    $feedbackRequest = json_decode($request->getBody());
    if (is_null($feedbackRequest)){
        echo '$feedbackRequest';
        die();
    }


    $sql = "INSERT INTO `blueteam_service_providers`.`feedback_requests` (
                `id` ,
                `service_provider_id` ,
                `customer_name` ,
                `customer_mobile` ,
                `service_id` ,
                `creation`
                )
                VALUES (
                NULL , :id, :customer_name, :customer_mobile, :service_id, :creation
            );";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        //$service_provider->status = "new";

        $stmt->bindParam("id", $id);
        $stmt->bindParam("customer_name", $feedbackRequest->customer_name);
        $stmt->bindParam("customer_mobile", $feedbackRequest->customer_mobile);
        $stmt->bindParam("service_id", $feedbackRequest->service_id);
        $stmt->bindParam("creation", date("Y-m-d H:i:s"));

        $stmt->execute();

        $feedbackRequest->id = $db->lastInsertId();

        // more better message can be written and by decreasing words length we can reduce the cost.

        $message = "Hello,\nIts nice to server you.\nCan I know, how I did it?\nSo, I can improve and get more customers.\nFeedback at http://f.blueteam.in/".$feedbackRequest->id;

        sendSMS($feedbackRequest->customer_mobile, $message);

        $db = null;
        echo '{"feedback_request": ' . json_encode($feedbackRequest) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
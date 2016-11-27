<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/27/16
 * Time: 1:43 PM
 */
//INSERT INTO `campaign_requests`(`id`, `service_provider_id`, `type`, `amount`, `status`, `creation`, `last_update`)
// VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7])

function createCampaignRequest($id){

    $request = \Slim\Slim::getInstance()->request();


    $campaigningRequest = json_decode($request->getBody());
    if (is_null($campaigningRequest)){
        echo '$feedbackRequest';
        die();
    }


    $sql = "INSERT INTO `campaign_requests`(`service_provider_id`, `type`, `amount`, `creation`)
               VALUES (:service_provider_id,:type,:amount,:creation);";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        //$service_provider->status = "new";

        $stmt->bindParam("service_provider_id", $id);
        $stmt->bindParam("type", $campaigningRequest->type);
        $stmt->bindParam("amount", $campaigningRequest->amount);
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
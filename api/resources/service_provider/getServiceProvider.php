<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/23/16
 * Time: 6:22 PM
 */

function getServiceProvider($id){

    $sql = "SELECT name, organization, description, experience, id, profile_pic_id, `reliability_score`, `reliability_count`,
              sms_credit, email_credit, campaign_credit_status
                FROM service_providers WHERE id = :id ";

    $sqlAmount = "SELECT sum(`amount`) as sum FROM `invoice` WHERE `service_provider_id` = :id AND Month( creation ) = MONTH(CURRENT_DATE())";

    $sqlAmountExpanses = "SELECT sum(`amount`) as sum FROM `expanses` WHERE `service_provider_id` = :id AND Month( creation ) = MONTH(CURRENT_DATE())";



    try {
        $db = getDB();



        //getting service provider

        $stmt = $db->prepare($sql);

        $stmt->bindParam("id", $id);

        $stmt->execute();
        $serviceProviders = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = $db->prepare($sqlAmount);

        $stmt->bindParam("id", $id);

        $stmt->execute();
        $amount = $stmt->fetchAll(PDO::FETCH_OBJ);

        $serviceProviders[0]->amount = $amount[0]->sum;

        $stmt = $db->prepare($sqlAmountExpanses);

        $stmt->bindParam("id", $id);

        $stmt->execute();
        $amount = $stmt->fetchAll(PDO::FETCH_OBJ);

        $serviceProviders[0]->amount_expanse = $amount[0]->sum;


        $db = null;
        echo '{"service_provider": ' . json_encode($serviceProviders) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
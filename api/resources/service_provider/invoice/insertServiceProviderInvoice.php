<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/20/16
 * Time: 7:41 PM
 *
 * name,mobile,service,amount,service_tax
 */

function insertServiceProviderInvoice($id){

    $request = \Slim\Slim::getInstance()->request();


    $invoice = json_decode($request->getBody());
    if (is_null($invoice)){
        echo '{"error":{"text":"Invalid Json"}}';
        die();
    }


    $sql = "INSERT INTO `blueteam_service_providers`.`invoice` (
                    `id` ,
                    `service_provider_id` ,
                    `customer_name` ,
                    `customer_mobile` ,
                    `service_id` ,
                    `amount` ,
                    `service_tax` ,
                    `creation`
                    )
                    VALUES (
                    NULL , :id, :customer_name, :customer_mobile, :service_id, :amount, :service_tax, :creation
                    );";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        //$service_provider->status = "new";

        $stmt->bindParam("id", $id);
        $stmt->bindParam("customer_name", $invoice->customer_name);
        $stmt->bindParam("customer_mobile", $invoice->customer_mobile);
        $stmt->bindParam("service_id", $invoice->service_id);
        $stmt->bindParam("amount", $invoice->amount);
        $stmt->bindParam("service_tax", $invoice->service_tax);
        $stmt->bindParam("creation", date("Y-m-d H:i:s"));


        $stmt->execute();

        $invoice->id = $db->lastInsertId();
        $db = null;
        echo '{"service_providers": ' . json_encode($invoice) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
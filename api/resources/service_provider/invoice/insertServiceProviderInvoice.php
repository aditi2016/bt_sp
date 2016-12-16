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

    $sqlGetSP = "SELECT a.service_provider_id, a.customer_name, a.customer_mobile, a.amount,
                        c.name as service_name, a.service_tax, b.name as service_provider_name, b.organization , b.address, b.mobile_no as partner_mobile
                        FROM `invoice` as a INNER JOIN service_providers as b INNER JOIN  services as c
                        WHERE a.id = :id  and a.service_provider_id = b.id and a.service_id = c.id;";

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

        $stmt = $db->prepare($sqlGetSP);
        $stmt->bindParam("id", $invoice->id);
        $stmt->execute();
        $sp = $stmt->fetchAll(PDO::FETCH_OBJ);
        //organization

        $message = "Thanks for using service by ".$sp[0]->organization." (Partner Id: $id)\nYou have paid Rs $invoice->amount including tax\nget bill on email at http://b.blueteam.in/".$invoice->id;

        if($invoice->send_bill)
            sendSMS($invoice->customer_mobile, $message);

        $db = null;
        echo '{"service_providers": ' . json_encode($invoice) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}

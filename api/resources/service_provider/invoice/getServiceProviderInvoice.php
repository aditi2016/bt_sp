<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/20/16
 * Time: 7:41 PM
 */

function getServiceProviderInvoice($id){

    $sql = "SELECT * FROM `invoice` WHERE service_provider_id = :id";

    //$photosSql = "SELECT photo_id FROM `photos` WHERE `service_provider_id` = :id";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("id", $id);

        $stmt->execute();
        $invoices = $stmt->fetchAll(PDO::FETCH_OBJ);



        $db = null;
        echo '{"invoices": ' . json_encode($invoices) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
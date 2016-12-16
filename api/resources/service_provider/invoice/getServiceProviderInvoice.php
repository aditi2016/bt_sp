<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/20/16
 * Time: 7:41 PM
 */

function getServiceProviderInvoice($id){

    $sql = "SELECT * FROM `invoice` WHERE service_provider_id = :id AND Month( creation ) = Month( CURDATE( ) ) x";

    //$photosSql = "SELECT photo_id FROM `photos` WHERE `service_provider_id` = :id";

    $sqlMonthYear = "SELECT DISTINCT Month( `creation` ) AS
                        MONTH , Year( `creation` ) AS year, sum( amount ) as amount
                        FROM `invoice`
                        WHERE `service_provider_id` = :id
                        GROUP BY MONTH , year";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("id", $id);

        $stmt->execute();
        $invoices = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = $db->prepare($sqlMonthYear);

        $stmt->bindParam("id", $id);

        $stmt->execute();
        $invoices->months = $stmt->fetchAll(PDO::FETCH_OBJ);



        $db = null;
        echo '{"invoices": ' . json_encode($invoices) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
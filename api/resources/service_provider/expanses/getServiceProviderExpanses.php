<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/20/16
 * Time: 7:41 PM
 */

function getServiceProviderExpanses($id){

    global $app;
    $date = $app->request()->get('date');

    if(isset($date))
        $d=$date;
    else
        $d=date("Y-m-d");

    $sql = "SELECT * FROM `expanses` WHERE service_provider_id = :id AND Month( creation ) = Month( :month_year )";

    //$photosSql = "SELECT photo_id FROM `photos` WHERE `service_provider_id` = :id";

    $sqlMonthYear = "SELECT DISTINCT Month( `creation` ) AS
                        month , Year( `creation` ) AS year, sum( amount ) as amount
                        FROM `expanses`
                        WHERE `service_provider_id` = :id
                        GROUP BY month , year";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("id", $id);
        $stmt->bindParam("month_year", $d);

        $stmt->execute();
        $expanses = array();
        $expanses['expanses'] = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = $db->prepare($sqlMonthYear);

        $stmt->bindParam("id", $id);

        $stmt->execute();
        $invoices['months'] = $stmt->fetchAll(PDO::FETCH_OBJ);



        $db = null;
        echo '{"expanses": ' . json_encode($expanses) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
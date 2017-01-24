<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/20/16
 * Time: 7:41 PM
 */

function getServiceProviderInvoice($id){

    global $app;
    $date = $app->request()->get('date');

    if(isset($date))
        $d=$date;
    else
        $d=date("Y-m-d");

    $sql = "SELECT a.id, a.customer_name, a.customer_mobile, b.name as service_name, a.amount, a.creation
            FROM `invoice` as a inner join services as b
            WHERE a.service_id = b.id and a.service_provider_id = :id AND Month( a.creation ) = Month( :month_year )";

    $sqlExpanse = "SELECT * FROM `expanses` WHERE service_provider_id = :id AND Month( creation ) = Month( :month_year )";

    //$photosSql = "SELECT photo_id FROM `photos` WHERE `service_provider_id` = :id";

    $sqlMonthYear = "SELECT DISTINCT Month( `creation` ) AS
                        month , Year( `creation` ) AS year, sum( amount ) as amount
                        FROM `invoice`
                        WHERE `service_provider_id` = :id
                        GROUP BY month , year";

    $sqlMonthYearExpanses = "SELECT DISTINCT Month( `creation` ) AS
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
        $invoices = array();
        $invoices['invoices'] = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = $db->prepare($sqlExpanse);

        $stmt->bindParam("id", $id);
        $stmt->bindParam("month_year", $d);

        $stmt->execute();

        $invoices['expanses'] = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = $db->prepare($sqlMonthYear);

        $stmt->bindParam("id", $id);

        $stmt->execute();
        $invoices['months'] = $stmt->fetchAll(PDO::FETCH_OBJ);

        $stmt = $db->prepare($sqlMonthYearExpanses);

        $stmt->bindParam("id", $id);

        $stmt->execute();
        $expanses = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach($expanses as $expanse){
            foreach($invoices['months'] as $t){
                if($t->month == $expanse->month && $t->year == $expanse->year )
                    $t->expanse_amount = $expanse->amount;
            }
        }

        $db = null;
        echo '{"invoices": ' . json_encode($invoices) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
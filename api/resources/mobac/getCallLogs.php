<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 1/17/17
 * Time: 2:10 PM
 */

function getCallLogs(){

    $sql = "SELECT b.id, b.mobile_id, a.name, a.mobile, b.creation FROM phone_details.mobiles as a inner join phone_details.call_logs as b WHERE a.id = b.mobile_id and b.type = 'other'";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $mobiles = $stmt->fetchAll(PDO::FETCH_OBJ);
        echo '{"mobiles": '.json_encode($mobiles).'}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
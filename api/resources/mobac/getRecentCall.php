<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 1/17/17
 * Time: 2:10 PM
 */

function getRecentCall(){

    $sql = "SELECT b.id, b.mobile_id, a.name, a.mobile, b.creation FROM phone_details.mobiles as a inner join phone_details.call_logs as b WHERE a.id = b.mobile_id and b.creation BETWEEN timestamp(DATE_SUB(NOW(), INTERVAL 1 MINUTE)) AND timestamp(NOW()) AND b.type = 'other' LIMIT 1";
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
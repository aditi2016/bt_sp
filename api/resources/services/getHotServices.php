<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/26/16
 * Time: 1:33 PM
 */

//getHotServices

function getHotServices(){

    $sql = "SELECT name, id, pic_id, description FROM services WHERE status = 'active' ORDER BY `accesses` DESC LIMIT 0 , 8 ";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"allServices": ' . json_encode($services) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/20/16
 * Time: 7:01 PM
 */

function getInteresredServices(){

    $sql = "SELECT a.service_id, count( a.id ) AS cnt, b.name FROM service_looks AS a JOIN 
    		services AS b WHERE a.service_id = b.id GROUP BY a.service_id ORDER BY cnt DESC";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"interestedServices": ' . json_encode($services) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

} 
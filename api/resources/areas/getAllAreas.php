<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 3:16 PM
 */

function getAllAreas(){

    $sql = "SELECT a.id,a.area,b.name FROM areas as a INNER JOIN cities as b WHERE a.city_id = b.id";
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $areas = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"areas": ' . json_encode($areas) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
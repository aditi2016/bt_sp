<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/20/16
 * Time: 7:01 PM
 */

function getSearchResults(){

    $sql = "SELECT * FROM searchs WHERE ip != '52.66.85.75'";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"searchs": ' . json_encode($services) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

} 
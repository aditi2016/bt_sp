<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:30 PM
 */

function getCandidatesToCall(){

    $sql = "SELECT * FROM candidates WHERE status = 'new' or status = 'followback' ORDER BY `candidates`.`last_updated` ASC ";
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $candidates = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"candidates": ' . json_encode($candidates) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}

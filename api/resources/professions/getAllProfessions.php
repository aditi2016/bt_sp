<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 3:16 PM
 */

function getAllProfessions(){

    $sql = "SELECT id,name FROM professions WHERE status = 'active'";
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $professions = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"professions": ' . json_encode($professions) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}

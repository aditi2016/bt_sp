<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 1/15/17
 * Time: 8:33 PM
 */

function getExpansesTypes($id){

    $sql = "SHOW COLUMNS.type FROM expanses WHERE Field = 'type'";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);


        $stmt->execute();


        $expanseTypes = explode(',',explode(')',explode('(',$stmt->fetchAll(PDO::FETCH_OBJ)[0]->Type)[1]));


        $db = null;
        echo '{"expanse_types": ' . json_encode($expanseTypes) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}
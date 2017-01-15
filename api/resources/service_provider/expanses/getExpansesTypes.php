<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 1/15/17
 * Time: 8:33 PM
 */

function getExpansesTypes($id){

    $sql = "SHOW COLUMNS FROM expanses WHERE Field = 'type'";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);


        $stmt->execute();

        //var_dump($stmt->fetchAll(PDO::FETCH_OBJ));die();

        $expanseTypes = explode(',',str_replace('\'','',explode(')',explode('(',$stmt->fetchAll(PDO::FETCH_OBJ)[0]->Type)[1])[0]));


        $db = null;
        echo '{"expanse_types": ' . json_encode($expanseTypes) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}
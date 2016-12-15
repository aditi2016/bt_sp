<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 3:16 PM
 */

function getCategories(){

    $sql = "SELECT * FROM categories WHERE 1";
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $categories = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"categories": ' . json_encode($categories) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}

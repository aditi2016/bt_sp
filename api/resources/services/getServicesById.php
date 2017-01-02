<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:32 PM
 */



function getServicesById($id){

    $sql = " SELECT * FROM services WHERE id = :id ";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $service = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;
        echo '{"service": ' . json_encode($service) . '}';

    } catch (PDOException $e) {
            //error_log($e->getMessage(), 3, '/var/tmp/php.log');
            echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
    
}
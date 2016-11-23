<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/23/16
 * Time: 6:22 PM
 */

function getServiceProvider($id){

    $sql = "SELECT name, organization, description, experience, id, profile_pic_id, `reliability_score`, `reliability_count`,
                FROM service_providers WHERE id = :id ";



    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("id", $id);

        $stmt->execute();
        $serviceProviders = $stmt->fetchAll(PDO::FETCH_OBJ);


        $db = null;
        echo '{"service_provider": ' . json_encode($serviceProviders) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
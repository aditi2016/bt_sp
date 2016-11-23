<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/23/16
 * Time: 6:22 PM
 */

function getServiceProvider($id){

    $sql = "SELECT a.name, a.organization, a.description, a.experience, a.id, a.profile_pic_id, a.`reliability_score`, a.`reliability_count`,
                FROM service_providers AS a WHERE a.id = :id ";



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
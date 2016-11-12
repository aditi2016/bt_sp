<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:32 PM
 */
function getServiceProviderByType(){
    global $app;
    $type = $app->request()->get('type');
    
    $sql = "SELECT * FROM `services` as s inner join service_provider_service_mapping as m inner join service_providers as sp WHERE s.`name`  = :type and s.`status` = 'active' and s.id = m.service_id and m.`service_provider_id` = sp.id";
    
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindParam("type", $type);
        
        $stmt->execute();
        $candidates = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"candidates": ' . json_encode($candidates) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}
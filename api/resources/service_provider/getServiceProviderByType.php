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
    
    $sql = "SELECT sp.id, sp.`name` , sp.`organization` , s.name AS 'service', sp.`description` , sp.`address` , sp.`area_id` , sp.`city_id`   FROM `services` as s inner join service_provider_service_mapping as m inner join service_providers as sp WHERE s.`name`  = :type and s.`status` = 'active' and s.id = m.service_id and m.`service_provider_id` = sp.id";

    $photosSql = "SELECT photo_id FROM `photos` WHERE `service_provider_id` = :id";
    
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindParam("type", $type);
        
        $stmt->execute();
        $serviceProviders = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($serviceProviders as $key => $serviceProvider) {
            $id = $serviceProvider->id;

            $stmt = $db->prepare($photosSql);
        
            $stmt->bindParam("id", $id);
            
            $stmt->execute();
            $serviceProvider->photos = $stmt->fetchAll(PDO::FETCH_OBJ);

        }

        $db = null;
        echo '{"service_providers": ' . json_encode($serviceProviders) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}
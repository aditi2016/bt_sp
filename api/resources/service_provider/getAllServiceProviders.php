<?php 
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:31 PM
 */



   
function getAllServiceProviders($id){

    $sql = "SELECT a.name, a.organization, a.description, a.experience, a.id, a.profile_pic_id, b.price,
            b.nagotiable, b.hourly, b.status FROM service_providers AS a JOIN service_provider_service_mapping 
            AS b WHERE a.id = b.service_provider_id AND b.status = 'verified' AND b.service_id = :id ";
    
    $photosSql = "SELECT photo_id FROM `photos` WHERE `service_provider_id` = :id";
    
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindParam("id", $id);
        
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
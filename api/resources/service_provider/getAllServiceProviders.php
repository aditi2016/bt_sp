<?php 
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:31 PM
 */



   
function getAllServiceProviders($id){

    $sql = "SELECT a.name, a.organization, a.description, a.experience, a.id, a.profile_pic_id, a.`reliability_score`, a.`reliability_count`, b.price,
            b.nagotiable, b.hourly, b.status FROM service_providers AS a JOIN service_provider_service_mapping 
            AS b WHERE a.id = b.service_provider_id AND b.service_id = :id ";
    
    $photosSql = "SELECT photo_id FROM `photos` WHERE `service_provider_id` = :id";

    $sqlUpdateAccess = "UPDATE `blueteam_service_providers`.`services` SET `accesses` = accesses + 1 WHERE `id` =:id";

    try {
        $db = getDB();

        //updating accesses
        $stmt = $db->prepare($sqlUpdateAccess);

        $stmt->bindParam("id", $id);

        $stmt->execute();

        //get all service providers of the id
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
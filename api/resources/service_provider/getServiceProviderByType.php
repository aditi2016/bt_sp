<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:32 PM
 */



function getServiceProviderByType(){
    global $app;

    $mobile = $app->request()->get('mobile');

    if(isset($mobile)){
        checkRegisteredByMobile($mobile);
        die();
    }

    $type = $app->request()->get('type');

    if($type == 'not_install'){
        $sql = "SELECT `name`, `organization`, `description`, `address`, `mobile_no`, `email`, `id`, `profile_pic_id`
              FROM service_providers WHERE password = '' AND profile_pic_id = '0' ";

        try {
            $db = getDB();
            $stmt = $db->prepare($sql);            
            $stmt->execute();

            $serviceProviders = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            foreach($serviceProviders as $key => $value) {
                $value->name = htmlspecialchars($value->name);
                $value->organization = htmlspecialchars($value->organization);
                $value->description = htmlspecialchars($value->description);
                $value->address = htmlspecialchars($value->address);
                $value->mobile_no = htmlspecialchars($value->mobile_no);
                $value->email = htmlspecialchars($value->email);

            }
            //var_dump($serviceProviders);
            echo '{"service_providers": ' . json_encode($serviceProviders) . '}';
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }
    elseif ($type == 'not_using') {
        $sql = "SELECT `name`, `organization`, `description`, `address`, `mobile_no`, `email`, `id`, `profile_pic_id`
FROM service_providers WHERE id NOT IN
                    (SELECT DISTINCT service_provider_id FROM invoice WHERE 1) and password != '' AND profile_pic_id != '0' ";        
        try {
            $db = getDB();
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $serviceProviders = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            foreach($serviceProviders as $key => $value) {
                $value->name = htmlspecialchars($value->name);
                $value->organization = htmlspecialchars($value->organization);
                $value->description = htmlspecialchars($value->description);
                $value->address = htmlspecialchars($value->address);
                $value->mobile_no = htmlspecialchars($value->mobile_no);
                $value->email = htmlspecialchars($value->email);

            }
            echo '{"service_providers": ' . json_encode($serviceProviders) . '}';
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }
    else {
        $sql = "SELECT sp.id, sp.`name` , sp.`organization` , s.name AS 'service', sp.`description` , sp.`address` , sp.`area_id` , sp.`city_id`
            FROM `services` as s inner join service_provider_service_mapping as m inner join service_providers as sp
            WHERE s.`name`  = :type and s.`status` = 'active' and s.id = m.service_id and m.`service_provider_id` = sp.id";

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
}
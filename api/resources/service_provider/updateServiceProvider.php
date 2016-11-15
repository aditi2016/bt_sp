<?php 
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:31 PM
 */



   
function updateServiceProvider(){

    $request = \Slim\Slim::getInstance()->request();


    $serviceProvider = json_decode($request->getBody());
    if (is_null($serviceProvider)){
      echo '{"error":{"text":"Invalid Json"}}';
      die();
    }
                $sql = "UPDATE 
                 service_providers 
                  SET
                   name =:name, 
                   organization= :organization,
                    description=:description,
                     mobile_no=:mobile,
                      area_id=:area_id,
                       city_id=:city_id,
                        address=:address,
                         email=:email,
                         profile_pic_id= :profile_pic_id
                         WHERE id=:service_providers_id";



        try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        //$service_provider->status = "new";

        $stmt->bindParam("name", $serviceProvider->name);
        $stmt->bindParam("organization", $serviceProvider->organization);
        $stmt->bindParam("description", $serviceProvider->description);
        $stmt->bindParam("mobile", $serviceProvider->mobile);
        $stmt->bindParam("area_id", $serviceProvider->area_id);
        $stmt->bindParam("city_id", $serviceProvider->city_id);
        $stmt->bindParam("address", $serviceProvider->address);
        $stmt->bindParam("email", $serviceProvider->email);
         $stmt->bindParam("profile_pic_id", $serviceProvider->profile_pic_id);
       

        $stmt->execute();

       
        $db = null;
        echo '{"service_providers": ' . json_encode($serviceProvider) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
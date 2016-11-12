<?php 
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:31 PM
 */



   
function insertServiceProvider(){

    $request = \Slim\Slim::getInstance()->request();


    $serviceProvider = json_decode($request->getBody());
    if (is_null($serviceProvider))
      echo '{"error":{"text":"Invalid Json"}}';


    $sql = "INSERT INTO service_providers (name, organization, description, mobile_no, area_id, city_id, address, email)
                  VALUES (:name, :organization, :description, :mobile, :area_id, :city_id, :address, :email)";
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
       

        $stmt->execute();

        $serviceProvider->id = $db->lastInsertId();
        $db = null;
        echo '{"service_providers": ' . json_encode($serviceProvider) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
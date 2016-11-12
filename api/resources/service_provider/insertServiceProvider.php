<?php 
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:31 PM
 */



   
function insertServiceProvider(){

    $request = \Slim\Slim::getInstance()->request();

    $service_provider = json_decode($request->getBody());

    $sql = "INSERT INTO service_providers (name, organization, description, mobile_no., area_id, city_id, address, email)
                  VALUES (:name, :organization, :description, :mobile, :area_id, :city_id, :address, :email)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        //$service_provider->status = "new";

        $stmt->bindParam("name", $service_provider->name);
        $stmt->bindParam("organization", $service_provider->organization);
        $stmt->bindParam("description", $service_provider->description);
        $stmt->bindParam("mobile", $service_provider->mobile);
        $stmt->bindParam("area_id", $service_provider->area_id);
        $stmt->bindParam("city_id", $service_provider->city_id);
        $stmt->bindParam("address", $service_provider->address);
        $stmt->bindParam("email", $service_provider->email);
       

        $stmt->execute();

        $service_provider->id = $db->lastInsertId();
      



  $db = null;
        echo '{"service_providers": ' . json_encode($service_provider) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
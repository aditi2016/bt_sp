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

    $sql = "INSERT
                  INTO
                    service_provider(
                      name,
                      organization,
                      description
                      mobile_no.,
                      area_id,
                      city_id
                      address,
                      email
                      )
                  VALUES (
                      :name,
                      :organization
                      :description
                      :mobile,
                      :area_id,
                      :city_id,
                      :address,
                      :email
                      
                      )";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $serviceProviders->status = "new";

        $stmt->bindParam("name", $serviceProviders->name);
        $stmt->bindParam("organization", $serviceProviders->organization);
        $stmt->bindParam("description", $serviceProviders->description);
        $stmt->bindParam("mobile", $serviceProviders->mobile);
        $stmt->bindParam("area_id", $serviceProviders->area_id);
        $stmt->bindParam("city_id", $serviceProviders->city_id);
        $stmt->bindParam("address", $serviceProviders->address);
        $stmt->bindParam("email", $serviceProviders->email);
       

        $stmt->execute();

        $serviceProviders->id = $db->lastInsertId();
      



  $db = null;
        echo '{"service_providers": ' . json_encode($serviceProviders) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
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
                      mobile,
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
        $candidate->status = "new";

        $stmt->bindParam("name", $candidate->name);
        $stmt->bindParam("organization", $candidate->mobile);
        $stmt->bindParam("description", $candidate->area);
        $stmt->bindParam("mobile", $candidate->age);
        $stmt->bindParam("area_id", $candidate->dob);
        $stmt->bindParam("city_id", $candidate->address);
        $stmt->bindParam("address", $candidate->gender);
        $stmt->bindParam("email", $candidate->user_id);
       

        $stmt->execute();

        $serviceProviders->id = $db->lastInsertId();
      



  $db = null;
        echo '{"service_providers": ' . json_encode($serviceProviders) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
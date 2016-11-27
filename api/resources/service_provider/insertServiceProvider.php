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
    if (is_null($serviceProvider)){
      echo '{"error":{"text":"Invalid Json"}}';
      die();
    }

    if(!isset($serviceProvider->organization))
        $serviceProvider->organization = $serviceProvider->name;
    if(!isset($serviceProvider->description))
        $serviceProvider->description = $serviceProvider->name;
    if(!isset($serviceProvider->email))
        $serviceProvider->email = "n/a";

    //photo,name,mobile,password,address,experience,services,city,area

    $sql = "INSERT INTO service_providers (name, organization, description, mobile_no, password, experience, area_id, city_id, address, email,profile_pic_id)
                  VALUES (:name, :organization, :description, :mobile, :password, :experience, :area_id, :city_id, :address, :email, :profile_pic_id)";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        //$service_provider->status = "new";


        $stmt->bindParam("name", $serviceProvider->name);
        $stmt->bindParam("organization", $serviceProvider->organization);
        $stmt->bindParam("description", $serviceProvider->description);
        $stmt->bindParam("mobile", $serviceProvider->mobile);
        $stmt->bindParam("password", $serviceProvider->password);
        $stmt->bindParam("experience", $serviceProvider->experience);
        $stmt->bindParam("area_id", $serviceProvider->area_id);
        $stmt->bindParam("city_id", $serviceProvider->city_id);
        $stmt->bindParam("address", $serviceProvider->address);
        $stmt->bindParam("email", $serviceProvider->email);
        $stmt->bindParam("profile_pic_id", $serviceProvider->profile_pic_id);
       

        $stmt->execute();

        $serviceProvider->id = $db->lastInsertId();
        $db = null;
        echo '{"service_providers": ' . json_encode($serviceProvider) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
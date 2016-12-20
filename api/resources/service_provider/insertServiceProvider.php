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

    if(!isset($serviceProvider->organization)&&$serviceProvider->organization== "")
        $serviceProvider->organization = $serviceProvider->name;
    if(!isset($serviceProvider->description))
        $serviceProvider->description = $serviceProvider->name;
    if(!isset($serviceProvider->email))
        $serviceProvider->email = "n/a";

    if(!isset($serviceProvider->address))
        $serviceProvider->address = "n/a";
    if(!isset($serviceProvider->password))
        $serviceProvider->password = "";
    if(!isset($serviceProvider->profile_pic_id))
        $serviceProvider->profile_pic_id = 0;
    if(!isset($serviceProvider->experience))
        $serviceProvider->experience = 0;
    $coupon = 0;
    if(isset($serviceProvider->coupon)){
        $coupon = explode("#",$serviceProvider->coupon)[1];
    }
    //photo,name,mobile,password,address,experience,services,city,area

    $sql = "INSERT INTO
                    service_providers
                      (name, organization, description, mobile_no, password, ref_id, experience, address, email,profile_pic_id,area_id,city_id)
                    VALUES
                      (:name, :organization, :description, :mobile, :password, :ref_id, :experience,  :address, :email, :profile_pic_id, :area, :city)
                    ON DUPLICATE KEY UPDATE
                      name = :name1, organization = :organization1, password = :password1, ref_id = :ref_id1, experience = :experience1, profile_pic_id = :profile_pic_id1, id=LAST_INSERT_ID(id);";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        //$service_provider->status = "new";
        //name,password,experience,area_id,city_id,profile_pic_id

        $stmt->bindParam("name", $serviceProvider->name);
        $stmt->bindParam("name1", $serviceProvider->name);
        $stmt->bindParam("organization", $serviceProvider->organization);
        $stmt->bindParam("organization1", $serviceProvider->organization);
        $stmt->bindParam("description", $serviceProvider->description);
        $stmt->bindParam("mobile", $serviceProvider->mobile);

        $stmt->bindParam("password", $serviceProvider->password);
        $stmt->bindParam("password1", $serviceProvider->password);
        $stmt->bindParam("ref_id", $coupon);
        $stmt->bindParam("ref_id1", $coupon);
        $stmt->bindParam("experience", $serviceProvider->experience);
        $stmt->bindParam("experience1", $serviceProvider->experience);
        $stmt->bindParam("area", $serviceProvider->area_id);
        $stmt->bindParam("city", $serviceProvider->city_id);

        $stmt->bindParam("address", $serviceProvider->address);
        $stmt->bindParam("email", $serviceProvider->email);

        $stmt->bindParam("profile_pic_id", $serviceProvider->profile_pic_id);
        $stmt->bindParam("profile_pic_id1", $serviceProvider->profile_pic_id);


        $stmt->execute();

        $serviceProvider->id = $db->lastInsertId();
        $db = null;
        echo '{"service_providers": ' . json_encode($serviceProvider) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
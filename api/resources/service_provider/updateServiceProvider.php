<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:31 PM
 */




function updateServiceProvider($id){

    $request = \Slim\Slim::getInstance()->request();


    $serviceProvider = json_decode($request->getBody());
    if (is_null($serviceProvider)){
        echo '{"error":{"text":"Invalid Json"}}';
        die();
    }

    $updateStr = "";

    if(isset($serviceProvider->name))
        $updateStr .= "name =:name,";

    if(isset($serviceProvider->organization))
        $updateStr .= "organization =:organization,";

    if(isset($serviceProvider->description))
        $updateStr .= "description =:description,";

    if(isset($serviceProvider->description))
        $updateStr .= "description =:description,";

    if(isset($serviceProvider->area_id))
        $updateStr .= "area_id =:area_id,";

    if(isset($serviceProvider->city_id))
        $updateStr .= "city_id =:city_id,";

    if(isset($serviceProvider->email))
        $updateStr .= "email =:email,";

    if(isset($serviceProvider->profile_pic_id))
        $updateStr .= "profile_pic_id =:profile_pic_id,";


    if(isset($serviceProvider->gps_location)) {
        $p = explode(",",$serviceProvider->gps_location);
        $point = $p[0]." ".$p[1];
        $updateStr .= "gps_location =GeomFromText( 'POINT(" . $point . ")' ),";

    }

    $updateStr = rtrim($updateStr, ",");

    $sql = "UPDATE
                 service_providers 
                  SET
                   $updateStr
                         WHERE id=:service_providers_id";

    //var_dump($sql);die();


    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        //$service_provider->status = "new";

        if(isset($serviceProvider->name))
            $stmt->bindParam("name", $serviceProvider->name);
        if(isset($serviceProvider->organization))
            $stmt->bindParam("organization", $serviceProvider->organization);
        if(isset($serviceProvider->description))
            $stmt->bindParam("description", $serviceProvider->description);
        if(isset($serviceProvider->area_id))
            $stmt->bindParam("area_id", $serviceProvider->area_id);
        if(isset($serviceProvider->city_id))
            $stmt->bindParam("city_id", $serviceProvider->city_id);
        if(isset($serviceProvider->address))
            $stmt->bindParam("address", $serviceProvider->address);
        if(isset($serviceProvider->email))
            $stmt->bindParam("email", $serviceProvider->email);
        if(isset($serviceProvider->profile_pic_id))
            $stmt->bindParam("profile_pic_id", $serviceProvider->profile_pic_id);

        $stmt->bindParam("service_providers_id", $id);


        $stmt->execute();


        $db = null;
        echo '{"service_providers": ' . json_encode($serviceProvider) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
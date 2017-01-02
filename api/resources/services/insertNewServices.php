<?php 
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:31 PM
 */



   
function insertNewServices(){


    $request = \Slim\Slim::getInstance()->request();

    $service = json_decode($request->getBody());
    if (is_null($service)){
      echo '{"error":{"text":"Invalid Json"}}';
      die();
    }
    $date = date("Y-m-d H:i:s");
    
    if(!isset($service->description))
        $service->description = "";
    if(!isset($service->pic_id))
        $service->pic_id = 0;
    if(!isset($service->service_img))
        $service->service_img = 0;
    $sql = "INSERT INTO
                    services
                      (name, description, pic_id, service_img, creation_time, status)
                    VALUES
                      (:name, :description, :pic_id, :service_img, :creation_time, :status)
                    ON DUPLICATE KEY UPDATE
                      description = :description1, pic_id = :pic_id1, service_img = :service_img1, status = :status1;";
    $mappingSql = "INSERT INTO service_category_mapping (service_id, category_id, status, creation_time) 
                    VALUES (:service_id, :category_id, 'active', :cr_time) ";
    try { 
        $db = getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindParam("name", $service->name);
        $stmt->bindParam("description", $service->description);
        $stmt->bindParam("description1", $service->description);        
        $stmt->bindParam("pic_id", $service->pic_id);
        $stmt->bindParam("pic_id1", $service->pic_id);
        $stmt->bindParam("service_img", $service->service_img);
        $stmt->bindParam("service_img1", $service->service_img);
        $stmt->bindParam("status", $service->status);
        $stmt->bindParam("creation_time", $date);
        $stmt->bindParam("status1", $service->status);
        $stmt->execute();
        $service->id = $db->lastInsertId();
        if(isset($service->category_id)){
            $stmt2 = $db->prepare($mappingSql);
            $stmt2->bindParam("service_id", $service->id);
            $stmt2->bindParam("category_id", $service->category_id);
            $stmt2->bindParam("cr_time", $date);
            $stmt2->execute();
            $service->map_id = $db->lastInsertId();
        }
        $db = null;
        echo '{"service": ' . json_encode($service) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
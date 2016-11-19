<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:32 PM
 */



function getAllServices(){
        
    $sql = "SELECT id, name, icon_id FROM categories WHERE 1";
    $servicesql = "SELECT a.name, a.id, a.pic_id, a.description FROM services AS a JOIN 
                    service_category_mapping AS b WHERE a.id = b.service_id AND a.status = 'active' 
                    AND b.status= 'active' AND b.category_id = :id ";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $allServices = array();
        foreach ($categories as $key => $value) {
            $category_id = $value['id'];
            $stmt = $db->prepare($servicesql);
            $stmt->bindParam("id", $category_id);
            $stmt->execute();
            $taskTitle = array();
            $taskDescription = array();
            $services = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            $allServices[] = array("id"=> $category_id , "name" => $value['name'], "icon_id" => $value['icon_id'] , "services " => $services);
        }
        
        echo '{"allServices": ' . json_encode($allServices) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}
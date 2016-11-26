<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:32 PM
 */



function getAllServices(){
    global $app;
    $category = $app->request()->get('category');
    $type = $app->request()->get('type');

    if(isset($type)&&$type == "hot"){
        getHotServices();
        die();
    }

    if(isset($category)) {

        $sql = "SELECT id, name, icon_id FROM categories WHERE 1";
        $servicesql = "SELECT a.name, a.id, a.pic_id, a.description FROM services AS a JOIN 
                        service_category_mapping AS b WHERE a.id = b.service_id AND a.status = 'active' 
                        AND b.status= 'active' AND b.category_id = :id ";
        try {
            $db = getDB();
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_OBJ);

            foreach ($categories as $key => $category) {
                $id = $category->id;

                $stmt = $db->prepare($servicesql);
            
                $stmt->bindParam("id", $id);
                
                $stmt->execute();
                $category->services = $stmt->fetchAll(PDO::FETCH_OBJ);
            }

            $db = null;
            echo '{"allServices": ' . json_encode($categories) . '}';
        
        } catch (PDOException $e) {
            //error_log($e->getMessage(), 3, '/var/tmp/php.log');
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }
    else {
        $sql = "SELECT name, id, pic_id, description FROM services WHERE status = 'active' ";
    
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"allServices": ' . json_encode($services) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
    }
}
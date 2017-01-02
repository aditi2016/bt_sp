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
    $areaId = $app->request()->get('area_id');

    if(isset($type)&&$type == "hot"){
        getHotServices();
        die();
    }

    if(isset($type)&&$type == "geo"){
        getServicesGeo($areaId);
        die();
    }

    if(isset($type)&&$type == "not-found"){
        servicesNeedLookUp();
        die();
    }

    if(isset($category)) {

        $sql = "SELECT id, name, icon_id, img FROM categories WHERE 1";
        $servicesql = "SELECT a.name, a.id, a.pic_id, a.description FROM services AS a JOIN 
                        service_category_mapping AS b WHERE a.id = b.service_id AND a.status = 'active' 
                        AND b.status= 'active' AND b.category_id = :id ";

        $serviceProviderSql = "SELECT a.name, a.organization, a.description, a.experience, a.id, a.profile_pic_id, a.`reliability_score`, a.`reliability_count`, b.price,
            b.nagotiable, b.hourly, b.status FROM service_providers AS a JOIN service_provider_service_mapping
            AS b WHERE a.id = b.service_provider_id AND b.service_id = :id ";

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
                $services = $stmt->fetchAll(PDO::FETCH_OBJ);
                /*
                                foreach($services as $key1 => $service){

                                    $stmt = $db->prepare($serviceProviderSql);

                                    $stmt->bindParam("id", $service->id);

                                    $stmt->execute();
                                    if ( count( $stmt->fetchAll(PDO::FETCH_OBJ)) == 0){
                                        unset($services[$key1]);
                                    }

                                }

                                if(count($services) == 0){

                                    unset($categories[$key]);
                                } else*/

                $category->services = $services;
            }

            //var_dump($categories);die();

            $db = null;
            echo '{"allServices": ' . json_encode($categories) . '}';

        } catch (PDOException $e) {
            //error_log($e->getMessage(), 3, '/var/tmp/php.log');
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }
    else {
        $sql = "SELECT name, id, pic_id, description, status FROM services WHERE 1 ";

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
<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:32 PM
 */



function getAllServices(){
        
    $sql = "SELECT id, name, icon_id FROM categories WHERE 1";
    
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        var_dump($categories);
        $db = null;
        /*$sql = "SELECT a.name, a.id, a.pic_id, a.description FROM services AS a 
                JOIN service_category_mapping AS b
                WHERE a.id = b.service_id AND a.status = 'active' AND b.status= 'active' 
                AND b.category_id = :id ";*/
        //echo '{"service_providers": ' . json_encode($serviceProviders) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}
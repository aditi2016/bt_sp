<?php 
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:31 PM
 */



   
function getAllServicesByCategory($id){

    $sql = "SELECT a.name, a.id, a.pic_id, a.description FROM services AS a JOIN service_category_mapping AS b
            WHERE a.id = b.service_id AND a.status = 'active' AND b.status= 'active' AND b.category_id = :id ";
    
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"services": ' . json_encode($services) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
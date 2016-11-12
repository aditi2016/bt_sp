<?php



function requestContact(){

    global $app;

    $area_id = $app->request()->get('area_id');
    $profession_id = $app->request()->get('profession_id');
    
    $sql =  "SELECT id, name, gender, age, area, profession_id, mobile
                FROM candidates
                WHERE profession_id =  ':profession_id'
                AND area_id
                IN (

                SELECT worker_area_id
                FROM client_worker_mapping
                WHERE client_area_id =  ':area_id'
                ); ";
        
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $stmt->bindParam("area_id", $area_id);
        $stmt->bindParam("profession_id", $profession_id);
        $stmt->execute();
        $candidates = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"Worker Areas": ' . json_encode($candidates) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}
?>
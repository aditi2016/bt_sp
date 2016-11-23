<?php
function findWorkerArea(){

    global $app;

    $area_id = $app->request()->get('area_id');
    $profession_id = $app->request()->get('profession_id');
    $area_sql = "SELECT worker_area_id FROM client_worker_mapping WHERE client_area_id = ':area_id'";
    try {
        $db = getDB();
        $stmt = $db->query($area_sql);
        $stmt->bindParam("area_id", $area_id);
        //$stmt->bindParam("profession_id", $profession_id);
        $stmt->execute();
        $areas = $stmt->fetchAll(PDO::FETCH_OBJ);
        $candidates = [];
        //var_dump($candidates); exit();
        /*foreach ($areas as $key => $value) {
            $area_id=$value['area_id'];
            $sql = "SELECT id, name, gender, age, area, profession_id
                FROM candidates
                WHERE profession_id = ':profession_id' AND area_id = ".$area_id. ";";               
            $db = getDB();
            $stmt = $db->query($sql);
            //$stmt->bindParam("area_id", $area_id);
            $stmt->bindParam("profession_id", $profession_id);
            $stmt->execute();
            $candidates .= $stmt->fetchAll(PDO::FETCH_OBJ);
            //var_dump($candidates); exit();           
        }*/
        $db = null;
        echo '{"areas": ' . json_encode($areas) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
    
}

/*function findWorkerArea(){

    global $app;

    $area_id = $app->request()->get('area_id');
    $profession_id = $app->request()->get('profession_id');

    $sql = "SELECT id, name, gender, age, area, profession_id
                FROM candidates
                WHERE profession_id = ':profession_id'
                AND area_id=':area_id' ;";

       
    try {
        $db = getDB();
        $stmt = $db->query($sql);
        $stmt->bindParam("area_id", $area_id);
        $stmt->bindParam("profession_id", $profession_id);
        
        $stmt->execute();

        $area = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"Worker Areas": ' . json_encode($area) . '}';

        foreach ($area as $key => $value) {
            $area_id=$value['area_id'];

            $sql_1 = "SELECT worker_area_id
                        FROM client_worker_mapping
                            WHERE client_area_id = '$area_id';";

            try {
                $db = getDB();
                $stmt_1 = $db->query($sql_1);
//                $stmt_1->bindParam("profession_id", $profession_id);
                $stmt_1->execute();
                $area_1= $stmt_1->fetchAll(PDO::FETCH_OBJ);
                
                $db = null;
                echo '{"Worker Areas": ' . json_encode($candidates) . '}';

            } catch (PDOException $e) {
                //error_log($e->getMessage(), 3, '/var/tmp/php.log');
                echo '{"error":{"text":' . $e->getMessage() . '}}';
            }

        }

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}*/
?>
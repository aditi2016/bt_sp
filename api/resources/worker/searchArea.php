<?php


function searchArea(){

    global $app;

   
    $area_id = $app->request()->get('area_id');
    $gender = $app->request()->get('gender');
    $profession_id = $app->request()->get('profession_id');

  
    $area_str = ($area_id!=null)?" AND area_id = :area_id  ":"";
    $gender_str = ($gender!=null)?" AND gender = :gender ":"";

/*    $sql = "SELECT id,name,mobile,gender,age,area,profession_id 
                    FROM candidates WHERE profession_id =:profession_id 
                                        AND area IN (SELECT area FROM areas Where user) "
                            .$age_str
                            .$area_str
                            .$gender_str;*/
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        if($age!=null) $stmt->bindParam("age", $age);
        if($area_id!=null) $stmt->bindParam("area_id", $area_id);
        if($gender!=null) $stmt->bindParam("gender", $gender);

        $stmt->bindParam("profession_id", $profession_id);

        $stmt->execute();
        $candidates = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"candidates": ' . json_encode($candidates) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
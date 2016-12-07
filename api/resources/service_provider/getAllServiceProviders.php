<?php 
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:31 PM
 */



   
function getAllServiceProviders($id,$location){
    /*
     * SET @p = POINTFROMTEXT('POINT(28.4594965 77.0266383)');

SELECT  *
FROM service_providers where CalculateDistanceKm(X(@p), Y(@p), X(gps_location), Y(gps_location)) < 1 ;
    */
$p = explode(",",$location);
    $sql = "


            SELECT
              a.name, a.organization, a.description, a.experience, a.id, a.profile_pic_id, a.`reliability_score`,
              a.`reliability_count`, b.price,
              b.negotiable, b.hourly, b.status
            FROM service_providers AS a
                INNER JOIN service_provider_service_mapping AS b
                INNER JOIN services as c
                WHERE c.id = b.service_id AND a.id = b.service_provider_id AND b.service_id = :id AND
                      CalculateDistanceKm(".$p[0].", ".$p[1].", X(a.gps_location), Y(a.gps_location)) < c.range;";
    //var_dump($sql);
    
    $photosSql = "SELECT photo_id FROM `photos` WHERE `service_provider_id` = :id";

    $sqlUpdateAccess = "UPDATE `blueteam_service_providers`.`services` SET `accesses` = accesses + 1 WHERE `id` =:id";

    try {
        $db = getDB();

        //updating accesses
        $stmt = $db->prepare($sqlUpdateAccess);

        $stmt->bindParam("id", $id);

        $stmt->execute();

        //get all service providers of the id
        $stmt = $db->prepare($sql);
        
        $stmt->bindParam("id", $id);
        
        $stmt->execute();
        $serviceProviders = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($serviceProviders as $key => $serviceProvider) {
            $id = $serviceProvider->id;

            $stmt = $db->prepare($photosSql);
        
            $stmt->bindParam("id", $id);
            
            $stmt->execute();
            $serviceProvider->photos = $stmt->fetchAll(PDO::FETCH_OBJ);
        }

        $db = null;
        echo '{"service_providers": ' . json_encode($serviceProviders) . '}';
        
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
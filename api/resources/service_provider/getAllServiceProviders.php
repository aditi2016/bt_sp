<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:31 PM
 */




function getAllServiceProviders($id){
    global $app;
    $db = getDB();
    $location = $app->request()->get('location');
    $p = explode(",",$location);
    
    $areaUrl = "SELECT id, city_id, CalculateDistanceKm(".$p[0].", ".$p[1].", X( gps_location ) , Y( gps_location )) AS diatance FROM areas WHERE CalculateDistanceKm(".$p[0].", ".$p[1].", X( gps_location ) , Y( gps_location )) < 1 ORDER BY diatance ASC LIMIT 1";
    $stmt = $db->prepare($areaUrl);
    $stmt->execute();
    $areaData = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    if($areaData !== NULL){
       $area_id = $areaData[0]->id; 
    }
    else {
        $url = "http://api.sp.blueteam.in/location/".$location;
        $locDetails = json_decode(httpGet($url));
        $area_id = $locDetails->location_details->area->id;
    }

    /*
     * SET @p = POINTFROMTEXT('POINT(28.4594965 77.0266383)');

SELECT  *
FROM service_providers where CalculateDistanceKm(X(@p), Y(@p), X(gps_location), Y(gps_location)) < 1 ;
    */
    $p = explode(",",$location);
    $sql = "SELECT
              a.name, a.organization, a.description, a.experience, a.id, a.profile_pic_id, a.`reliability_score`,
              a.`reliability_count`, b.price,
              b.negotiable, b.hourly, b.status
            FROM service_providers AS a
                INNER JOIN service_provider_service_mapping AS b
                INNER JOIN services as c
                WHERE c.id = b.service_id AND a.id = b.service_provider_id AND b.service_id = :id AND
                      CalculateDistanceKm(".$p[0].", ".$p[1].", X(a.gps_location), Y(a.gps_location)) < (c.range+c.range*0.1*:look);";

    $photosSql = "SELECT photo_id FROM `photos` WHERE `service_provider_id` = :id";

    $sqlUpdateAccess = "UPDATE `blueteam_service_providers`.`services` SET `accesses` = accesses + 1 WHERE `id` =:id";

    $point = $p[0]." ".$p[1];
    //GeomFromText( 'POINT(:location)' )
    $sqlSelectSL = "select id from `blueteam_service_providers`.`service_looks` where service_id = :service_id and area_id = :area_id";
    $sqlServiceLooks = "INSERT INTO
                          `blueteam_service_providers`.`service_looks`
                            (`id`, `service_id`, `area_id`, `result_count`, `last_updated`)
                          VALUES
                            (NULL, :service_id, :area_id, :count, CURRENT_TIMESTAMP)
                          ;";

    $sqlSLUpdate = "UPDATE `service_looks` SET `result_count` = :count1, count= count+1 WHERE id= :id";
// I need to track which area people are looking for which service and i have how much results for it
// service_looks ( service_id, area_id, result_count)
    //who was looking for it ( service_look_id, customer_name, customer_mobile)

    try {
        

        //updating accesses
        $stmt = $db->prepare($sqlUpdateAccess);

        $stmt->bindParam("id", $id);

        $stmt->execute();
        $lookupId = 0;

        //get all service providers of the id

        for($i=0;$i<3;$i++){

            $stmt = $db->prepare($sql);

            $stmt->bindParam("id", $id);
            $stmt->bindParam("look", $i);

            $stmt->execute();
            $serviceProviders = $stmt->fetchAll(PDO::FETCH_OBJ);
            $count = count($serviceProviders);
            if($i<=0){
                //add service not found at location request

                $stmt = $db->prepare($sqlSelectSL);

                $stmt->bindParam("service_id", $id);
                $stmt->bindParam("area_id", $area_id);

                $stmt->execute();
                $SL = $stmt->fetchAll(PDO::FETCH_OBJ);

                if(count($SL) == 0) {

                    $stmt = $db->prepare($sqlServiceLooks);

                    $stmt->bindParam("service_id", $id);
                    $stmt->bindParam("area_id", $area_id);
                    $stmt->bindParam("count", $count);


                    $stmt->execute();
                    $lookupId = $db->lastInsertId();
                }
                else {
                    $lookupId = $SL[0]->id;
                    $stmt = $db->prepare($sqlSLUpdate);

                    $stmt->bindParam("id", $lookupId);

                    $stmt->bindParam("count1", $count);

                    $stmt->execute();

                }
            }

            if($count >= 1) break;
        }

        foreach ($serviceProviders as $key => $serviceProvider) {
            $id = $serviceProvider->id;

            $stmt = $db->prepare($photosSql);

            $stmt->bindParam("id", $id);

            $stmt->execute();
            $serviceProvider->photos = $stmt->fetchAll(PDO::FETCH_OBJ);
        }

        $db = null;
        echo '{"service_providers": ' . json_encode($serviceProviders) . ',"lookup_id":'.$lookupId.'}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
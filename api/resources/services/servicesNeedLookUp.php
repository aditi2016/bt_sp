<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 12/8/16
 * Time: 5:11 PM
 */

/*
 * SELECT a.id, a.service_id, b.name AS service_name, a.area_id, c.name AS area_name, c.city_id, d.name AS city_name, a.result_count, a.count
FROM `service_looks` AS a
INNER JOIN services AS b
INNER JOIN areas AS c
INNER JOIN cities AS d
WHERE a.result_count <6
AND a.area_id = c.id
AND a.service_id = b.id
AND c.city_id = d.id
 * */

function servicesNeedLookUp(){

    $sql = "SELECT
              a.id, a.service_id, b.name AS service_name, a.area_id, c.name AS area_name,
              X(c.gps_location) as lat_gps_location , Y(c.gps_location) as lng_gps_location , c.city_id,
              d.name AS city_name, a.result_count, a.count
              FROM `service_looks` AS a
                INNER JOIN services AS b
                INNER JOIN areas AS c
                INNER JOIN cities AS d
                  WHERE a.result_count <3
                        AND a.area_id = c.id
                        AND a.service_id = b.id
                        AND c.city_id = d.id ";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach($services as $service){
            $url = "http://api.sp.blueteam.in/service/".$service->service_id."?location=".$service->lat_gps_location.",".$service->lng_gps_location;
            httpGet($url);
        }

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;
        echo '{"services": ' . json_encode($services) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
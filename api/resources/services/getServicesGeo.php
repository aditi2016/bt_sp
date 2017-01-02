<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 1/2/17
 * Time: 1:42 PM
 */

/*
 * SELECT c.name, a.name, s.name
FROM areas AS a
INNER JOIN services AS s
INNER JOIN cities AS c
INNER JOIN service_provider_service_mapping AS spm
INNER JOIN service_providers AS sp
WHERE a.city_id = c.id
AND spm.service_id = s.id
AND spm.service_provider_id = sp.id
AND CalculateDistanceKm(
X( a.gps_location ) , Y( a.gps_location ) , X( sp.gps_location ) , Y( sp.gps_location )
) < s.range

*/

function getServicesGeo(){


    $sql = "SELECT c.name as city, a.name as area, s.id as service_id, s.name as service,X( a.gps_location ) as lat , Y( a.gps_location ) as lng
                    FROM areas AS a
                    INNER JOIN services AS s
                    INNER JOIN cities AS c
                    INNER JOIN service_provider_service_mapping AS spm
                    INNER JOIN service_providers AS sp
                    WHERE a.city_id = c.id
                    AND spm.service_id = s.id
                    AND spm.service_provider_id = sp.id
                    AND CalculateDistanceKm(
                    X( a.gps_location ) , Y( a.gps_location ) , X( sp.gps_location ) , Y( sp.gps_location )
                    ) < s.range";

    try {
        if(!file_exists ("services.json") || (time()-filemtime("services.json") > 86400)) {
            $servicesJson = fopen("services.json", "w") or die("Unable to open file!");
            $db = getDB();
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $services = $stmt->fetchAll(PDO::FETCH_OBJ);

            $cities = array();

            foreach ($services as $service) {
                //var_dump($service);die();
                if (array_key_exists($service->city, $cities)) {
                    if (array_key_exists($service->area, $cities[$service->city])) {

                        $cities[$service->city][$service->area]['services'][$service->service] = $service->service_id;
                    } else {
                        $cities[$service->city][$service->area] = array(
                            'services' => array($service->service => $service->service_id),
                            'location' => $service->lat . "," . $service->lng
                        );

                    }

                } else {
                    $cities[$service->city] = array(
                        $service->area => array(
                            'services' => array($service->service => $service->service_id),
                            'location' => $service->lat . "," . $service->lng
                        ));

                }


            }


            $db = null;

            //$_SESSION['geo_services'] = $cities;
            fwrite($servicesJson, json_encode($cities));
            fclose($servicesJson);
        }else{
            $cities = json_decode(file_get_contents('services.json'));
        }

        echo '{"services": ' . json_encode($cities) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
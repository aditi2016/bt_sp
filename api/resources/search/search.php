<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/20/16
 * Time: 7:01 PM
 */

function search($keywords)
{

    $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];

    $sql = "SELECT name, id, pic_id, description FROM services WHERE name LIKE :keywords ";

    $insertSql = "INSERT INTO `blueteam_service_providers`.`searchs` (`id`, `string`, `creation`, `ip` , `result_count`)
                        VALUES (NULL, :keywords, CURRENT_TIMESTAMP, :ip , :result_count);";

    try {
        $db = getDB();

        $keywordsLike = "%$keywords%";
        $stmt = $db->prepare($sql);
        $stmt->bindParam("keywords", $keywordsLike);
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        $stmt = $db->prepare($insertSql);
        $stmt->bindParam("keywords", $keywords);
        $stmt->bindParam("ip", $ip);
        $stmt->bindParam("result_count", count($services));

        $stmt->execute();



        $db = null;
        echo '{"allServices": ' . json_encode($services) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}
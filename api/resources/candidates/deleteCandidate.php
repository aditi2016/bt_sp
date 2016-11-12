<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:32 PM
 */

function deleteCandidate($candidate_id){

    $sql = "UPDATE candidates SET status = 'deleted' WHERE id=:candidate_id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("candidate_id", $candidate_id);
        $stmt->execute();

        $db = null;
        echo '{"status": "success" }';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}

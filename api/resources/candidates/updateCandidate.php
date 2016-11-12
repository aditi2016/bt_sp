<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:32 PM
 */

function updateCandidate($candidate_id){

    $request = \Slim\Slim::getInstance()->request();

    $candidate = json_decode($request->getBody());

    $sql = "UPDATE
              candidates
                SET
                  name=:name,
                  area=:area,
                  age=:age,
                      dob=:dob,
                      address=:address,
                      gender=:gender,
                      profession_id=:profession_id,
                      native_place=:native_place,
                      native_address=:native_address,
                      remarks=:remarks,
                      status= 'verified'
                    WHERE id=:candidate_id";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("name", $candidate->name);
        $stmt->bindParam("area", $candidate->area);
        $stmt->bindParam("age", $candidate->age);
        $stmt->bindParam("dob", $candidate->dob);
        $stmt->bindParam("address", $candidate->address);
        $stmt->bindParam("gender", $candidate->gender);
        $stmt->bindParam("profession_id", $candidate->profession_id);
        $stmt->bindParam("native_place", $candidate->native_place);
        $stmt->bindParam("native_address", $candidate->native_address);
        $stmt->bindParam("remarks", $candidate->remarks);
        //$stmt->bindParam("status", $candidate->status);
        $stmt->bindParam("candidate_id", $candidate_id);

        $stmt->execute();

        $db = null;
        echo '{"status": "success" }';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":"' . $e->getMessage() . '"}}';
    }

}
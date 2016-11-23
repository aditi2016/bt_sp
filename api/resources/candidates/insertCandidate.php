<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:31 PM
 */

function insertCandidate(){

    $request = \Slim\Slim::getInstance()->request();

    $candidate = json_decode($request->getBody());

    $sql = "INSERT
                  INTO
                    candidates (
                      name,
                      mobile,
                      area,
                      age,
                      dob,
                      address,
                      gender,
                      user_id,
                      ref_id,
                      creation,
                      profession_id,
                      native_place,
                      native_address,
                      remarks,
                      status
                      )
                  VALUES (
                      :name,
                      :mobile,
                      :area,
                      :age,
                      :dob,
                      :address,
                      :gender,
                      :user_id,
                      :ref_id,
                      :creation,
                      :profession_id,
                      :native_place,
                      :native_address,
                      :remarks,
                      :status
                      )";
    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        $candidate->status = "new";

        $stmt->bindParam("name", $candidate->name);
        $stmt->bindParam("mobile", $candidate->mobile);
        $stmt->bindParam("area", $candidate->area);
        $stmt->bindParam("age", $candidate->age);
        $stmt->bindParam("dob", $candidate->dob);
        $stmt->bindParam("address", $candidate->address);
        $stmt->bindParam("gender", $candidate->gender);
        $stmt->bindParam("user_id", $candidate->user_id);
        $stmt->bindParam("ref_id", $candidate->ref_id);
        $stmt->bindParam("profession_id", $candidate->profession_id);
        $stmt->bindParam("native_place", $candidate->native_place);
        $stmt->bindParam("native_address", $candidate->native_address);
        $stmt->bindParam("remarks", $candidate->remarks);
        $stmt->bindParam("status", $candidate->status);
        $stmt->bindParam("creation", date("Y-m-d H:i:s"));

        $stmt->execute();

        $candidate->id = $db->lastInsertId();
        $db = null;

        echo '{"candidate": ' . json_encode($candidate) . '}';

    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":"' . $e->getMessage() . '"}}';
    }
}
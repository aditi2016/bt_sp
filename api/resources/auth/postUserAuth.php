<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/22/16
 * Time: 2:13 PM
 */


function userAuth(){

    $request = \Slim\Slim::getInstance()->request();

    $user = json_decode($request->getBody());


    $sql = "SELECT * FROM users WHERE username =:username and password=:password ";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindParam("username", $user->username);
        $stmt->bindParam("password", $user->password);


        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);

        $db = null;

        if(count($users) == 1)
            echo '{"auth": "true"}';
        else
            echo '{"auth": "false"}';


    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}



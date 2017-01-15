<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 11/20/16
 * Time: 7:41 PM
 *
 * name,mobile,service,amount,service_tax
 */

function insertServiceProviderExpanse($id){

    $request = \Slim\Slim::getInstance()->request();


    $expanse = json_decode($request->getBody());
    if (is_null($expanse)){
        echo '{"error":{"text":"Invalid Json"}}';
        die();
    }


    $sql = "INSERT INTO `expanses`
                  (`service_provider_id`, `give_to`, `mobile`, `amount`, `type`)
                  VALUES
                  (:id,:give_to,:mobile,:amount,:type);";

    $sqlGetSP = "SELECT * from service_providers where id = :id;";

    try {
        $db = getDB();
        $stmt = $db->prepare($sql);
        //$service_provider->status = "new";

        $stmt->bindParam("id", $id);
        $stmt->bindParam("give_to", $expanse->receiver_name);
        $stmt->bindParam("mobile", $expanse->receiver_mobile);
        $stmt->bindParam("amount", $expanse->amount);
        $stmt->bindParam("type", $expanse->type);


        $stmt->execute();

        $expanse->id = $db->lastInsertId();

        $stmt = $db->prepare($sqlGetSP);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $sp = $stmt->fetchAll(PDO::FETCH_OBJ);
        //organization

        $message = "You have received Rs." .$expanse->amount . " by ".$sp[0]->organization." (Partner Id: $id)\n as " . $expanse->type . "\n Get BT Partner App\n http://goo.gl/rLK3s5";

        if($expanse->send_expanse)
            sendSMS($expanse->receiver_mobile, $message);

        $db = null;
        echo '{"expanses": ' . json_encode($expanse) . '}';
    } catch (PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/php.log');
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

}

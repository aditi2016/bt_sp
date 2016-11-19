<?php
/**
 * Created by PhpStorm.
 * User: spider-ninja
 * Date: 6/4/16
 * Time: 1:12 PM
 */

\Slim\Slim::registerAutoloader();

global $app;

if(!isset($app))
    $app = new \Slim\Slim();


//$app->response->headers->set('Access-Control-Allow-Origin',  'http://localhost');
$app->response->headers->set('Access-Control-Allow-Credentials',  'true');
/*$app->response->headers->set('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS');
$app->response->headers->set('Access-Control-Allow-Headers', 'X-CSRF-Token, X-Requested-With, Accept, Accept-Version, Content-Length, Content-MD5, Content-Type, Date, X-Api-Version');
*/
$app->response->headers->set('Content-Type', 'application/json');

/* Starting routes */

$app->get('/service_provider','getServiceProviderByType');
$app->get('/services/catagory/:id','getAllServicesByCategory');
$app->get('/services/catagory/','getAllServices');
/*$app->get('/service_provider/:id','getServiceProviderById');*/


$app->post('/service_provider', 'insertServiceProvider');
$app->put('/service_provider/:id','updateServiceProvider');
/*$app->post('/service_provider/:id','updateServiceProvider');*/

$app->post('/auth', 'userAuth');

/* Ending Routes */

$app->run();
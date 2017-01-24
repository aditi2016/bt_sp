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
$app->response->headers->set('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS');
/*$app->response->headers->set('Access-Control-Allow-Headers', 'X-CSRF-Token, X-Requested-With, Accept, Accept-Version, Content-Length, Content-MD5, Content-Type, Date, X-Api-Version');
*/
$app->response->headers->set('Content-Type', 'application/json');

/* Starting routes */

$app->get('/service_provider','getServiceProviderByType');
$app->get('/service_provider/:id','getServiceProvider');

// services resource
$app->get('/services','getAllServices');
$app->get('/services/:id','getServicesById');
$app->get('/service/:id','getAllServiceProviders');
/*$app->get('/service_provider/:id','getServiceProviderById');*/


// service provider resouce
$app->post('/service_provider', 'insertServiceProvider');
$app->put('/service_provider/:id','updateServiceProvider');
$app->post('/service_provider/:id','updateServiceProvider');

$app->post('/auth', 'userAuth');

// search api
$app->get('/search/:keywords','search');

// invoice api
$app->post('/service_provider/:id/invoice', 'insertServiceProviderInvoice');
$app->get('/service_provider/:id/invoice', 'getServiceProviderInvoice');

// expanses api
$app->post('/service_provider/:id/expanse', 'insertServiceProviderExpanse');
$app->get('/service_provider/:id/expanse', 'getServiceProviderExpanses');
$app->get('/service_provider/:id/expanse_types', 'getExpansesTypes');

// take feedback request api
$app->post('/service_provider/:id/feedback_request', 'insertServiceProviderFeedbackRequest');

// create campaigning request
$app->post('/service_provider/:id/campaigning_request', 'createCampaignRequest');

// cities
$app->get('/cities','getAllCities');
$app->get('/cities/:id/areas','getAllCityAreas');

// location
$app->get('/location/:id','getLocationDetails');
$app->get('/category','getCategories');
$app->get('/search','getSearchResults');
$app->get('/interest','getInteresredServices');


// service provider services
$app->post('/services', 'insertNewServices');
$app->post('/service_provider/:id/services', 'insertServices');

$app->post('/mobac/:mobile','insertCallLogs');
$app->get('/mobac','getCallLogs');
$app->get('/mobac/recent','getRecentCall');
$app->put('/mobac/:id','updateCallLogs');

/* Ending Routes */

$app->run();
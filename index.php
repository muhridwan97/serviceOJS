<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'local'));

    // show errors when working on local
    if(APPLICATION_ENV === 'local'){
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

require 'vendor/autoload.php';
require 'configs/'.strtolower(APPLICATION_ENV).'.config.php';

$app = new \Slim\App;
$app->get('/api/pengguna', function ($request,$response) {
    $data = array(
        'nama' => 'Ridwan',
        'umur' => 21
    );
    return $response->withJson($data);
});
$app->get('/forum/{title}', function ($request,$response,$args) {

    return $args['title'];
});

$app->GET('/api/user', controller\userController::class. ':getData');
$app->GET('/api/userSubmit', controller\userController::class. ':getUserSubmit');
$app->GET('/api/userSubmitAntrian', controller\userController::class. ':getUserSubmitAntrian');
$app->GET('/api/submission', controller\userController::class. ':getDaftarSubmission');
$app->GET('/api/userFiles/{userId}', controller\userController::class. ':getUserFiles');
$app->GET('/api/lihatFilesAsli/{fileId}', controller\fileController::class. ':getFilesAsli');
$app->run();
?>

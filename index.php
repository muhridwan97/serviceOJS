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


use Slim\Http\UploadedFile;

$app = new \Slim\App;
//untuk upload
// $container = $app->getContainer();
// $container['upload_directory'] = __DIR__ . '/uploads';

$app->post('/api/uploadArsip', function(Request $request, Response $response) {
   
    $directory = 'C:\xampp\htdocs\jurnal\dataJurnal\journals\2\articles';
    //mkdir("hallo\hai",0777,true);
    $uploadedFiles = $request->getUploadedFiles();
    
    // handle single input with single file upload
    $uploadedFile = $uploadedFiles['fileArsip'];
    $basename = pathinfo($uploadedFile->getClientFilename(), PATHINFO_FILENAME);
    //print_r($basename);
    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
        $response->write('uploaded ' . $filename . '<br/>');
    }

});

// $app->post('/api/uploadArsip', controller\fileController::class. ':uploadArsip');


$app->get('/api/pengguna', function ($request,$response) {
    $data = array(
        'nama' => 'Ridwan',
        'umur' => 21
    );
    return $response->withJson($data);
});
$app->post('/forum', function ($request,$response,$args) {

    return $args['title'];
});

$app->GET('/api/user', controller\userController::class. ':getData');
$app->GET('/api/userSubmit', controller\userController::class. ':getUserSubmit');
$app->GET('/api/userSubmitAntrian', controller\userController::class. ':getUserSubmitAntrian');
$app->GET('/api/submission', controller\userController::class. ':getDaftarSubmission');
$app->GET('/api/userFiles/{userId}', controller\userController::class. ':getUserFiles');
$app->GET('/api/lihatFilesAsli/{fileId}', controller\fileController::class. ':getFilesAsli');

$app->group('/api/antrian', function () use ($app) {
    $app->post('/submitIn', controller\userController::class. ':setSubmitIn');
    $app->get('/sudah-selesai/{nim}', controller\PelanggaranController::class. ':sudahSelesai');
});

$app->post('/api/verifikasi', controller\userController::class. ':setVerifikasi');
$app->get('/api/metadata/{userId}', controller\userController::class. ':getMetadata');
$app->post('/api/setMetadata', controller\userController::class. ':setMetadata');
$app->post('/api/tambahPenulis', controller\userController::class. ':tambahPenulis');
$app->GET('/api/publication', controller\userController::class. ':getDaftarPublication');
$app->GET('/api/publicationIssue', controller\userController::class. ':getPublicationIssue');
$app->GET('/api/publicationMaterial/{id}', controller\userController::class. ':getPublicationMaterial');
$app->run();
?>

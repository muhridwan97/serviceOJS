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


 $app->post('/api/uploadArsip', controller\fileController::class. ':uploadArsip');
 $app->post('/api/uploadGalley', controller\fileController::class. ':uploadGalley');
 $app->post('/api/uploadGalleyEdit', controller\fileController::class. ':uploadGalleyEdit');
 $app->post('/api/uploadArsipEdit', controller\fileController::class. ':uploadArsipEdit');

$app->GET('/api/user', controller\userController::class. ':getData');
$app->GET('/api/userSubmit/{editor_id}', controller\userController::class. ':getUserSubmit');
$app->GET('/api/userSubmitAntrian', controller\userController::class. ':getUserSubmitAntrian');
$app->GET('/api/submission', controller\userController::class. ':getDaftarSubmission');
$app->GET('/api/userFiles/{userId}', controller\userController::class. ':getUserFiles');
$app->GET('/api/userFilesPub/{userId}', controller\userController::class. ':getUserFilesPub');
$app->GET('/api/userFilesArsip/{userId}', controller\userController::class. ':getUserFilesArsip');
$app->GET('/api/lihatFilesAsli/{fileId}', controller\fileController::class. ':getFilesAsli');
$app->GET('/api/lihatFilesAsliPub/{fileId}', controller\fileController::class. ':getFilesAsliPub');
$app->GET('/api/lihatFilesAsliArsip/{fileId}', controller\fileController::class. ':getFilesAsliArsip');

$app->group('/api/antrian', function () use ($app) {
    $app->post('/submitIn', controller\userController::class. ':setSubmitIn');
    $app->get('/sudah-selesai/{nim}', controller\PelanggaranController::class. ':sudahSelesai');
});

$app->post('/api/verifikasi', controller\userController::class. ':setVerifikasi');
$app->get('/api/metadata/{userId}', controller\userController::class. ':getMetadata');
$app->get('/api/keyword/{userId}', controller\userController::class. ':getKeyword');
$app->get('/api/keywordInd/{userId}', controller\userController::class. ':getKeywordInd');
$app->post('/api/setMetadata', controller\userController::class. ':setMetadata');
$app->post('/api/tambahPenulis', controller\userController::class. ':tambahPenulis');
$app->post('/api/editPenulis', controller\userController::class. ':editPenulis');
$app->post('/api/hapusPenulis', controller\userController::class. ':hapusPenulis');
$app->GET('/api/publication', controller\userController::class. ':getDaftarPublication');
$app->GET('/api/publicationIssue', controller\userController::class. ':getPublicationIssue');
$app->GET('/api/publicationMaterial/{id}', controller\userController::class. ':getPublicationMaterial');
$app->post('/api/setPublication', controller\userController::class. ':setPublication');
$app->get('/api/getPage/{issueId}', controller\userController::class. ':getPage');
$app->GET('/api/getEmail/{userId}', controller\userController::class. ':getEmail');
$app->post('/api/decline', controller\userController::class. ':decline');
$app->GET('/api/getTanggalSubmission', controller\userController::class. ':getTanggalSubmission');
$app->GET('/api/getTanggalPublication', controller\userController::class. ':getTanggalPublication');
$app->GET('/api/getBulanSubmission', controller\userController::class. ':getBulanSubmission');
$app->GET('/api/getBulanPublication', controller\userController::class. ':getBulanPublication');
$app->GET('/api/getTotalSubPub', controller\userController::class. ':getTotalSubPub');
$app->run();
?>

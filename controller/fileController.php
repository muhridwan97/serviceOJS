<?php

namespace controller;

class fileController{

    
    public function getFilesAsli($request, $response, $args) {
        $fileId = $args['fileId']; 
        $userFiles = new \model\fileModel();
        $dataUserFiles = $userFiles->getFilesAsli($fileId);
        $submission_id;
        $namaFile;
        foreach($dataUserFiles as $data){
            $submission_id=$data->submission_id;
            $namaFile=$data->submission_id;
            $namaFile=$namaFile.'-'.$data->genre_id;
            $namaFile=$namaFile.'-'.$data->file_id;
            $namaFile=$namaFile.'-'.$data->revision;
            $namaFile=$namaFile.'-'.$data->file_stage;
            $tgl = (string)$data->date_uploaded;
            $tgl = explode(" ",$tgl);
            $tgl = $tgl[0];
            $tgl = explode("-",$tgl);
            $tgl = $tgl[0]."".$tgl[1]."".$tgl[2];
            $format = $data->original_file_name;
            $format = explode(".",$format);
            $format =  $format[count($format)-1];
            $namaFile=$namaFile.'-'.$tgl;
            $namaFile=$namaFile.'.'.$format;
        }
        $path = "http://localhost/jurnal/dataJurnal/journals/2/articles/$submission_id/submission/$namaFile";
        $data = array('alamat' => $path);
        return $response->withJson($data);
    }
    
    public function uploadArsip($request, $response, $args) {
        $directory = __DIR__ . '/uploads';

    $uploadedFiles = $request->getUploadedFiles();

    // handle single input with single file upload
    $uploadedFile = $uploadedFiles['fileArsip'];
    print_r($uploadedFiles);
    if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        //$filename = $this->moveUploadedFile($directory, $uploadedFile);
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
        return $response->write('uploaded ' . $filename . '<br/>');
    }
    }

//     public function moveUploadedFile($directory, UploadedFile $uploadedFile)
// {
//     $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
//     $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
//     $filename = sprintf('%s.%0.8s', $basename, $extension);

//     $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

//     return $filename;
// }
}


?>
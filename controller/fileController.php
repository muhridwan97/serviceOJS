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
    public function getFilesAsliPub($request, $response, $args) {
        $fileId = $args['fileId']; 
        $userFiles = new \model\fileModel();
        $dataUserFiles = $userFiles->getFilesAsliPub($fileId);
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
        $path = "http://localhost/jurnal/dataJurnal/journals/2/articles/$submission_id/submission/proof/$namaFile";
        $data = array('alamat' => $path);
        return $response->withJson($data);
    }
    public function getFilesAsliArsip($request, $response, $args) {
        $fileId = $args['fileId']; 
        $userFiles = new \model\fileModel();
        $dataUserFiles = $userFiles->getFilesAsliArsip($fileId);
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
        $path = "http://localhost/jurnal/dataJurnal/journals/2/articles/$submission_id/submission/productionReady/$namaFile";
        $data = array('alamat' => $path);
        return $response->withJson($data);
    }
    
    public function uploadArsip($request, $response, $args) {
        $body=$request->getParsedBody();
        $submission_id=$body['submission_id'];
        $editor_id=$body['editor_id'];
        $uploadedFiles = $request->getUploadedFiles();
         
         // handle single input with single file upload
         $uploadedFile = $uploadedFiles['fileArsip'];
         $basename = pathinfo($uploadedFile->getClientFilename(), PATHINFO_FILENAME);
         $namaAsli = $uploadedFile->getClientFilename();
         $size = $uploadedFile->getSize();
         $ekstensi = $uploadedFile->getClientMediaType();
         $date= date('Y-m-d');
         $date = explode("-",$date);
         $date = $date[0]."".$date[1]."".$date[2];
         //print_r($namaAsli);
        //print_r($date);
        $data = array(
			'submission_id' => $submission_id,
			'namaAsli' => $namaAsli,
            'size' => $size,
            'ekstensi' => $ekstensi,
            'editor_id' => $editor_id
			);	
        
        $userFiles = new \model\fileModel();
        $dataUserFiles = $userFiles->setFileArsip($data);
        $file_id=$dataUserFiles[0]->file_id;
        print_r($data);
        $path="C:\\xampp\\htdocs\\jurnal\\dataJurnal\\journals\\2\\articles\\$submission_id\\submission\productionReady";
        
         if (!file_exists($path)) {
         mkdir($path,0777,true);
         }
         $directory = $path;
         
        // print_r($uploadedFiles['fileArsip']);
         
         
         if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
              $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
              $namaBaru="".$submission_id."-"."13"."-".$file_id."-"."1"."-"."11"."-".$date;
              $filename = sprintf('%s.%0.8s', $namaBaru, $extension);
     //print_r($filename);
             $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
             return $response->write('uploaded ' . $filename . 'berhasil <br/>');
         }else{
             return $response->write('gagal <br/>');
         }
    }
    public function uploadGalley($request, $response, $args) {
        $body=$request->getParsedBody();
        $submission_id=$body['submission_id'];
        $editor_id=$body['editor_id'];
        $uploadedFiles = $request->getUploadedFiles();
         
         // handle single input with single file upload
         $uploadedFile = $uploadedFiles['fileGalley'];
         $basename = pathinfo($uploadedFile->getClientFilename(), PATHINFO_FILENAME);
         $namaAsli = $uploadedFile->getClientFilename();
         $size = $uploadedFile->getSize();
         $ekstensi = $uploadedFile->getClientMediaType();
         $date= date('Y-m-d');
         $date = explode("-",$date);
         $date = $date[0]."".$date[1]."".$date[2];
         //print_r($namaAsli);
        //print_r($date);
        $data = array(
			'submission_id' => $submission_id,
			'namaAsli' => $namaAsli,
            'size' => $size,
            'ekstensi' => $ekstensi,
            'editor_id' => $editor_id
			);	
        
        $userFiles = new \model\fileModel();
        $dataUserFiles = $userFiles->setFileGalley($data);
        $file_id=$dataUserFiles[0]->file_id;
        // print_r($file_id);
        $path="C:\\xampp\\htdocs\\jurnal\\dataJurnal\\journals\\2\\articles\\$submission_id\\submission\proof";
        
         if (!file_exists($path)) {
         mkdir($path,0777,true);
         }
         $directory = $path;
         
        // print_r($uploadedFiles['fileArsip']);
         
         
         if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
              $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
              $namaBaru="".$submission_id."-"."13"."-".$file_id."-"."1"."-"."10"."-".$date;
              $filename = sprintf('%s.%0.8s', $namaBaru, $extension);
     //print_r($filename);
             $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
             return $response->write('uploaded ' . $filename . 'berhasil <br/>');
         }else{
             return $response->write('gagal <br/>');
         }
    }
}


?>
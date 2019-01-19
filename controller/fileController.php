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
        $body=$request->getParsedBody();
        $submission_id=$body['submission_id'];
        $uploadedFiles = $request->getUploadedFiles();
         
         // handle single input with single file upload
         $uploadedFile = $uploadedFiles['fileArsip'];
         $basename = pathinfo($uploadedFile->getClientFilename(), PATHINFO_FILENAME);
         $namaAsli = $uploadedFile->getClientFilename();
         $size = $uploadedFile->getSize();
         $ekstensi = $uploadedFile->getClientMediaType();
         //print_r($namaAsli);
        //print_r($submission_id);
        $data = array(
			'submission_id' => $submission_id,
			'namaAsli' => $namaAsli,
            'size' => $size,
            'ekstensi' => $ekstensi
			);	
        
        $userFiles = new \model\fileModel();
        $dataUserFiles = $userFiles->setFileArsip($data);
        $path="C:\\xampp\\htdocs\\jurnal\\dataJurnal\\journals\\2\\articles\\$submission_id\\submission\productionReady";
        
         if (!file_exists($path)) {
         mkdir($path,0777,true);
         }
         //mkdir("C:\\xampp\\htdocs\\jurnal\\dataJurnal\\journals\\2\\articles\\$submission_id\\submission\proof",0777,true);
         $directory = $path;
         
        // print_r($uploadedFiles['fileArsip']);
         
         
        //  if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
        //      $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
             
        //      $filename = sprintf('%s.%0.8s', $basename, $extension);
     
        //      $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
        //      return $response->write('uploaded ' . $filename . 'berhasil <br/>');
        //  }else{
        //      return $response->write('gagal <br/>');
        //  }
    }
    public function uploadGalley($request, $response, $args) {
        $submission_id=$request->getParsedBody();
        $submission_id=$submission_id['submission_id'];
        $path="C:\\xampp\\htdocs\\jurnal\\dataJurnal\\journals\\2\\articles\\$submission_id\\submission\proof";
         if (!file_exists($path)) {
         mkdir($path,0777,true);
         }
         $directory = $path;
         $uploadedFiles = $request->getUploadedFiles();
         
         // handle single input with single file upload
         $uploadedFile = $uploadedFiles['fileGalley'];
         //print_r($uploadedFiles['fileArsip']);
         $basename = pathinfo($uploadedFile->getClientFilename(), PATHINFO_FILENAME);
         //print_r($directory);
         
         if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
             $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
             
             $filename = sprintf('%s.%0.8s', $basename, $extension);
     
             $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
             return $response->write('uploaded ' . $filename . 'berhasil <br/>');
         }else{
             return $response->write('gagal <br/>');
         }
    }
}


?>
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
}


?>
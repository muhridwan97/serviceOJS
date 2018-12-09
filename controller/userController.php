<?php

namespace controller;

class userController{
    public function getData($request, $response, $args) {
        $user = new \model\userModel();
        $dataUser = $user->getUser();
        return $response->withJson($dataUser);
    }

    public function getDaftarSubmission($request, $response, $args) {
        $user = new \model\userModel();
        $dataUser = $user->getSubmission();
        return $response->withJson($dataUser);
    }
    public function getUserSubmit($request, $response, $args) {
        $user = new \model\userModel();
        $dataUser = $user->getUserSubmit();
        return $response->withJson($dataUser);
    }
    public function getUserSubmitAntrian($request, $response, $args) {
        $user = new \model\userModel();
        $dataUser = $user->getUserSubmitAntrian();
        return $response->withJson($dataUser);
    }
    
    public function getUserFiles($request, $response, $args) {
        $userId = $args['userId']; 
        $userFiles = new \model\userModel();
        $dataUserFiles = $userFiles->getUserFiles($userId);
        return $response->withJson($dataUserFiles);
    }
    public function setSubmitIn($request, $response, $args) {
        $data = $request->getParsedBody();
        //print_r($data);
        //return $response->withJson($data);
        $userFiles = new \model\userModel();
        $dataUserFiles = $userFiles->setSubmitIn($data);
        return $response->withJson($dataUserFiles);
    }
}


?>
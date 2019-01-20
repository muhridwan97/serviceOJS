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
        $editor_id = $args['editor_id']; 
        $user = new \model\userModel();
        $dataUser = $user->getUserSubmit($editor_id);
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
        $submission_id=$userFiles->getSubmissionId($userId);
        //print_r($submission_id);
        $dataUserFiles = $userFiles->getUserFiles($submission_id);
        return $response->withJson($dataUserFiles);
    }
    public function setSubmitIn($request, $response, $args) {
        $data = $request->getParsedBody();
        //print_r($data);
        //return $response->withJson($data);
        $userFiles = new \model\userModel();
        $dataUserFiles = $userFiles->setSubmitIn($data);
        //return $response->withJson($dataUserFiles);
    }
    public function setVerifikasi($request, $response, $args) {
        $data = $request->getParsedBody();
        //print_r($data);
        //return $response->withJson($data);
        $userFiles = new \model\userModel();
        $dataUserFiles = $userFiles->setVerifikasi($data);
        //return $response->withJson($dataUserFiles);
    }
    public function getMetadata($request, $response, $args) {
        $userId = $args['userId']; 
        $userMetadata = new \model\userModel();
        $submission_id=$userMetadata->getSubmissionId($userId);
        
        $dataUserMetadata = $userMetadata->getMetadata($submission_id);
        return $response->withJson($dataUserMetadata);
    }
    public function getKeyword($request, $response, $args) {
        $userId = $args['userId']; 
        $userMetadata = new \model\userModel();
        $submission_id=$userMetadata->getSubmissionId($userId);
        
        $dataUserMetadata = $userMetadata->getKeyword($submission_id);
        return $response->withJson($dataUserMetadata);
        
    }
    public function getPage($request, $response, $args) {
        $issueId = $args['issueId']; 
        $userMetadata = new \model\userModel();        
        $data = $userMetadata->getPage($issueId);
        return $response->withJson($data);
        
    }
    public function setMetadata($request, $response, $args) {
        $data = $request->getParsedBody();
        //print_r($data);
        //return $response->withJson($data);
        $userFiles = new \model\userModel();
        $dataUserFiles = $userFiles->setMetadata($data);
        //return $response->withJson($dataUserFiles);
    }
    public function decline($request, $response, $args) {
        $data = $request->getParsedBody();
        //print_r($data);
        //return $response->withJson($data);
        $userFiles = new \model\userModel();
        $dataUserFiles = $userFiles->decline($data);
        //return $response->withJson($dataUserFiles);
    }
    public function tambahPenulis($request, $response, $args) {
        $data = $request->getParsedBody();
        //print_r($data);
        //return $response->withJson($data);
        $userFiles = new \model\userModel();
        $dataUserFiles = $userFiles->tambahPenulis($data);
        //return $response->withJson($dataUserFiles);
    }
    public function getDaftarPublication($request, $response, $args) {
        $user = new \model\userModel();
        $dataUser = $user->getDaftarPublication();
        return $response->withJson($dataUser);;
    }
    
    public function getPublicationIssue($request, $response, $args) {
        $user = new \model\userModel();
        $dataUser = $user->getPublicationIssue();
        return $response->withJson($dataUser);;
    }
    public function getPublicationMaterial($request, $response, $args) {
        $id = $args['id']; 
        $user = new \model\userModel();
        $dataUser = $user->getPublicationMaterial($id);
        return $response->withJson($dataUser);;
    }
    public function getEmail($request, $response, $args) {
        $userId = $args['userId']; 
        $userEmail = new \model\userModel();
        $submission_id=$userEmail->getSubmissionId($userId);
        
        $dataUserEmail = $userEmail->getEmail($submission_id);
        return $response->withJson($dataUserEmail);
    }
    public function setPublication($request, $response, $args) {
        $data = $request->getParsedBody();
        $userFiles = new \model\userModel();
        $dataUserFiles = $userFiles->setPublication($data);
        //return $response->withJson($dataUserFiles);
    }
}


?>
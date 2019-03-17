<?php
    namespace model;
    use lib\Core;
    use PDO;

    class fileModel{
        protected $core;

        function __construct(){
            $this->core = Core::getInstance();
        }

        
        public function getFilesAsli($fileId){
            $sql = "select sf.submission_id,gs.genre_id,sf.file_id,revision,file_stage,date_uploaded,sf.original_file_name from users u JOIN submission_files sf ON u.user_id=sf.uploader_user_id JOIN submission_file_settings sfs ON sfs.file_id=sf.file_id JOIN genre_settings gs ON gs.genre_id=sf.genre_id where sf.file_id='$fileId' and file_stage=2 and sfs.setting_name='name' and gs.locale='en_US'";

            $stmt = $this->core->dbh->prepare($sql);
            
            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);		   	
            } else {
                $data = 0;
            }	
            return $data;
        }
        public function getFilesAsliPub($fileId){
            $sql = "select sf.submission_id,gs.genre_id,sf.file_id,revision,file_stage,date_uploaded,sf.original_file_name from users u JOIN submission_files sf ON u.user_id=sf.uploader_user_id JOIN submission_file_settings sfs ON sfs.file_id=sf.file_id JOIN genre_settings gs ON gs.genre_id=sf.genre_id where sf.file_id=$fileId and file_stage=10 and sfs.setting_name='name' and gs.locale='en_US'";

            $stmt = $this->core->dbh->prepare($sql);
            
            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);		   	
            } else {
                $data = 0;
            }	
            return $data;
        }
        public function getFilesAsliArsip($fileId){
            $sql = "select sf.submission_id,gs.genre_id,sf.file_id,revision,file_stage,date_uploaded,sf.original_file_name from users u JOIN submission_files sf ON u.user_id=sf.uploader_user_id JOIN submission_file_settings sfs ON sfs.file_id=sf.file_id JOIN genre_settings gs ON gs.genre_id=sf.genre_id where sf.file_id=$fileId and file_stage=11 and sfs.setting_name='name' and gs.locale='en_US'";

            $stmt = $this->core->dbh->prepare($sql);
            
            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);		   	
            } else {
                $data = 0;
            }	
            return $data;
        }
        public function setFileArsip($data){
            $submission_id=$data['submission_id'];
            $namaAsli=$data['namaAsli'];
            $size=$data['size'];
            $ekstensi=$data['ekstensi'];
            $editor_id=$data['editor_id'];
            $date= date('Y-m-d H:i:s');
            //print_r($size);
            $sql = "INSERT INTO submission_files(revision, submission_id, file_type, genre_id, file_size, original_file_name, file_stage, viewable, date_uploaded, date_modified, uploader_user_id) 
            VALUES (1,$submission_id,'$ekstensi',13,$size,'$namaAsli',11,0,'$date','$date',$editor_id);";
            
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);	   	 
            $sql = "SELECT file_id FROM submission_files WHERE submission_id=$submission_id and file_stage=11 ORDER BY file_id DESC;";
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
            $file_id = $stmt->fetchAll(PDO::FETCH_OBJ);
            $file_idEditor=$file_id[0]->file_id;

            $sql = "SELECT username FROM users WHERE user_id=$editor_id;";
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
            $username = $stmt->fetchAll(PDO::FETCH_OBJ);
            $username=$username[0]->username;

            $sql = "INSERT INTO submission_file_settings(file_id, locale, setting_name, setting_value, setting_type) 
            VALUES ($file_idEditor,'en_US','name','$username, $namaAsli','string');";
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
             //print_r($sql);
            return $file_id;
        }
        public function setFileGalley($data){
            $submission_id=$data['submission_id'];
            $namaAsli=$data['namaAsli'];
            $size=$data['size'];
            $ekstensi=$data['ekstensi'];
            $editor_id=$data['editor_id'];
            $date= date('Y-m-d H:i:s');
            //print_r($size);
            $sql = "SELECT max(assoc_id) as assoc_id FROM submission_files WHERE file_stage=10;";
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
            $assoc_id = $stmt->fetchAll(PDO::FETCH_OBJ);
            $assoc_id=$assoc_id[0]->assoc_id;
            $assoc_id++;
            //print_r($assoc_id);
            $sql = "INSERT INTO submission_files(revision, submission_id, file_type, genre_id, file_size, original_file_name, file_stage, viewable, date_uploaded, date_modified, uploader_user_id, assoc_type, assoc_id) 
            VALUES (1,$submission_id,'$ekstensi',13,$size,'$namaAsli',10,0,'$date','$date',$editor_id,521,$assoc_id);";
            
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);	   	 
            $sql = "SELECT file_id FROM submission_files WHERE submission_id=$submission_id and file_stage=10 ORDER BY file_id DESC;";
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
            $file_id = $stmt->fetchAll(PDO::FETCH_OBJ);
            $file_idEditor=$file_id[0]->file_id;

            $sql = "SELECT username FROM users WHERE user_id=$editor_id;";
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
            $username = $stmt->fetchAll(PDO::FETCH_OBJ);
            $username=$username[0]->username;

            $sql = "INSERT INTO submission_file_settings(file_id, locale, setting_name, setting_value, setting_type) 
            VALUES ($file_idEditor,'en_US','name','$username, $namaAsli','string');";
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();

            $sql = "INSERT INTO submission_galleys(locale, submission_id, label, file_id, seq, remote_url, is_approved) 
            VALUES ('en_US',$submission_id,'PDF', $file_idEditor,0,'',0);";
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
             print_r($sql);
            //print_r($file_id);
            return $file_id;
        }
    }
?>
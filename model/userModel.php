<?php
    namespace model;
    use lib\Core;
    use PDO;

    class userModel{
        protected $core;

        function __construct(){
            $this->core = Core::getInstance();
        }

        public function getUser(){
            $sql = "select username, email from users";

            $stmt = $this->core->dbh->prepare($sql);
            
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
 
            return $data;
        }

        public function getUserSubmitAntrian(){
            $sql = "select DISTINCT u.user_id, first_name,middle_name,last_name from users u left join submission_files sf on user_id=uploader_user_id join stage_assignments sa on sf.submission_id=sa.submission_id where sf.file_stage = 2 and sa.submission_id not in ( SELECT sa.submission_id FROM stage_assignments sa WHERE sa.user_group_id=20 )";

            $stmt = $this->core->dbh->prepare($sql);
            
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
 
            return $data;
        }

        public function getUserSubmit(){
            $sql = "select DISTINCT u.user_id, first_name,middle_name,last_name from users u left join submission_files sf on user_id=uploader_user_id join stage_assignments sa on sf.submission_id=sa.submission_id where sf.file_stage = 2 and sa.submission_id in ( SELECT sa.submission_id FROM stage_assignments sa WHERE sa.user_group_id=20 )";

            $stmt = $this->core->dbh->prepare($sql);
            
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
 
            return $data;
        }

        public function getSubmission(){
            $sql = "select ss.submission_id, first_name,middle_name,last_name,setting_name, setting_value from users u JOIN submission_files sf ON u.user_id=sf.uploader_user_id JOIN submission_settings ss ON ss.submission_id=sf.submission_id where file_stage=2";

            $stmt = $this->core->dbh->prepare($sql);
            
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
 
            return $data;
        }
        public function getUserFiles($userId){
            $sql = "select sf.uploader_user_id,sf.submission_id,sf.file_id, first_name,middle_name,last_name,revision,file_stage,gs.genre_id,gs.setting_value as jenis_berkas,date_uploaded,sfs.setting_value as nama_file from users u JOIN submission_files sf ON u.user_id=sf.uploader_user_id JOIN submission_file_settings sfs ON sfs.file_id=sf.file_id JOIN genre_settings gs ON gs.genre_id=sf.genre_id where u.user_id='$userId' and file_stage=2 and sfs.setting_name='name' and gs.locale='en_US'";

            $stmt = $this->core->dbh->prepare($sql);
            
            if ($stmt->execute()) {
                $data = $stmt->fetchAll(PDO::FETCH_OBJ);		   	
            } else {
                $data = 0;
            }	
            return $data;
        }
        
    }
?>
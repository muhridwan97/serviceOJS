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
            $sql = "select DISTINCT u.user_id, sf.submission_id, first_name,middle_name,last_name from users u left join submission_files sf on user_id=uploader_user_id join stage_assignments sa on sf.submission_id=sa.submission_id 
            join submissions s on s.submission_id=sf.submission_id where sf.file_stage = 2 and sa.submission_id not in ( SELECT sa.submission_id FROM stage_assignments sa WHERE sa.user_group_id=20 ) and sa.user_group_id=26 ORDER BY s.date_submitted ASC";

            $stmt = $this->core->dbh->prepare($sql);
            
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
 
            return $data;
        }
        public function getUserSubmitAntrianRevisi(){
            $sql = "select DISTINCT u.user_id, sf.submission_id, first_name,middle_name,last_name from users u left join submission_files sf on user_id=uploader_user_id join stage_assignments sa on sf.submission_id=sa.submission_id 
            join submissions s on s.submission_id=sf.submission_id where sf.file_stage = 2 and sa.submission_id not in ( SELECT sa.submission_id FROM stage_assignments sa WHERE sa.user_group_id=20 ) and sa.user_group_id=26 ORDER BY s.date_submitted ASC";
            //SELECT DISTINCT submission_id, uploader_user_id FROM `submission_files` GROUP by submission_id
            $stmt = $this->core->dbh->prepare($sql);
            
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
 
            return $data;
        }

        public function getUserSubmit(){
            $sql = "select DISTINCT u.user_id, first_name,middle_name,last_name,s.stage_id from users u left join submission_files sf on user_id=uploader_user_id join stage_assignments sa on sf.submission_id=sa.submission_id join submissions s on s.submission_id=sf.submission_id  where sf.file_stage = 2 and sa.submission_id in ( SELECT sa.submission_id FROM stage_assignments sa WHERE sa.user_group_id=20 ) and s.status=1";

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
        public function setSubmitIn($data){
            
            $sql = "insert into stage_assignments (submission_id, user_group_id, user_id, date_assigned, recommend_only)VALUES 
            ($data[submission_id],$data[user_group_id],$data[user_id],'$data[date_assigned]',0)";
            //print_r($sql);
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
        }
        public function setVerifikasi($data){
            
            $sql = "UPDATE submissions s, submission_files sf
            SET s.stage_id=3
            WHERE s.submission_id=sf.submission_id
             AND sf.uploader_user_id =$data[user_id];";
            //print_r($sql);
            //$stmt = $this->core->dbh->prepare($sql);
            //$stmt->execute();
            $sql2="select * from submission_files where submission_id=5 and file_stage=2";
            $stmt = $this->core->dbh->prepare($sql2);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach($data as $d){
                $sql3="INSERT INTO submission_files ( revision, source_file_id, source_revision, submission_id, file_type, genre_id,
                 file_size, original_file_name, file_stage, direct_sales_price, sales_type, viewable, date_uploaded, date_modified, uploader_user_id, 
                 assoc_type, assoc_id) VALUES ( $d->revision, $d->file_id, 1, $d->submission_id, '$d->file_type', $d->genre_id, $d->file_size, '$d->original_file_name', 4, NULL, NULL, 1,
                 '$d->date_uploaded', '$d->date_modified', $d->uploader_user_id, NULL, NULL);";
                 $stmt = $this->core->dbh->prepare($sql3);
                 $stmt->execute();
                //print_r($sql3);
            }
            //print_r($data[0]);
        }
        
    }
?>
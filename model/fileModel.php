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
        public function setFileArsip($data){
            $submission_id=$data['submission_id'];
            $namaAsli=$data['namaAsli'];
            $size=$data['size'];
            $ekstensi=$data['ekstensi'];
            print_r($size);
            $sql = "INSERT INTO submission_files(revision, submission_id, file_type, genre_id, file_size, original_file_name, file_stage, viewable, date_uploaded, date_modified, uploader_user_id, assoc_type, assoc_id) 
            VALUES (1,10,'application/pdf',13,12312,'5-Article Text-8-1-2-20181107.pdf',10,0,'2019-01-16 05:06:48','2019-01-16 05:06:48',3,521,4)";

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
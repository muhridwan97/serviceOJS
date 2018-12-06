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
        
    }
?>
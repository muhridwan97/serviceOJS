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
            $sql = "select u.user_id, sf.submission_id, first_name,middle_name,last_name, s.date_submitted from users u left join submission_files sf on user_id=uploader_user_id left join stage_assignments sa on sf.submission_id=sa.submission_id left join submissions s on s.submission_id=sf.submission_id where sf.file_stage = 2 and sa.submission_id not in ( SELECT sa.submission_id FROM stage_assignments sa WHERE sa.user_group_id=20) and s.submission_progress!=3 and s.status=1 GROUP BY (u.user_id) ORDER BY s.date_submitted ASC";

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

        public function getPage($issueId){
            $sql = "SELECT submission_id FROM published_submissions WHERE issue_id=$issueId ORDER by published_submission_id ASC";
            $stmt = $this->core->dbh->prepare($sql);            
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $submission_id=0;
            foreach($data as $d){
                $submission_id=$d->submission_id;
            }
            $sql2 = "SELECT pages FROM submissions WHERE submission_id=$submission_id";
            $stmt = $this->core->dbh->prepare($sql2);            
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
        public function getUserFiles($submission_id){
            $sql = "select sf.uploader_user_id,sf.submission_id,sf.file_id, first_name,middle_name,last_name,revision,file_stage,gs.genre_id,gs.setting_value as jenis_berkas,date_uploaded,sfs.setting_value as nama_file,ss.setting_value as judul,
            (SELECT setting_value FROM submission_settings WHERE submission_id=$submission_id and setting_name='subtitle') as subtitle, (SELECT setting_value FROM submission_settings WHERE submission_id=$submission_id and setting_name='abstract') as abstract from users u JOIN submission_files sf ON u.user_id=sf.uploader_user_id JOIN submission_file_settings sfs ON sfs.file_id=sf.file_id JOIN genre_settings gs ON gs.genre_id=sf.genre_id join submission_settings ss on ss.submission_id=sf.submission_id where sf.submission_id=$submission_id and file_stage=2 and sfs.setting_name='name' and gs.locale='en_US' and ss.setting_name='cleanTitle'";

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
            $submission_id=0;
            $user_id=$data['user_id'];
            $editor_user_id= $data['editor_user_id'];
            $date_assigned=$data['date_assigned'];
            $sql = "UPDATE submissions s, submission_files sf
            SET s.stage_id=3
            WHERE s.submission_id=sf.submission_id
             AND sf.uploader_user_id =$data[user_id];";
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
            $sql2="select * from submission_files where uploader_user_id=$data[user_id] and file_stage=2";
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
                if($submission_id<$d->submission_id){
                    $submission_id=$d->submission_id;
                }
            }
            
            $sql4="INSERT INTO review_rounds( submission_id, stage_id, round, review_revision, status) VALUES ($submission_id,3,1,NULL,6);
            INSERT INTO edit_decisions(submission_id, review_round_id, stage_id, round, editor_id, decision, date_decided) 
            VALUES ($submission_id,0,1,0,$editor_user_id,8,'$date_assigned');";
            $stmt = $this->core->dbh->prepare($sql4);
            $stmt->execute();

            $sql5="SELECT review_round_id FROM review_rounds where submission_id=$submission_id ";
            $stmt = $this->core->dbh->prepare($sql5);
            $stmt->execute();
            $review_round_id = $stmt->fetchAll(PDO::FETCH_OBJ);
            $review_round_id = $review_round_id[0]->review_round_id;

            $sql6= "select file_id from submission_files where uploader_user_id=$user_id and file_stage=4";
            $stmt = $this->core->dbh->prepare($sql6);
            $stmt->execute();
            $file_id = $stmt->fetchAll(PDO::FETCH_OBJ);
            foreach($file_id as $f){
                $sql7="INSERT INTO review_round_files(submission_id, review_round_id, stage_id, file_id, revision) VALUES ($submission_id,
                $review_round_id,3,$f->file_id,1);";
                $stmt = $this->core->dbh->prepare($sql7);
                $stmt->execute();
                //print_r($sql7 );
            }
            
        }
        public function getSubmissionId($userId){
            $submission_id=0;
            $sql = "select * from submission_files where uploader_user_id=$userId and file_stage=2";

            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            foreach($data as $d){
                if($submission_id<$d->submission_id){
                    $submission_id=$d->submission_id;
                }
            }
            return $submission_id;
        }
        public function getMetadata($submission_id){
            $sql = "SELECT submission_id,first_name,middle_name,last_name,email,seq FROM `authors` WHERE submission_id=$submission_id";

            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            return $data;
        }
        public function getKeyword($submission_id){
            $sql = "SELECT controlled_vocab_id FROM controlled_vocabs where assoc_id=$submission_id AND symbolic='submissionKeyword';";
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $id=0;
            foreach($data as $d){
                    $id=$d->controlled_vocab_id;
            }
            $sql2 = "SELECT es.setting_value FROM controlled_vocab_entry_settings es JOIN controlled_vocab_entries ve 
            on es.controlled_vocab_entry_id=ve.controlled_vocab_entry_id WHERE ve.controlled_vocab_id = $id;";
            $stmt = $this->core->dbh->prepare($sql2);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $keyword=array();
            $i=0;
            foreach($data as $d){
                    $keyword[$i]=$d->setting_value;
                    $i++;
            }
            $kalimat = implode(",",$keyword);
            $d->setting_value=$kalimat;
            return $data;
        }
        public function setMetadata($data){
            $submission_id=$data['submission_id'];
            $judul= $data['judul'];
            $subtitle=$data['subtitle'];
            $abstract="<p>".$data['abstract']."</p>";
            $keyword=$data['keyword'];
            $keyword=explode(",",$keyword);
            $sql = "UPDATE submission_settings SET setting_value='$judul'  WHERE submission_id=$submission_id and setting_name='Title';
            UPDATE submission_settings SET setting_value='$judul'  WHERE submission_id=$submission_id and setting_name='cleanTitle';
            UPDATE submission_settings SET setting_value='$subtitle'  WHERE submission_id=$submission_id and setting_name='subtitle';
            UPDATE submission_settings SET setting_value='$abstract'  WHERE submission_id=$submission_id and setting_name='abstract';";
            //print_r($sql);
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
            $sql2 = "SELECT controlled_vocab_id FROM controlled_vocabs where assoc_id=$submission_id AND symbolic='submissionKeyword';";
            //print_r($sql);
            $stmt = $this->core->dbh->prepare($sql2);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            $id=0;
            foreach($data as $d){
                    $id=$d->controlled_vocab_id;
            }
            //print_r($keyword);//
            $sql3 = "DELETE controlled_vocab_entries, controlled_vocab_entry_settings FROM controlled_vocab_entries
            INNER JOIN controlled_vocab_entry_settings ON controlled_vocab_entries.controlled_vocab_entry_id = controlled_vocab_entry_settings.controlled_vocab_entry_id 
            WHERE
            controlled_vocab_entries.controlled_vocab_id= $id;
            ALTER TABLE controlled_vocab_entries AUTO_INCREMENT=0;";
            $stmt = $this->core->dbh->prepare($sql3);
            $stmt->execute();
            for($i=1;$i<=count($keyword);$i++){
                $sql4 = "INSERT INTO controlled_vocab_entries (controlled_vocab_id, seq) VALUES ($id,$i);";
                $stmt = $this->core->dbh->prepare($sql4);
                $stmt->execute();
             }
             $sql5 = "SELECT controlled_vocab_entry_id FROM controlled_vocab_entries WHERE controlled_vocab_id=$id;";
             $stmt = $this->core->dbh->prepare($sql5);
             $stmt->execute();
             $data = $stmt->fetchAll(PDO::FETCH_OBJ);
             $i=0;
             foreach($data as $d){
                $sql5 = "INSERT INTO controlled_vocab_entry_settings(controlled_vocab_entry_id, locale, setting_name, setting_value, setting_type) 
                VALUES ($d->controlled_vocab_entry_id,'en_US','submissionKeyword','$keyword[$i]','string');";
                $stmt = $this->core->dbh->prepare($sql5);
                $stmt->execute();
                $i++;
            }
            
        }
        public function decline($data){
            $submission_id=$data['user_id'];
            $submission_id=$this->getSubmissionId($submission_id);
            $sql = "UPDATE submissions SET status = '4' WHERE submission_id = '$submission_id';";
            //print_r($sql);
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
        }
        public function tambahPenulis($data){
            $submission_id=$data['submission_id'];
            $first_name=$data['first_name'];
            $last_name= $data['last_name'];
            $middle_name=$data['middle_name'];
            $email=$data['email'];
            $affiliation=$data['affiliation'];
            $sql0="SELECT seq FROM authors where submission_id=$submission_id ORDER BY seq DESC;";
            $stmt = $this->core->dbh->prepare($sql0);
            $stmt->execute();
            $seq = $stmt->fetchAll(PDO::FETCH_OBJ);
            $seq = $seq[0]->seq;
            $seq++;
            $sql = "INSERT INTO authors( submission_id, primary_contact, seq, first_name, middle_name, last_name, suffix, country, email,url, user_group_id, include_in_browse) 
            VALUES ($submission_id,0,$seq,'$first_name','$middle_name','$last_name','','ID','$email','',31,1);";
            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
            $sql2="SELECT author_id FROM authors where submission_id=$submission_id and seq=$seq;";
            $stmt = $this->core->dbh->prepare($sql2);
            $stmt->execute();
            $author_id = $stmt->fetchAll(PDO::FETCH_OBJ);
            $author_id = $author_id[0]->author_id;
            $sql3="INSERT INTO author_settings(author_id, locale, setting_name, setting_value, setting_type) VALUES ($author_id,'en_US','affiliation','$affiliation','string');";
            $stmt = $this->core->dbh->prepare($sql3);
            $stmt->execute();
        }
        public function getDaftarPublication(){
            $sql = "select DISTINCT u.user_id, first_name,middle_name,last_name,s.stage_id from users u left join submission_files sf on user_id=uploader_user_id 
            join stage_assignments sa on sf.submission_id=sa.submission_id join submissions s on s.submission_id=sf.submission_id  where sf.file_stage = 2 and sa.submission_id in ( SELECT sa.submission_id FROM stage_assignments sa WHERE sa.user_group_id=20 ) and s.status=3";

            $stmt = $this->core->dbh->prepare($sql);
            
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
 
            return $data;
        }
        
        public function getPublicationIssue(){
            $sql = "SELECT i.issue_id,i.volume,i.number,i.year,i.published,i.current,iss.setting_value FROM issues i join issue_settings iss on i.issue_id=iss.issue_id WHERE iss.setting_name='title'";

            $stmt = $this->core->dbh->prepare($sql);
            
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
 
            return $data;
        }
        public function getPublicationMaterial($id){
            $sql = "SELECT i.issue_id,i.volume,i.number,i.year,i.published,i.current,iss.setting_value FROM issues i join issue_settings iss on i.issue_id=iss.issue_id WHERE iss.setting_name='title'";

            $stmt = $this->core->dbh->prepare($sql);
            
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
 
            return $data;
        }
        public function getEmail($submission_id){
            $sql = "SELECT submission_id,primary_contact,email,seq FROM `authors` WHERE submission_id=$submission_id";

            $stmt = $this->core->dbh->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            return $data;
        }
    }
?>
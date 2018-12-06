<?php

namespace lib;

use lib\Config;
use PDO;

class Core {
    public $dbh; 
    private static $instance;

    private function __construct() {
        //set artribu database server dari file Config
        $dsn = 'mysql:host=' . Config::read('db.host') .
               ';dbname='    . Config::read('db.basename') .
               ';port='      . Config::read('db.port') .
               ';connect_timeout=15';
                       
        $user = Config::read('db.user');
                        
        $password = Config::read('db.password');

        //membuat koneksi ke database    
        $this->dbh = new PDO($dsn, $user, $password);
    }

    //Singgleton 
    public static function getInstance() {
        if (!isset(self::$instance))
        {
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }
}
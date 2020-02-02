<?php
namespace App\Utils\Databases;
class Migration
{
    private static $instance;

    public static function getInstance()
    {
        if(self::$instance === null){
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function execute(){
        $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
        $db = DB::select($query, [env('DB_DATABASE')]);

        if (empty($db)) {
            echo 'No db exist of that name!';
        } else {
            echo 'db already exists!';
        }
    }
}
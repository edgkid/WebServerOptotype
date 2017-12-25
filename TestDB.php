<?php

require_once 'PgDataBase.php';

class TestDB extends PgDataBase{
    
    function __construct() {
        parent::__construct();
    }
    
    public function save (array $obj){

        foreach ($obj as $value){
         
            echo $value->idPatient." ".$value->idOptotype." ".$value->testCode." ".$value->eye."\n";
            
        }
    }
}
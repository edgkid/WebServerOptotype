<?php

require_once 'PgDataBase.php';

Class UpdateImageAction{
    
    public $directory="";
    public $path = ""; 
    public $count = 0;
    
    function __construct() {
        parent::__construct(); 
    }
    
    public function updateImageKids (){
        
        $this->directory="kids";
        $files = opendir($this->directory);
        
        while ($file = readdir($files)){
    
            if ($count >1){
                $this->path = $this->directory."/".$file;
                $name = explode(".", $file);
                $bytesFile = file_get_contents($this->path);
                $bytesFile = pg_escape_bytea($bytesArchivo);
                echo $this->path;
                $query = "UPDATE TABLE patient SET photo = ".$bytesFile.
                         "WHERE Concat(firstname,middlename,lastname,maidenname) = '".
                          $name[0]."'";
                $result = pg_query($query);
                
            }
            $count ++;
        }
        
    } 
    
    public function UpdateImageOptotype(){
        
        $this->directory="optotypes";
        $files = opendir($this->directory);
        
        while ($file = readdir($files)){
    
            if ($count >1){
                $this->path = $this->directory."/".$file;
                $bytesArchivo = file_get_contents($this->path);
                $bytesArchivo = pg_escape_bytea($bytesArchivo);
                echo $this->path;
                /*$query = "INSERT INTO ImageB (imagename, imagepicture)VALUES ('".$archivo."','".$bytesArchivo."')";
                $result = pg_query($query);
                if ($result)
                    echo "exito al guardar : ". $ruta."</br>";
                else
                    echo "fallo al guardar :". $ruta."</br>";*/
            }
            $count ++;
        }
        
    }
    
    
    
}


<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'PgDataBase.php';
/**
 * Description of OptotypeDB
 *
 * @author Edgar
 */
class OptotypeDB extends PgDataBase{
    
    function __construct() {
        parent::__construct();
    }
    
    /**
     * obtiene todos los registros de la tabla "optotype"
     * @return Array array con los registros obtenidos de la base de datos
     */
    public function getOptotypes (){
        
        $data = array();
        $query = "SELECT IdOptotype, OptotypeCode, OptotypeName, Image "
                . "FROM Optotype ";
        $users = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
       
        while ($line = pg_fetch_array($users, null, PGSQL_ASSOC)) {
            $data []= array('idOptotype'=>$line['idoptotype'],'optotypeName'=>$line['optotypename'],'optotypeCode'=>$line['optotypecode'],'image'=> base64_encode(pg_unescape_bytea($line['image'])));
         }   
            
     
        return $data; 
    }
    
    public function getOptotypesByYears($year){
        
        $limit = "16";
        $data = array();
        
        $query = "  SELECT IdOptotype, OptotypeCode, OptotypeName, Image"
                ."  FROM Optotype"
                ."  WHERE optotypeYear = ".$year
                ."  ORDER BY random() LIMIT ".$limit;
        
        $optotypes = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        
        while ($line = pg_fetch_array($optotypes, null, PGSQL_ASSOC)) {
            $data []= array('idOptotype'=>$line['idoptotype'],'optotypeName'=>$line['optotypename'],'optotypeCode'=>$line['optotypecode'],'image'=> base64_encode(pg_unescape_bytea($line['image'])));
         }
        
        return $data;
    }
    
    public function putOptotypesImage (){
        
        $directory="optotypesImage";
        $path = ""; 
        $count = 0;
        
        
        $files = opendir($directory);
        
        while ($file = readdir($files)){
    
            if ($count >1){
                $path = $directory."/".$file;
                $name = explode(".", $file);
                $bytesFile = file_get_contents($path);
                $bytesFile = pg_escape_bytea($bytesFile);
                //echo $path."</br>";
                $query = " UPDATE optotype SET image = '".$bytesFile.
                         "' WHERE optotypecode = '".
                          $name[0]."'";
                $result = pg_query($query);
                if($result)
                    echo 'exito al actualizar'.$name[0]."</br>";
                else 
                    echo 'fallo al actualizar'.$name[0]."</br>";
            }
            $count ++;
        }
        
    } 
    
}

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
        $query = "SELECT IdOptotype, OptotypeCode, OptotypeName "
                . "FROM Optotype";
        $users = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
       
        while ($line = pg_fetch_array($users, null, PGSQL_ASSOC)) {
            $data[] = $line;
        }
     
        return $data; 
    }
    
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'PgDataBase.php';
/**
 * Description of UserDB
 *
 * @author Edgar
 */
class UserDB extends PgDataBase{
   
    function __construct() {
        parent::__construct();
    }
    
     /**
     * obtiene todos los registros de la tabla "people"
     * @return Array array con los registros obtenidos de la base de datos
     */
    public function getUsers(){  
        $data = array();
        $query = "SELECT IdUser, UserName, UserPassword FROM User_System;";
        $users = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
       
        while ($line = pg_fetch_array($users, null, PGSQL_ASSOC)) {
            $data[] = $line;
        }
     
        return $data; 
    }

}

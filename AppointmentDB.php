<?php

require_once 'PgDataBase.php';

class AppointmentDB extends PgDataBase{
    
    function __construct() {
        parent::__construct();
    }
    
    function delete(array $obj){
        
        $value = false;
        $query = "DELETE FROM Medical_Appointment WHERE fk_idPatient = ".$obj[0]->idPatient;
        $query = $query." AND status = '".$obj[0]->status."'";
        
        $result = pg_query($query);
        
        if ($result)
            $value = true;
        
        return $value;
    }
    
}

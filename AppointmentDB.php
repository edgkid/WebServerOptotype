<?php

require_once 'PgDataBase.php';

class AppointmentDB extends PgDataBase{
    
    function __construct() {
        parent::__construct();
    }
    
    function actionAppointment (array $obj){
        
        $value = false;
        
        switch ($obj[0]->action){
            
            case '0':
                echo 'nueva cita';
                break;
            
            case '1':
                $this->update($obj);
                break;
            
            case '2':
                $value = $this->delete($obj);
                break;
            
        }
        
        return $value;
        
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
    
    function update (array $obj){
        
        $value = false;
        
        $query = "UPDATE Medical_Appointment SET appointmentdate = '".$obj[0]->appointmentDate."'";
        $query = $query." WHERE fk_idPatient = ".$obj[0]->idPatient;
        $query = $query." AND status = '".$obj[0]->status."'";
        
        $result = pg_query($query);
        
        if ($result)
            $value = true;
        
        return $value;
        
    }
    
}

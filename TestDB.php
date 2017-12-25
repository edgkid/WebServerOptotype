<?php

require_once 'PgDataBase.php';

class TestDB extends PgDataBase{
    
    function __construct() {
        parent::__construct();
    }
    
    public function save (array $obj){
        
        $query="";
        $idPatient = "";

        foreach ($obj as $value){
         
            $idPatient = $this->getFkAppointmentForTest($value->idPatient);
            $query = "INSERT INTO TEST (testCode,eye,fk_idappointment) VALUES (";
            $query = $query."'".$value->testCode."','".$value->eye."',".$idPatient.")";
            
            $result = pg_query($query);
                if($result)
                    echo 'exito al guardar Test'.$value->testCode."\n";
                else 
                    echo 'fallo al guardar Test'.$value->testCode."\n";
        }
    }
    
    private function getFkAppointmentForTest($idPatient){
           
        $value = 0;
        
        $query = " SELECT idAppointment ".
                 " FROM Medical_Appointment ma ".
                 " WHERE ma.fk_idPatient = ".$idPatient.
                 "      AND to_char(ma.appointmentdate, 'dd/mm/yyyy') = ".
                 "         (SELECT COALESCE((EXTRACT(day FROM  current_timestamp)) ". 
                 "          || '/'|| (EXTRACT(month FROM  current_timestamp))|| '/'|| ". 
                 "          (EXTRACT(year FROM  current_timestamp)))".
                 "           FROM current_timestamp)";
        
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
       
        if ($row = pg_fetch_row($result)) {
           
            $value = $row[0]; 
        }
            
        return $value;
    }
        
}
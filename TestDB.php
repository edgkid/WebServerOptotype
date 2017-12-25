<?php

require_once 'PgDataBase.php';

class TestDB extends PgDataBase{
    
    function __construct() {
        parent::__construct();
    }
    
    public function save (array $obj){
        
        $query="";
        $idPatient = "";
        $idTest = "";
        
        foreach ($obj as $value){
         
            $idPatient = $this->getFkAppointmentForTest($value->idPatient);
            $query = "INSERT INTO TEST (testCode,eye,fk_idappointment) VALUES (";
            $query = $query."'".$value->testCode."','".$value->eye."',".$idPatient.")";
            
            $result = pg_query($query);
                if($result){
                    
                    $this->sendCommit();
                    $idTest = $this->getIdTestOptotype();
                    echo 'exito al guardar Test '.$value->testCode." ".$idTest."\n";
                    $this->saveOptotpeTest($value->idOptotype, $idTest);
                }
                else 
                    echo 'fallo al guardar Test'.$value->testCode."\n";
        }
    }
    
    private function saveOptotpeTest($idOptotype, $idTest){
        
        $query = "INSERT INTO OPTOTYPE_TEST (fk_idoptotype, fk_idtest) VALUES (";
        $query = $query.$idOptotype.",".$idTest.")";
        
        $result = pg_query($query);
                if($result)
                    echo 'exito al guardar optotipo'.$idOptotype."\n";
                else 
                    echo 'fallo al guardar optotipo'.$idOptotype."\n";
       
    }
    
    private function getIdTestOptotype (){
        
        $value= "";
        $query="SELECT MAX(idTest) FROM TEST";
        
        $result = pg_query($query);
        
        if ($row = pg_fetch_row($result)) {
           
            $value = $row[0]; 
        }
        
        if ($value == null || $value == 0)
            $value = 1;
   
        return $value;
    }
    
    private function sendCommit (){
        
        $query = "COMMIT; ";
        $result = pg_query($query);
        if($result)
            echo 'commit\n';
        
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
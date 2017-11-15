<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'PgDataBase.php';
/**
 * Description of PatientDB
 *
 * @author Edgar
 */
class PatientDB extends PgDataBase {
    
    function __construct() {
        parent::__construct();
    }
    
    /**
     * This function find the Patients for today
     * @return type
     */
    public function getPatientsForToday (){
        $data = array();
        $query = "  SELECT pat.idPatient as idPatient, pat.firstName as firstName, ".
               "           pat.middleName as middleName, pat.lastName as lastName,".
               "           pat.maidenName as maidenName ,".
               "    (extract(year from  current_timestamp) - extract(year from pat.birthDay)) as yearsOld,".
               "    pat.fk_idUser as fkUSer".
               "  FROM Patient pat, Medical_Appointment mea".
               "  WHERE pat.idPatient = mea.fk_idPatient".
               "        AND to_char(mea.appointmentdate, 'dd/mm/yyyy')= to_char((Current_Timestamp :: date), 'dd/mm/yyyy')";
        
        $patient = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
       
        while ($line = pg_fetch_array($patient, null, PGSQL_ASSOC)) {
            $data[] = $line;
        }
        
        return $data;
    }
    
    
    public function putPatientsImage (){
        
        $directory="kids";
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
                $query = " UPDATE patient SET photo = '".$bytesFile.
                         "' WHERE Concat(firstname,middlename,lastname,maidenname) = '".
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

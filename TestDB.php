<?php

require_once 'PgDataBase.php';
require_once 'OptometricTest.php';


class TestDB extends PgDataBase{
    
    function __construct() {
        parent::__construct();
    }
    
    function processDataMedicalTest(array $obj){
        
        switch ($obj[0]->action){
            
            case '0':
                $this->save($obj);
                break;
            
            case '1':
                echo "Consultar datos de interacción";
                break;
            
            case '2':
                echo "Actualizar datos de interacción";
                break;
            
            case '3':
                echo 'eliminar datos de interacion';
                break;
            
            case '4':
                //$this->optometricTest($obj);
                $this->newTest($obj, "L");
                break;
        }
        
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
        
        $query = "INSERT INTO OPTOTYPE_TEST (status, fk_idoptotype, fk_idtest) VALUES (";
        $query = $query."'N',".$idOptotype.",".$idTest.")";
        
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
                 "      AND ma.appointmentdate = to_date(".
                 "         (SELECT COALESCE((EXTRACT(day FROM  current_timestamp))".  
                 "          || '/'|| (EXTRACT(month FROM  current_timestamp))|| '/'||". 
                 "          (EXTRACT(year FROM  current_timestamp)))".
                 "           FROM current_timestamp), 'dd/mm/yyyy')";
        
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
       
        if ($row = pg_fetch_row($result)) {
           
            $value = $row[0]; 
        }
            
        return $value;
    }
    
    private function saveNewTest($testCode){
        
        $directory="OptometricCard";
        $path = ""; 
        $count = 0;
        
        $files = opendir($directory);
        
        while ($file = readdir($files)){
    
            if ($count >1){
                $path = $directory."/".$file;
                $name = explode(".", $file);
                $bytesFile = file_get_contents($path);
                $bytesFile = pg_escape_bytea($bytesFile);
                if ($testCode == $name[0])
                {
                    $query = "INSERT INTO SUMMARY_TEST (summarycode, imagetest) VALUES ("."'";
                    $query = $query.$testCode."','".$bytesFile."'); commit;";
                    
                    $result = pg_query($query);
                
                    if($result)
                        echo 'exito al actualizar'.$testCode."</br>";
                    else 
                        echo 'fallo al actualizar'.$testCode."</br>";
                    
                    break;
                }
            }
            $count ++;
        }
        
    }
    
    private function saveOptotypeByNewTest($testCode){
        
       $query = "";
       $optometricTest = $this->getNewTest($testCode);
       $optotypes = $this->getOptotypes($testCode);
       
       foreach ($optotypes as $value){

            $query = $query." INSERT INTO TEST_BY_SUMMARY (fk_idSummary, fk_idOptotypeTest) VALUES (";
            $query = $query.$optometricTest.",".$value."); ";
       }
       
       $query = $query ." commit;";
       
       $result = pg_query($query);
                
        if($result)
            echo 'exito al guardar '.$testCode."</br>";
        else 
            echo 'fallo al guardar '.$testCode."</br>";
       
        
    }
    
    private function getNewTest($testCode){
        
        $value = "";
        $query =    "   SELECT idSummary".
                    "   FROM Summary_Test".
                    "   WHERE summaryCode = '".$testCode."'";
        
        $result = pg_query($query) or die('La consulta en getNewTest fallo: ' . pg_last_error());
       
        if ($row = pg_fetch_row($result)) {
           
            $value = $row[0]; 
        }
            
        return $value;
        
    }
    
    private function getOptotypes ($testCode){
        
        $data = array();
        $count = 0;
        $query =    " SELECT idOptotypetest".
                    " FROM Test, optotype_test, optotype". 
                    " WHERE testCode = '".$testCode."'".
                    "       AND fk_idTest = idTest".
                    "       AND fk_idOptotype = idOptotype";
        
        $result = pg_query($query) or die('La consulta en getOptotypes fallo: ' . pg_last_error());

        while ($row = pg_fetch_row($result)) {
           
            $data[$count] = $row[0];
            $count ++;
        }
            
        return $data;
    }
    
    private function newTest($objArr, $eye){
        
        $pixelArray = array();
        
        $optometricCard = new OptometricTest('prueba');
        $optometricCard->setDistance($objArr[0]->distance);
        $optometricCard->setTestCode($eye);
        $pixelArray = $optometricCard->findInteractionData($objArr[0]->patientId);
        $optometricCard->resizeImage($optometricCard->getHigh(),$optometricCard->getWidth(),$optometricCard->getTestCode());
        $optometricCard->newOptometricCard($optometricCard->getInteraction(),$optometricCard->getTestCode(), $optometricCard->getWidth(), $optometricCard->getHigh(), $pixelArray);
        
        $this->saveNewTest($optometricCard->getTestCode());
        $this->saveOptotypeByNewTest($optometricCard->getTestCode());
    }
        
}
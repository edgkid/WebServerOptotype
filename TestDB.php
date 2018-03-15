<?php

require_once 'PgDataBase.php';
require_once 'TestParameter.php';
require_once 'ImageSize.php';
require_once 'PrintSize.php';
require_once 'Canvas.php';


class TestDB extends PgDataBase{
    
    function __construct() {
        parent::__construct();
    }
    
    function processDataMedicalTest(array $obj){
        
        $response = "";
        
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
                //$response = $this->newTest($obj, "L");
                $response = $this->newTest($obj);
                break;
        }
        
        return $response;
        
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
                
                    /*if($result)
                        echo 'exito al actualizar'.$testCode."</br>";
                    else 
                        echo 'fallo al actualizar'.$testCode."</br>";*/
                    
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
                
        /*if($result)
            echo 'exito al guardar '.$testCode."</br>";
        else 
            echo 'fallo al guardar '.$testCode."</br>";*/
       
        
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
    
    function getSummaryTestByCode($testCode){
        
        $data = array();
        
        $query ="   SELECT idsummary, summaryCode, imagetest".
                "   FROM summary_test".
                "   WHERE summaryCode = '".$testCode."'";
        
        $patient = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
       
        while ($line = pg_fetch_array($patient, null, PGSQL_ASSOC)) {
            $data []= array('idSummary'=>$line['idsummary'],'summaryCode'=>$line['summarycode'],'imageTest'=> base64_encode(pg_unescape_bytea($line['imagetest'])));
        }
        
        return $data;
      
    }
    
    function newTest($objArr){
        
        $response = "";
        
        $nameTest = "";
        $avMin = 0;
        $avGrade = 0;
        $avRadians = 0;
        $e = 0;
        $h = 0;
        $position = 0;
        $nextElement = 0;
        $hPixel = 0;
        $x = 0;
        $y = 0;
        $xPixel = 0;
        $yPixel = 0;

        $avList = array();
        $avPixels = array();
        $avPrints = array();

        $testParameter = new TestParameter($objArr[0]->distance);
        $PrintSize = new PrintSize();
        $ImageSize = new ImageSize($testParameter->getPppCalculos());
        $avList = $testParameter->getAvList();
        
        $sizeArray = count($avList);

        //// defino el tamañod elos elementos para cada renglon
        while ($position < $sizeArray ){
            
            $avMin = $PrintSize->getAvMinute($avList[$position]);
            $avGrade = $PrintSize->getAvGrade($avMin);
            $avRadians = $PrintSize ->getAvRadian($avGrade);
            $e = $PrintSize->getSizeMinDetailmm($avRadians, $testParameter->getDistance());
            $h = $PrintSize->getSizeElement($e);
            $h = $PrintSize->getSizeElementCm($h);
            $hPixel = $ImageSize->getSizeInPixel($h);
            
            $avPrints[$nextElement] = $h;
            $avPixels[$nextElement] = $hPixel;

            $position ++;
            $nextElement++;
        }
        
        ///// Defino alto y ancho del lienzo que sera la carta
        $y = $PrintSize->getCanvasHight($avPrints);
        $x = $PrintSize->getCanvasWith($avPrints);
        $yPixel = $ImageSize->getSizeInPixel($y);
        $xPixel = $ImageSize->getSizeInPixel($x);
        
        ///// Defino alto y ancho del lienzo que sera la carta
        $y = $PrintSize->getCanvasHight($avPrints);
        $x = $PrintSize->getCanvasWith($avPrints);
        $yPixel = $ImageSize->getSizeInPixel($y);
        
        // tambien debo buscar el Test Code correspondiente
        $nameTest = $this->getNameNewTest($objArr[0]->patientId);
        
        ///// esto debe ser consultado
        $avElements = $this->getElementsInteraction($objArr[0]->patientId, $nameTest);
    
        /// voy a crear el lienzo base para la carta
        $canvas = new Canvas($xPixel, $yPixel, $avPixels, $avElements, $nameTest);
        $canvas->newCanvasImage();
        $canvas->canvasToOptometricCard();
        
        // si es necesario guardamos el nuevo text creado
        if ($this->findingSumaryTest($nameTest) == 0 ){
            $this->saveNewTest($nameTest);
            $this->saveOptotypeByNewTest($nameTest);
        }
        
        // retorno la imagen en un JSON
        $response = $this->getSummaryTestByCode($nameTest);
        
        return $response;
    }
    
    private function getNameNewTest ($patientId){
        
        $date = getdate();
        $value = "";
        $today = "'".$date['mday']."/".$date['mon']."/".$date['year']."'";

        $query = "  SELECT DISTINCT(te.testCode) ". 
                    " FROM Patient pa, Medical_appointment ma, Test te, Optotype_Test ot, Optotype op ".
                    " WHERE pa.idPatient = ma.fk_idPatient ".
                        " AND ma.idAppointment = te.fk_idAppointment ".
                        " AND te.idTest = ot.fk_idTest ".
                        " AND ot.fk_idOptotype = op.idOptotype ".
                        " AND pa.idPatient = ".$patientId.
                        " AND ma.appointmentdate = to_date(".$today.",'dd/mm/yyyy')";  
        
        $db = new PgDataBase();
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
       
        if ($row = pg_fetch_row($result)) {
            $value = $row[0];
        } 
        
        return $value;
    }
    
    private function getElementsInteraction ($patientId, $testCode){
        
        $date = getdate();
        $position = 0;
        $array = array();
        $today = "'".$date['mday']."/".$date['mon']."/".$date['year']."'";
        
        $query = "  SELECT op.optotypeCode". 
                    " FROM Patient pa, Medical_appointment ma, Test te, Optotype_Test ot, Optotype op ".
                    " WHERE pa.idPatient = ma.fk_idPatient ".
                        " AND ma.idAppointment = te.fk_idAppointment ".
                        " AND te.idTest = ot.fk_idTest ".
                        " AND ot.fk_idOptotype = op.idOptotype ".
                        " AND pa.idPatient = ".$patientId.
                        " AND ma.appointmentdate = to_date(".$today.",'dd/mm/yyyy')".
                        " AND te.testCode LIKE '%".$testCode."%'";  
        
        $db = new PgDataBase();
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        
        while ($row = pg_fetch_row($result)) {
            $array[$position] = $row [0];
            $position ++;
         }   
         
         return $array;
         
    }
    
    
    private function findingSumaryTest($testCode){
        
        $value = 0;
        
        $query = "  SELECT count(su.idsummary)as value".
                 "  FROM SUMMARY_TEST su".
                 "  WHERE su.summaryCode = '".$testCode."'";  
        
        $db = new PgDataBase();
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        
        if($line = pg_fetch_assoc($result)) {
            
            $value = $line['value'];
            
        }

        return $value;
        
        
    }
        
}
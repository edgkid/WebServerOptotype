<?php

require_once 'PgDataBase.php';

require_once 'ParameterForCard.php';
require_once 'PrintSize.php';
require_once 'ImageSize.php';
require_once 'Canvas.php';
require_once 'CardConstructor.php';


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
    
    private function newTest($objArr){
        
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
        
        $parameters = new ParameterForCard($objArr[0]->distance);
        $printSize = new PrintSize();
        $imageSize = new ImageSize($parameters->getCalculatePPP());
        $avList = $parameters->getAvList();

        $sizeArray = count($avList);
        
        //// defino el tamañod elos elementos para cada renglon
        while ($position <= $sizeArray ){
            
            if($position == $sizeArray){
              $avMin = $printSize->getAvMinute($avList[$position - 1]);  
            }else{
               $avMin = $printSize->getAvMinute($avList[$position]); 
            }
            
            $avRadians = $printSize->getAvRadian($avGrade);
            $e = $printSize->getSizeMinDetailmm($avRadians, $parameters->getDistance());
            $avGrade = $printSize->getAvGrade($avMin);
            $h = $printSize->getSizeElement($e);
            $h = $printSize->getSizeElementCm($h);
            $hPixel = $imageSize->getSizeInPixel($h);

            $avPrints[$nextElement] = $h;
            $avPixels[$nextElement] = $hPixel;

            $position ++;
            $nextElement++;
         }  
         
         ///// Defino alto y ancho del lienzo que sera la carta
        $y = $printSize->getCanvasHight($avPrints);
        $x = $printSize->getCanvasWith($avPrints);
        $yPixel = $imageSize->getSizeInPixel($y);
        $xPixel = $imageSize->getSizeInPixel($x) + 100;
        
        ///// voy a crear el lienzo base para la carta
        //$canvas = new Canvas($xPixel, $yPixel, $avPixels);
        $canvas = new Canvas($xPixel, $yPixel, $avPixels, "prueba");
        $canvas->newCanvasImage();
        $canvas->rowsForCanvas();
        $cardConstructor = new CardConstructor($avPixels, $objArr[0]->distance, "prueba");
        $cardConstructor->fillCanvasRows();
        $cardConstructor->fillOptometricCard();

        //$response = $this->getSummaryTestByCode($optometricCard->getTestCode());
        
        $response = "hola";
        return $response;
    }
        
}
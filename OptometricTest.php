<?php

require_once 'PgDataBase.php';
require_once 'NewImage.php';
require_once 'sizeImage.php';

class OptometricTest extends NewImage {
    
    private $testCode = "";
    private $patient;
    private $interaction = array();
    private $width = 0;
    private $high = 0;
    
    function __construct($testCode) {
        parent::__construct();
    }
    
    function getTestCode() {
        return $this->testCode;
    }

    function setTestCode($testCode) {
        $this->testCode = $testCode;
    }
    
    function getWidth() {
        return $this->width;
    }

    function getHigh() {
        return $this->high;
    }

    function setWidth($width) {
        $this->width = $width;
    }

    function setHigh($high) {
        $this->high = $high;
    }
    
    function getInteraction() {
        return $this->interaction;
    }

    function setInteraction($interaction) {
        $this->interaction = $interaction;
    }

            
    /*Metodo para obtener resutados de interacción*/
    function findInteractionData ($patientId){
        
        $this->getNameNewTest($patientId);
        $this->getElementsInteraction($patientId);
        $sizeCalculator = new sizeImage($this->getDistance());
        $sizeCalculator->findSizeForOptotypeInCM();
        $sizeCalculator->findSizeForOptotypeInPixel();
        $this->high = $sizeCalculator->optometricCarHigh();
        $this->width = $sizeCalculator->optometricCarWidth();
        
        return $sizeCalculator->getPixelSizeList();
 
    } 
    
    function  getNameNewTest ($patientId){
        
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
                        " AND ma.appointmentdate = to_date(".$today.",'dd/mm/yyyy')".
                        " AND te.testCode LIKE '%".$this->testCode."%'";  
        
        $db = new PgDataBase();
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
       
        if ($row = pg_fetch_row($result)) {
            $this->testCode = $row[0];
        }

     
    }
    
    function getElementsInteraction ($patientId){
        
        $date = getdate();
        $position = 0;
        $today = "'".$date['mday']."/".$date['mon']."/".$date['year']."'";
        
        $query = "  SELECT op.optotypeCode". 
                    " FROM Patient pa, Medical_appointment ma, Test te, Optotype_Test ot, Optotype op ".
                    " WHERE pa.idPatient = ma.fk_idPatient ".
                        " AND ma.idAppointment = te.fk_idAppointment ".
                        " AND te.idTest = ot.fk_idTest ".
                        " AND ot.fk_idOptotype = op.idOptotype ".
                        " AND pa.idPatient = ".$patientId.
                        " AND ma.appointmentdate = to_date(".$today.",'dd/mm/yyyy')".
                        " AND te.testCode LIKE '%".$this->testCode."%'";  
        
        $db = new PgDataBase();
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        
        while ($row = pg_fetch_row($result)) {
            $this->interaction[$position] = $row [0];
            $position ++;
         }   
        
    }
    
}

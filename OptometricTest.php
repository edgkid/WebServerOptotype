<?php

require_once 'PgDataBase.php';
require_once'NewImage.php';

class OptometricTest extends NewImage {
    
    private $testCodeLeft = "";
    private $testCodeRigth = "";
    private $patient;
    private $interaction = array();
    
    function __construct($testCode) {
        parent::__construct();
    }
    
    /*Metodo para obtener resutados de interacción*/
    function findInteractionData ($patientId){
        
        $date = getdate();
        $today = "'".$date['mday']."/".$date['mon']."/".$date['year']."'";
        
        $query = "  SELECT te.testCode, op.idOptotype, op.optotypeCode ". 
                    " FROM Patient pa, Medical_appointment ma, Test te, Optotype_Test ot, Optotype op ".
                    " WHERE pa.idPatient = ma.fk_idPatient ".
                        " AND ma.idAppointment = te.fk_idAppointment ".
                        " AND te.idTest = ot.fk_idTest ".
                        " AND ot.fk_idOptotype = op.idOptotype ".
                        " AND pa.idPatient = ".$patientId.
                        " AND to_char(ma.appointmentdate,'dd/mm/yyyy') = ".$today;
        
        
        
        
    }
    
    

}

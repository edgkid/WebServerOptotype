<?php

//require_once 'PgDataBase.php';
require_once 'Diagnostic.php';
/**
 * Description of DiagnosticDB
 *
 * @author Edgar
 */
class DiagnosticDB extends PgDataBase {
    
    
    public function proccessDataDiagnostic(array $obj){
        
        $responce = "listo para procesar datos";
        
        switch ($obj[0]->action){
            
            case '0':
                //echo "salvar datos";
                $this->saveDataDiagnostic($obj);
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
  
        }
        
        return $responce;
    }
    
    private function saveDataDiagnostic(array $obj){

        $diagnostic = new Diagnostic();
        $diagnostic->setIdPatient($obj[0]->idPatient);
        
        //$this->saveDataDiagnosticSignalRegister($diagnostic, $obj);
        //$this->saveDataDiagnosticSignalPatient($diagnostic, $obj);
        //$this->saveDataDiagnosticAntecedentRegister($diagnostic, $obj);
        //// esto debo validarlo
        //$this->saveDataDiagnosticAntecedentRoll($diagnostic, $obj, $obj[0]->antacedentDad, 'M');
        //$this->saveDataDiagnosticAntecedentRoll($diagnostic, $obj, $obj[0]->antecedentMon, 'F');
        
        //$this->saveDataDiagnosticSubjectiveTest($diagnostic, $obj);
        $this->saveDataDiagnosticObjectiveTets($diagnostic, $obj);
    }
    
    private function getAId ($query, $tableNanme, $whereClausule){
        $id = 0;
        
        $query = $query.$tableNanme.$whereClausule;
        
        $result = pg_query($query);
        
        if ($row = pg_fetch_row($result)) {
           
            $id = $row[0]; 
        }
        
        return $id;
        
    }
    
    private function getSomeId ($query, $tableNanme, $whereClausule){
        
        $someId = array();
        $position = 0;
        $query = $query.$tableNanme.$whereClausule;
        
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
       
        while ($row = pg_fetch_row($result)) {
           
            $someId[$position] = $row[0];
            $position ++;
        }
        
        return $someId;
        
    }
    
    private function saveDataDiagnosticSignalRegister (Diagnostic $diagnostic, array $obj){
        
        $fk = (int) $obj[0]->idPatient;
        $idTable = "idSignalRegister";
        $whereClausule = " ";
        $query = " INSERT INTO Signal_BY_REGISTER (fk_idPatient) VALUES (".$fk.");";            
        $query = $query." commit;";
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        
        if ($result){
            $query = " Select max (".$idTable.") from ";
            $tableName = "Signal_BY_REGISTER";
            $diagnostic->setIdSignalDefect($this->getAId($query, $tableName, $whereClausule));
        }
            
    }
    
    private function saveDataDiagnosticSignalPatient (Diagnostic $diagnostic, array $obj){
        
        $someId = array();
        $arraySignal = split(',', $obj[0]->signalDefect);
        $tableName = " SIGNAL_DEFECT sd ";
        $query = " SELECT sd.idSignal FROM ";
        $whereClausule = " WHERE";
        
        for ($position = 0; $position < count($arraySignal); $position ++){
         
            $whereClausule = $whereClausule." sd.name like ('%".$arraySignal[$position]."%') ";
            $whereClausule = $whereClausule." OR ";
        }
        
        $whereClausule = substr($whereClausule, 0, -5).";";
        
        $someId = $this->getSomeId($query, $tableName, $whereClausule);

        $query = "";
        for ($position = 0; $position < count($someId); $position ++){
            
            $query = " INSERT INTO SIGNAL_DEFECT_PATIENT (fk_idSignal, fk_idregisterSignal) VALUES (";
            $query = $query.$someId[$position].",".$diagnostic->getIdSignalDefect()."); ";
            $query = $query." commit;";
            $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        }   
    }
    
    private function saveDataDiagnosticAntecedentRegister(Diagnostic $diagnostic, array $obj){
        
        $fk = (int) $obj[0]->idPatient;
        $idTable = "idByRegister";
        $whereClausule = " ";
        $query = " INSERT INTO ANTECENDENT_BY_REGISTER (fk_idPatient) VALUES (".$fk.");";            
        $query = $query." commit;";
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        
        if ($result){
            $query = " Select max (".$idTable.") from ";
            $tableName = " ANTECENDENT_BY_REGISTER ";
            $diagnostic->setIdAntecedent($this->getAId($query, $tableName, $whereClausule));
        }
        
    }
    
    private function saveDataDiagnosticAntecedentRoll (Diagnostic $diagnostic, array $obj, $antecedent, $roll){
        
        $someId = array();
        $arrayAntecedent = split(',', $antecedent);
        $tableName = " ANTECENDENT an ";
        $query = " SELECT an.idAntecedent FROM ";
        $whereClausule = " WHERE";
        
        for ($position = 0; $position < count($arrayAntecedent); $position ++){
         
            $whereClausule = $whereClausule." an.name like ('%".$arrayAntecedent[$position]."%') ";
            $whereClausule = $whereClausule." OR ";
        }
        
        $whereClausule = substr($whereClausule, 0, -5).";";
        
        $someId = $this->getSomeId($query, $tableName, $whereClausule);

        for ($position = 0; $position < count($someId); $position ++){
            
            $query = " INSERT INTO ANTECENDENT_ROLL (roll, fk_idAntecedent, fk_idByRegister) VALUES (";
            $query = $query."'".$roll."',".$someId[$position].",".$diagnostic->getIdAntecedent()."); ";
            $query = $query." commit;";
            $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        }
        
    }
    
    private function saveDataDiagnosticSubjectiveTest (Diagnostic $diagnostic, array $obj){
        
        $idTable = "idSubjective";
        $whereClausule = " ";
        $query = " INSERT INTO SUBJECTIVE_TEST (center, SUSTAIN, MAINTAIN) VALUES ('".$obj[0]->center."',";
        $query = $query."'".$obj[0]->sustain."','".$obj[0]->maintain."'); ";
        $query = $query." commit;";

        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        
        if ($result){
            $query = " Select max (".$idTable.") from ";
            $tableName = " Subjective_test ";
            $diagnostic->setIdSubjectiveTest($this->getAId($query, $tableName, $whereClausule));
        }  
    }
    
    private function saveDataDiagnosticObjectiveTets (Diagnostic $diagnostic, array $obj){
        
        $idTable = "idAvResult";
        $whereClausule = " ";
        $query = "INSERT INTO AV_RESULT (eyeRight,eyeLeft) VALUES (";
        $query = $query.$obj[0]->avRigth.",".$obj[0]->avLeft."); commit;";
        
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        
        if ($result){
            $query = " Select max (".$idTable.") from ";
            $tableName = " AV_RESULT ";
            $diagnostic->setIdObjectiveTest($this->getAId($query, $tableName, $whereClausule));
        }  
        
        echo $diagnostic->getIdObjectiveTest();
        
    }
    
}

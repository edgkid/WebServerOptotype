<?php

require_once 'PgDataBase.php';
require_once 'Diagnostic.php';
/**
 * Description of DiagnosticDB
 *
 * @author Edgar
 */
class DiagnosticDB {
    
    
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
        
        /*echo $obj[0]->idPatient.' ';
        echo $obj[0]->yearsOld." ";
        echo $obj[0]->gender." ";
        echo $obj[0]->center." ";
        echo $obj[0]->sustain." ";
        echo $obj[0]->maintain." ";
        echo $obj[0]->avRigth." ";
        echo $obj[0]->avLeft." ";
        echo $obj[0]->antecedentMon." ";
        echo $obj[0]->antacedentDad." ";
        echo $obj[0]->signalDefect." ";        
        echo $obj[0]->typeTest." ";
        echo $obj[0]->colaboratedGrade." ";*/
        
        $diagnostic = new Diagnostic();
        $diagnostic->setIdPatient($obj[0]->idPatient);
        
        $this->saveDataDiagnosticSignalRegister($diagnostic, $obj);
            
    }
    
    private function saveDataDiagnosticSignalRegister (Diagnostic $diagnostic, array $obj){
        
        $fk = (int) $obj[0]->idPatient;
        $connect = new PgDataBase();
        $connect->getPgConnect();
        $idTable = "idSignalRegister";
        $query = " INSERT INTO Signal_BY_REGISTER (fk_idPatient) VALUES (".$fk."); ";
        $query = $query." commit;";
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        
        if ($result){
            $query = " Select max (".$idTable.") from ";
            $tableName = "Signal_BY_REGISTER";
            $diagnostic->setIdSignalDefect($this->getAId($query, $tableName));
        }
            
    }
    
    private function getAId ($query, $tableNanme){
        $id = 0;
        
        $query = $query.$tableNanme;
        
        $result = pg_query($query);
        
        if ($row = pg_fetch_row($result)) {
           
            $id = $row[0]; 
        }
        
        return $id;
        
    }
    
}

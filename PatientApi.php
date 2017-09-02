<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'PatientDB.php';
/**
 * Description of PatientApi
 *
 * @author Edgar
 */
class PatientApi {
    
    Public function API(){
        
        header('Content-Type: application/JSON');                
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
        case 'GET'://consulta
            echo 'get';
            break;     
        case 'POST'://inserta
            echo'post';
            break;                
        case 'PUT'://actualiza
            echo'put';
            break;      
        case 'DELETE'://elimina
            echo'delete';
            break;
        default://metodo NO soportado
            echo 'METODO NO SOPORTADO';
            break;
        }
    }
    
    function getPatients(){
       if ($_GET['action'] == 'patients'){
           $db = new PatientDB();
           if(isset($_GET['id'])){
               //--------
               echo json_encode($response,JSON_PRETTY_PRINT);
            }else{
               $response = $db ->getPatientsForToday();
               echo json_encode($response,JSON_PRETTY_PRINT);
           }
       }else{
           $this->response(400);
       }
       
    }
    
     function response($code=200, $status="", $message="") {
       http_response_code($code);
       if( !empty($status) && !empty($message) ){
           $response = array("status" => $status ,"message"=>$message);  
           echo json_encode($response,JSON_PRETTY_PRINT);    
       }   
    }  
    
    
}

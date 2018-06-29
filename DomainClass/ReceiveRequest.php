<?php

require_once 'UserApi.php';
require_once 'PatientApi.php';
require_once 'OptotypeApi.php';
require_once 'TestApi.php';
require_once 'AppointmentApi.php';
require_once 'AvResultApi.php';
require_once 'DiagnosticApi.php';
require_once 'ChromaticDefectApi.php';
require_once 'OpticalDefectApi.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReceiveRequest
 *
 * @author Edgar
 */
class ReceiveRequest {
    
    private $action;
    
    function __construct($action) {
        $this->action = $action;
    }

    function getAction() {
        return $this->action;
    }

    function setAction($action) {
        $this->action = $action;
    }


    public function answerToRequested(){
        
        if ($this->action == 'updateKids'){
            $update = new PatientApi();
            $update->API();
        }

        if ($this->action == 'updateOptotypes'){
            $update = new OptotypeApi();
            $update->API();
        }

        if ($this->action == 'users'){
            $userApi = new UserApi();
            $userApi->API(); 
        }

        if ($this->action == 'patients'){
             $patientApi = new PatientApi();
             $patientApi->API();
        }

        if ($this->action == 'optotypes'){
             $optotypetApi = new OptotypeApi();
             $optotypetApi->API();
        }

        if ($this->action == 'test'){
            $testApi = new TestApi();
            $testApi->API();
        }

        if ($this->action == 'appointment'){
            $appointment = new AppointmentApi();
            $appointment->API();
        }

        if ($this->action == 'avResult'){

            $avResult = new AvResultApi();
            $avResult->API();
        }

        if ($this->action == 'diagnostic'){

            $diagnostic = new DiagnosticApi();
            $diagnostic->API();
        }

        if ($this->action == 'antecedent'){
            $chromaticDefect = new ChromaticDefectApi();
            $chromaticDefect->API();
        }

        if ($this->action == 'signalDefect'){

            $opticalDefectApi = new OpticalDefectApi();
            $opticalDefectApi->API();

        }
    }
    
}

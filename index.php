<?php
    require_once 'UserApi.php';
    require_once 'PatientApi.php';
    require_once 'OptotypeApi.php';

    
    if ($_GET['action'] == 'updateKids'){
        $update = new PatientApi();
        $update->API();
    }
    
    if ($_GET['action'] == 'users'){
        $userApi = new UserApi();
        $userApi->API(); 
    }
    
    if ($_GET['action'] == 'patients'){
         $patientApi = new PatientApi();
         $patientApi->API();
    }
    
    if ($_GET['action'] == 'optotypes'){
         $optotypetApi = new OptotypeApi();
         $optotypetApi->API();
    }
    
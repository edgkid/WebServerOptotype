<?php
    require_once 'UserApi.php';
    require_once 'PatientApi.php';
    
    if ($_GET['action'] == 'users'){
        $userApi = new UserApi();
        $userApi->API(); 
    }
    
    if ($_GET['action'] == 'patients'){
         $patientApi = new PatientApi();
         $patientApi->API();
    }
    
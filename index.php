<?php
    require_once 'UserApi.php';
    require_once 'PatientApi.php';
    require_once 'OptotypeApi.php';
    require_once 'TestApi.php';
    require_once 'AppointmentApi.php';

    
    if ($_GET['action'] == 'updateKids'){
        $update = new PatientApi();
        $update->API();
    }
    
    if ($_GET['action'] == 'updateOptotypes'){
        $update = new OptotypeApi();
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
    
    if ($_GET['action'] == 'test'){
        $testApi = new TestApi();
        $testApi->API();
    }
    
    if ($_GET['action'] == 'appointment'){
        $appointment = new AppointmentApi();
        $appointment->API();
    }
    
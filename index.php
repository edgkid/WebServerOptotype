<?php
    require_once 'UserApi.php';
    require_once 'PatientApi.php';
    
    $userApi = new UserApi();
    $userApi->API();
    
    $patientApi = new PatientApi();
    $patientApi->API();
    
    
?>
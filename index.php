<?php
    require_once "PeopleAPI.php"; 
    require_once "VehicleApi.php"; 
    $peopleAPI = new PeopleAPI();
    $peopleAPI->API();
    
    $vehicleAPI = new VehicleApi();
    $vehicleAPI->API();
    
?>
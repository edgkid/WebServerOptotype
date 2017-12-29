<?php

class OptometricTest extends NewImage {
    
    private $testCode = "";
    private $patient;
    private $interaction = array();
    
    function __construct($testCode) {
        $this->testCode = $testCode;
        parent::__construct();
    }
    
    /*Metodo para obtener resutados de interacción*/
    function findInteractionData (){
        
    }
    
    

}

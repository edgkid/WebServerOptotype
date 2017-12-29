<?php

class NewImage {
    
    private $optotypePath = "";
    private $optometricCardPath = "";
    
    function __construct($optotypePath, $optometricCardPath) {
        $this->optotypePath = $optotypePath;
        $this->optometricCardPath = $optometricCardPath;
    }
    
    function getOptotypePath() {
        return $this->optotypePath;
    }

    function getOptometricCardPath() {
        return $this->optometricCardPath;
    }

    function setOptotypePath($optotypePath) {
        $this->optotypePath = $optotypePath;
    }

    function setOptometricCardPath($optometricCardPath) {
        $this->optometricCardPath = $optometricCardPath;
    }

    /*La siguiente se encarga de cra una nueva imagen en base aun lienzo para las carta
     */
    function resizeImage(){
        
    }

    /*sra la encargada de insertar cada optotipo dentro de la carta*/
    function newOptometricCard(){
        
    }
}
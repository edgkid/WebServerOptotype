<?php

class NewImage {
    
    private $optotypePath = "";
    private $optometricCardPath = "";
    private $distance;
    
    function __construct($optotypePath, $optometricCardPath, $distance) {
        $this->optotypePath = $optotypePath;
        $this->optometricCardPath = $optometricCardPath;
        $this->distance = $distance;
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
    
    function getDistance() {
        return $this->distance;
    }

    function setDistance($distance) {
        $this->distance = $distance;
    }

    
    /*La siguiente se encarga de cra una nueva imagen en base aun lienzo para las carta
     */
    function resizeImage(){
        
    }

    /*sra la encargada de insertar cada optotipo dentro de la carta*/
    function newOptometricCard(){
        
    }
}
<?php

class NewImage {
    
    private $optotypePath = "C:/xampp/htdocs/WSOptotype/optotypesImage";
    private $optometricCardPath = "C:/xampp/htdocs/WSOptotype/OptometricCard";
    private $distance;
    
    function __construct() {
     
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
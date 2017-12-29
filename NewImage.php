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

    function resizeImage(){
        
    }

    function newOptometricCard(){
        
    }
}
<?php

class sizeImage {
    
   private $avList = array('0.1','0.8','0.63','0.5','0.4','0.5','0.33','0.25','0.2','0.17','0.13','0.1');
   private $distance;
   private $cm=10;
   private $mm=1000;
   
   function __construct($distance) {
       $this->distance = $distance;
   }
   
   function getDistance() {
       return $this->distance;
   }

   function setDistance($distance) {
       $this->distance = $distance;
   }

   /*metodo para calcular el tamaño de cada optotipo segun reglon en la carta optometrica*/
   function findSizeForOptotype(){
       
   }

}

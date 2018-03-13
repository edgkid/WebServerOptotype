<?php
/**
 * Description of TestParameter
 *
 * @author Edgar
 */
class TestParameter {
    
    private $pppReferencia = 72;
    private $pppCalculos = 110;
    private $avList = array('1','0.8','0.63','0.5','0.4','0.33','0.25','0.2','0.17','0.13','0.1');
    private $distance;
    
    function __construct($distance) {
       
       $this->distance = $distance;
   }
   
   function getPppReferencia() {
       return $this->pppReferencia;
   }

   function getPppCalculos() {
       return $this->pppCalculos;
   }

   function getAvList() {
       return $this->avList;
   }

   function getDistance() {
       return $this->distance;
   }

   function setPppReferencia($pppReferencia) {
       $this->pppReferencia = $pppReferencia;
   }

   function setPppCalculos($pppCalculos) {
       $this->pppCalculos = $pppCalculos;
   }

   function setAvList($avList) {
       $this->avList = $avList;
   }

   function setDistance($distance) {
       $this->distance = $distance;
   }
   
}

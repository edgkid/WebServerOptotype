<?php

class sizeImage {
    
   private $avList = array('1','0.8','0.63','0.5','0.4','0.33','0.25','0.2','0.17','0.13','0.1');
   private $cmSizeList = array();
   private $pixelSizeList = array();
   private $distance;
   private $cm=10;
   private $mm=1000;
   private $sizeFive = 5;
   
   function __construct($distance) {
       
       $this->distance = $distance;
   }
   
   function getDistance() {
       return $this->distance."\n";
   }

   function setDistance($distance) {
       $this->distance = $distance;
   }

   /*metodo para calcular el tamaño de cada optotipo segun reglon en la carta optometrica*/
   function findSizeForOptotypeInCM(){
       
       echo $this->distance."\n";
       
       $position = 0;
       $arcMin = 1;
       $min = 60;
       $grade = 180;
       $e=0;
       $h=0;
       
       $sizeArray = count($this->avList);
       
       while ($position < $sizeArray ){
           echo $this->avList[$position]."\n";
           
           $avMin = $arcMin/$this->avList[$position];//Av in arc min
           $avGrade = $avMin * ($arcMin/$min);//Av in Grade
           $avRadians =$avGrade * (pi()/$grade);//Av un radians
           $e = $avRadians * ($this->distance * $this->mm);//size by minimal detail in mm
           $h = $e * $this->sizeFive;//maximal size by optotype in mm
           echo $h." mm"."\n";
           $h = round($h /$this->cm,2);  
           echo $h." cm"."\n";
           
           $this->cmSizeList[$position] = $h; 
           
           $position ++;
       }  
   }
   
   function findSizeForOptotypeInPixel (){
       
       echo "Pixel"."\n";
       $value = 0; //varieble for size in pixel
       $ppp=300;//point per inches
       $pCm= 2.54; // Cm by inches
       $position = 0;
       
       $sizeArray = count($this->cmSizeList);
       
       while ($position < $sizeArray ){
           
           $value = round(($this->cmSizeList[$position]*$ppp)/$pCm);
           echo $value." Pixel"."\n";
           $this->pixelSizeList[$position] = $value;
           $position ++;
       }
   }
   
   function optometricCarHigh (){
       
       $value = 0;
       $headAnFooter = 0;
       $position = 0;
       $percentage = 0.10;
       $lineSpace = 355; // equivalente en pixel de 3cm
       
       echo "Altura de la carta"."\n";
       echo "high: ";
       $sizeArray = count($this->pixelSizeList);
       
       while($position < $sizeArray){
           
           $value = $value + $this->pixelSizeList[$position];
           $position ++;
       }
       
       $headAnFooter = round(($value * $percentage), 0);
       
       $value = $value + $headAnFooter  + $lineSpace;
       
       echo $value;
       
       return $value;
   }
   
   function optometricCarWidth (){
       
        $value = 0;
        $rightAnLeft = 0;
        $numElements = 2;
        $percentage = 0.15;
        $columnSpace = 355; // equivalente en pixel de 3cm
       
        echo "\n"."Ancho de la carta"."\n";
        echo "width: ";
       
        $position = count($this->pixelSizeList);
        $value = $this->pixelSizeList[$position-1] * $numElements +($columnSpace * 2);

        $value = round($value + ($value * $percentage),0);
      
        echo $value;
       
        return $value;
   }

}


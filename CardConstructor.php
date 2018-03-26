<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CardConstructor
 *
 * @author Edgar
 */
class CardConstructor {
    
    private $optotypesPath;
   private $rowsPath;
   private $canvasPath;
   private $xPosition;
   private $yPosition;
   private $arrayOptotypes;//// se consulta
   private $arrayPixels;//// parametro
   private $distance;
   
   
   function __construct($arrayPixels, $distance) {
       $this->arrayPixels = $arrayPixels;
       $this->distance = $distance;
       //$this->arrayOptotypes = array();
       $this->arrayOptotypes = array('avion_1','barco_1','botella_1','camion_1','circulo_1','corazon_1','estrella_1');
       $this->xPosition = 0;
       $this->yPosition = 10;
       $this->canvasPath = "C:/xampp/htdocs/WSOptotype/OptometricCard/";
       $this->optotypesPath = "C:/xampp/htdocs/WSOptotype/OptotypeForCard/";
       $this->rowsPath = "C:/xampp/htdocs/WSOptotype/rowsBase/";       
   }
   
   function setXPosition($xPosition) {
       $this->xPosition = $xPosition;
   }

   function setYPosition($yPosition) {
       $this->yPosition = $yPosition;
   }
   
   function getXPosition() {
       return $this->xPosition;
   }

   function getYPosition() {
       return $this->yPosition;
   }

       
   
   function fillCanvasRows(){
       
       $rows = 0;
       $totalRows = 11;
       $column = 1;
       $totalColumn = 4;
       $position = 0;
       $canvas = $this->rowsPath."prueba";
       $element = "";
       $pixelArray = array_reverse($this->arrayPixels);
       
       While ($rows < $totalRows){
           
           $this->setXPosition(10);
           
           while($column <= $totalColumn){
               
               if ($position == count ($this->arrayOptotypes)){
                   $position = 0;
               }
               
               $canvas = $canvas."_".($rows + 1).".png";
               $element = $this->optotypesPath.$this->arrayOptotypes[$position].".png";
               $posInCanvas = $this->getXPosition() + $pixelArray[$rows] + 60;               
               $this->insertElementsInCanvas($canvas, $element, $rows);
               $this->setXPosition($posInCanvas);
               
               $column ++;
               $position ++;
               $canvas = $this->rowsPath."prueba";
               
           }
           
           $column = 1;
           $rows ++;
       }
       
   }
   
    function fillOptometricCard(){
        
        $position = 0;
        $imageSize = null;
        $xSize = 0;
        $ySize = 0;
        $arrayRows = array('prueba_1','prueba_2','prueba_3','prueba_4','prueba_5','prueba_6','prueba_7','prueba_8','prueba_9','prueba_10','prueba_11');
        $canvas = $this->canvasPath.'prueba.png';
        $element = $this->rowsPath;
        
        $this->setXPosition(150);
       
        for ($position=0; $position < count($arrayRows); $position++){
            
            $element = $element.$arrayRows[$position].".png";
                        
//            //busco ancho y alto de cada row image creado
            $imageSize = getimagesize($element );    
            $xSize = $imageSize[0];              
            $ySize = $imageSize[1]; 
            
            $this->builOptometricCard($canvas, $element, $xSize, $ySize);
            $this->setYPositionOnCard($ySize, $position);
            $this->setXPosiitonOnCard($position);

            $element = $element = $this->rowsPath;    
          
        }
        
        
    }
   
   private function insertElementsInCanvas ($canvas,$element, $position){
         
         //indico la dirección de la imagen a utilizar
        $imageCanvas = $canvas;
        $imageElement = $element;
        
        $array = array_reverse($this->arrayPixels);
        $xPixel = $array[$position];
        $yPixel = $array[$position];

         /// creo un identificador en memoria para las imagnes
        $imgCanvas = imagecreatefrompng($imageCanvas);
        $imgElement = imagecreatefrompng($imageElement);
        imagealphablending($imgElement, false);
        imagesavealpha($imgElement, false);
         
         
         //Combino las imagenes
         imagecopyresampled(
                 $imgCanvas,
                 $imgElement, 
                 $this->xPosition, 
                 $this->yPosition, 
                 0, 
                 0, 
                 $xPixel, /*Nuevo tamaño en x*/
                 $yPixel, /*Nuevo tamaño en y*/
                 imagesx($imgElement), 
                 imagesy($imgElement)
                 );
         
         //sobreescribo el lienzo creado generando un resultado final
         imagepng($imgCanvas, $imageCanvas);
         
         // limpio de la memoria los identificadores (imagenes)
         imagedestroy($imgCanvas);
         imagedestroy($imgElement);
         
     }
     
     private function builOptometricCard ($canvas,$element, $newXSize, $newYSize){
         
         //indico la dirección de la imagen a utilizar
        $imageCanvas = $canvas;
        $imageElement = $element;

         /// creo un identificador en memoria para las imagnes
        $imgCanvas = imagecreatefrompng($imageCanvas);
        $imgElement = imagecreatefrompng($imageElement);
        imagealphablending($imgElement, false);
        imagesavealpha($imgElement, false);
         
         
         //Combino las imagenes
         imagecopyresampled(
                 $imgCanvas,
                 $imgElement, 
                 $this->xPosition, 
                 $this->yPosition, 
                 0, 
                 0, 
                 $newXSize, /*Nuevo tamaño en x*/
                 $newYSize, /*Nuevo tamaño en y*/
                 imagesx($imgElement), 
                 imagesy($imgElement)
                 );
         
         //sobreescribo el lienzo creado generando un resultado final
         imagepng($imgCanvas, $imageCanvas);
         
         // limpio de la memoria los identificadores (imagenes)
         imagedestroy($imgCanvas);
         imagedestroy($imgElement);
         
     }
     
    private function setYPositionOnCard($ySize, $position){
         
         if ($this->distance >= 1 && $this->distance <=3){
             
             // Funciona para 3, 2 y 1 metros
            if ($position < 3){
                $this->setYPosition($this->yPosition + $ySize + 80);
            }elseif ($position >= 3 && $position < 7) {
                $this->setYPosition($this->yPosition + $ySize + 50);
            }elseif ($position >= 7) {
                $this->setYPosition($this->yPosition + $ySize + 30);
            }
             
         }elseif($this->distance >3){
             
             //funciona para 4,5 y 6 metros
            if ($position < 2){
                $this->setYPosition($this->yPosition + $ySize);
            }elseif ($position >= 2) {
                $this->setYPosition($this->yPosition + $ySize + 70);
            }
         } 
     }
     
    private function setXPosiitonOnCard($position){
         
         if ($this->distance >= 2 && $this->distance <= 4){
            
            /// funciona para 4, 3 , 2 y 1 metro 
            if ($position < 2){
              $this->setXPosition($this->getXPosition() + 70);  
            }elseif($position >= 2 && $position < 7){
                $this->setXPosition($this->getXPosition()+ 20);
            }
            elseif($position >= 7 && $position < 11){
                $this->setXPosition($this->getXPosition() + 5);
            }
            
         }elseif($this->distance >= 5 && $this->distance <= 6){
             
            //// funciona para para 5 y 6 metros
            if ($position < 6){
              $this->setXPosition($this->getXPosition() + 70);  
            }elseif($position >= 6){
                $this->setXPosition($this->getXPosition()+ 20);
            }
             
         }
  
     }
    
}

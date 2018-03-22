<?php
/**
 * Description of Canvas
 *
 * @author Edgar
 */
class Canvas {
   
    private $idList;
    private $AvList;
    private $elements;
    private $imagePath;
    private $imagebasePath;
    private $elementsPath;
    private $with;
    private $height;
    private $avPixels;
    private $xPosition;
    private $yPositon;
    private $canvasCode;
    private $distance;
     
    private $xRow;
    private $yRow;
     
     function __construct($with, $height, $avPixels, $avelements, $canvasCode, $distance) {
         $this->idList =  array('uno','dos','tres','cuatro','cinco','seis','siete','ocho','nueve','diez','once');
         $this->AvList = array('Av1','Av2','Av3','Av4','Av5','Av6','Av7','Av8','Av9','Av10','Av11');
         //$this->elements = array('avion_1','barco_1','botella_1','camion_1','circulo_1','corazon_1','estrella_1'); 
         $this->elements = $avelements;
         $this->imagePath = "C:/xampp/htdocs/WSOptotype/OptometricCard/";
         $this->elementsPath = "C:/xampp/htdocs/WSOptotype/OptotypeForCard/";
         $this->imagebasePath = "C:/xampp/htdocs/WSOptotype/BaseCarta/";
         $this->canvasCode = $canvasCode;
         $this->avPixels = $avPixels;
         $this->with = $with;
         $this->height = $height;
         $this->distance = $distance;
         $this->xPosition = 100;
         $this->yPositon = 250;
         
         $this->xRow = 200;
         $this->yRow = 100;
     }
     
     function getElements() {
         return $this->elements;
     }

     function setElements($elements) {
         $this->elements = $elements;
     }

          
     function setXPosition($xPosition) {
         $this->xPosition = $xPosition;
     }

     function setYPositon($yPositon) {
         $this->yPositon = $yPositon;
     }
     
     function setXRow($xRow) {
         $this->xRow = $xRow;
     }

     function setYRow($yRow) {
         $this->yRow = $yRow;
     }
     
     function getCanvasCode() {
         return $this->canvasCode;
     }

     function setCanvasCode($canvasCode) {
         $this->canvasCode = $canvasCode;
     }
          
     function newCanvasImage(){
         
        $auxX = $this->xPosition;
        $auxY = $this->yPositon;
        $pathImage = $this->imagePath.$this->getCanvasCode().".png";
        
        $img = imagecreate($this->with, $this->height);
 
        // fondo blanco y texto azul
        imagecolorallocate($img, 255, 255, 255);

        // Guardo la imagen
        header("Content-type: image/png");
        imagepng($img,$pathImage);
        imagedestroy($img);
        
        $this->getBaseElementsforCanvas($pathImage,$this->idList);
        $this->setYPositon($auxY);
        $this->setXPosition($this->with - $auxX);
        $this->getBaseElementsforCanvas($pathImage,$this->AvList);

     }
     
     function canvasToOptometricCard(){
         
         $position = 0;
         $count = 0;
         $typeElement = 1;
         $typeInsert = 1;
         $totalColumn = 4;
         $column = 1;
         
        //hay que delegar esto
        //$this->setXPosition(($this->with)/2 - 200);
        
        //$this->setXPosition(200);//// 5 y 6
        //$this->setXPosition(250);//// 3 y 4
        
        
        //$this->setYPositon(100);//// 3, 4 ,5 ,6
        ///
        
        if ($this->distance == 2 ){
            $this->setXPosition(300);//// 1 y 2
            //$this->setYPositon(250);//// 1
            $this->setYPositon(200);//// 2
        }
        
        if ($this->distance >= 3 || $this->distance <=4){
            $this->setXPosition(250);//// 3 y 4
            $this->setYPositon(150);//// 3, 4 
        }
        
        if ($this->distance >= 5 || $this->distance <=6){
            $this->setXPosition(200);//// 5 y 6
            $this->setYPositon(100);
        }
        
        $array = $this->avPixels;
        $array = array_reverse($array);
         
        $canvas = $this->imagePath.$this->canvasCode.".png";
        $sizeArray = count($this->idList);
         
         //while ($count < ($sizeArray -1)){
         while ($count < $sizeArray){
             /*$this->insertElementsInCanvas($canvas,$this->elements[$position],$count, $typeElement, $typeInsert);
             $count ++;
             $position ++;
             $totalColumn ++;
             
             if ($position == count($this->elements))
                 $position = 0;*/
             
             While ($column <= $totalColumn){
                 
                 if ($position == count($this->elements)){
                     $position = 0;
                 }
                 
                 $this->insertElementsInCanvas($canvas,$this->elements[$position],$count, $typeElement, $typeInsert);
                 $this->setXPosition($this->xPosition + $array[$count] + 50);

                 $column ++;
                 $position ++;
             }
             
             //// para 5 y 6 metros
            $this->canvasByFiveAndSixMeter($count, $array);
             
             
             //// 3 y 4
             //$this->canvasByTrheAndFourMeter($count, $array);
             
             /// 2 
             //$this->canvasByTwoMeter($count, $array);
            
             $column = 1;
             $count ++;
         }
          
     }
         
    
     private function getBaseElementsforCanvas ($canvas ,$arrayElements){
         
         $sizeArray = count($arrayElements);
         $position = 0;
         $typeElemnt = 0;
         $typeInsert = 0;
    
         //echo 'elementos identificadores'."<br>";
         while ($position < $sizeArray){
             
             //echo $arrayElements[$position]."<br>";
             $this->insertElementsInCanvas($canvas,$arrayElements[$position],$position, $typeElemnt, $typeInsert);
             $position ++;
             
         }
         
     }
     
     private function insertElementsInCanvas ($canvas,$element, $position, $typeElemnt, $typeInsert){
         
         //indico la dirección de la imagen a utilizar
         $imageCanvas = $canvas;
         
         
         /// Este bloque de codigo debe ir a un metodo
         if ($typeElemnt == 0){
             $xPixel = 57;
             $yPixel = 57;
             $imageElement = $this->imagebasePath.$element.".png";
         } elseif ($typeElemnt == 1) {
             $imageElement = $this->elementsPath.$element.".png";
             $array = $this->avPixels;
             $array = array_reverse($array);
             $xPixel = $array[$position];
             $yPixel = $array[$position];
         }
         
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
                 $this->yPositon, 
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
         
        if($typeInsert == 0)
            $this->setPositionelemnts($position);
       
     }
     
     private function setPositionelemnts($position){
         
         $extraSpcaceY = 60;
         $array = $this->avPixels;
         $array = array_reverse($array);
         $this->setYPositon($array[$position] + $this->yPositon + $extraSpcaceY);
       
     }
     
     private function canvasByTwoMeter($count, $array){
         
         if ( $count < 1){
                $this->setXRow($this->xRow + 130);
                $this->setXPosition($this->xRow);
                $this->setYRow($this->yRow + $array[$count] + 150);
             }elseif($count >= 1 && $count < 3){
                 $this->setXRow($this->xRow + 60);
                $this->setXPosition($this->xRow);
                $this->setYRow($this->yRow + $array[$count] + 100);
             }elseif($count >= 3 && $count < 5){
                 $this->setXRow($this->xRow + 40);
                $this->setXPosition($this->xRow);
                $this->setYRow($this->yRow + $array[$count] + 70);
             }elseif($count >= 5 && $count < 9){
                 $this->setXRow($this->xRow + 20 );
                $this->setXPosition($this->xRow);
                $this->setYRow($this->yRow + $array[$count] + 50);
             }elseif($count >= 9 && $count < 10){
                 $this->setXRow($this->xRow);
                $this->setXPosition($this->xRow);
                $this->setYRow($this->yRow + $array[$count] + 50);
             }
       
             
        $this->setYPositon($this->yRow);   
     }
     
     private function canvasByTrheAndFourMeter($count, $array){
         if ( $count < 2){//4 para 5 y 6 metros
            $this->setXRow($this->xRow + 110);
            $this->setXPosition($this->xRow);
            $this->setYRow($this->yRow + $array[$count] + 90);
         }elseif($count >= 2 && $count < 8){
            $this->setXRow($this->xRow + 30);
            $this->setXPosition($this->xRow);
            $this->setYRow($this->yRow + $array[$count] + 70); 
         }elseif($count >= 8 && $count < 10){
             $this->setXRow($this->xRow + 10);
            $this->setXPosition($this->xRow);
            $this->setYRow($this->yRow + $array[$count] + 50);
         }

         $this->setYPositon($this->yRow); 
     }
     
     private function canvasByFiveAndSixMeter($count, $array){
         
         if ( $count < 4){//4 para 5 y 6 metros
                $this->setXRow($this->xRow + 110);
                $this->setXPosition($this->xRow);
                $this->setYRow($this->yRow + $array[$count] + 90);
             }elseif($count >= 4 && $count < 7){ 
                $this->setXRow($this->xRow + 50);
                $this->setXPosition($this->xRow);
                $this->setYRow($this->yRow + $array[$count] + 80);
             }elseif($count >= 7){ 
                $this->setXRow($this->xRow + 30);
                $this->setXPosition($this->xRow);
                $this->setYRow($this->yRow + $array[$count] + 60);
             }
             
          $this->setYPositon($this->yRow);
         
     }
    
}

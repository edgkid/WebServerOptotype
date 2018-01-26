<?php

class NewImage {
    
    private $optotypePath = "C:/xampp/htdocs/WSOptotype/optotypesImage";
    private $optometricCardPath = "C:/xampp/htdocs/WSOptotype/OptometricCard";
    private $distance;
    private $lineSpace = 0;
    private $columnSpace = 0;
    private $action = 0;
    
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
    function resizeImage($heigh, $witdh, $name){
        
        $img = imagecreate($witdh, $heigh);
        imagecolorallocate($img, 255, 255, 255);
        imagepng($img, "OptometricCard/".$name.".png");
        imagedestroy($img);

    }

    /*sra la encargada de insertar cada optotipo dentro de la carta*/
    function newOptometricCard($interactionElements, $testCode, $canvasWidth, $canvasHeigh, $pixelArray){
        $column = 1;
        $row = 1;
        $totalColumn = 1;
        $position = 0;
        $totalRow = count($pixelArray);
        $x = false;
        $y = true;
        
        
        $pixelArray = array_reverse ( $pixelArray);
        //echo "\n".count($interactionElements)."\n";
        //echo count($pixelArray)."\n";
        
        while ($row <= $totalRow){
            while ($column <= $totalColumn){
                
                if ($position == count($interactionElements)-1)
                    $position = 0;
                
                $imageOptotype = $interactionElements[$position];
                $this->insertOptotypeInTest($testCode, $imageOptotype, $canvasWidth, $canvasHeigh, $pixelArray[$row-1], $y, $x, ($row-1));
                $column ++;
                $position ++;
                $y = false;
                $x = true;
                
            }
            
            $column = 1;
            $x = false;
            $y = true;
            
            if ($totalColumn != 8)
                $totalColumn  ++;
            
            $row ++;
        }

    }
    
    function insertOptotypeInTest($testCode, $optotype, $canvasWidth, $canvasHeigh, $pixel, $y, $x, $row){
        
        // primero indico la dirección de las imagnes a utilzar
        $imageCanvas = "C:/xampp/htdocs/WSOptotype/OptometricCard/".$testCode.".png";
        $imageOptotype = "C:/xampp/htdocs/WSOptotype/OptotypeForCard/".$optotype.".png";

        // Creo identificadores para cada imagen a utilizar
        $imgCanvas = imagecreatefrompng($imageCanvas);
        $imgOptotype = imagecreatefrompng($imageOptotype);
        imagealphablending($imgOptotype,false);
        imagesavealpha($imgOptotype, false);
        
        // Se procede a combinar las imagenes
        $this->SettingOnRowAndColunm($x, $y, $pixel, $canvasWidth, $canvasHeigh, $row);
        
        imagecopyresampled(
        $imgCanvas,
        $imgOptotype,
        $this->columnSpace,/*posicion en X*/ 
        $this->lineSpace, /*posicion en Y*/
        0, 
        0,
        $pixel,/*nuevo tamaño en X*/
        $pixel,/*nuevo tamaño en Y*/
        imagesx($imgOptotype),/*tamaño original de la imagen en X*/
        imagesy($imgOptotype) /*tamaño original de la imagen en Y*/
        );
        
        //Solicito que se genere un resultado final
        imagepng($imgCanvas, "OptometricCard/".$testCode.".png");

        // por buena practica mando a limpipiar la memoria de las imagenes creadas
        imagedestroy($imgCanvas);
        imagedestroy($imgOptotype);
        
    }
    
    function SettingOnRowAndColunm ($x, $y, $pixel, $canvasWidth, $canvasHeigh, $row){
        
        $this->action ++;
        
        if ($this->action > 1){
            
            if ($y){
                $this->lineSpace = $this->lineSpace +  $pixel + 355;
                if ($row < 8)
                    $this->columnSpace = (($canvasWidth/2)-((($pixel *($row+1)) +($row*355))/2));
                else
                    $this->columnSpace = (($canvasWidth/2)-((($pixel * 7) +(7 * 355))/2));
            }    
            if ($x){
                $this->columnSpace = $this->columnSpace + $pixel + 355;
            }
        }else{
            $this->lineSpace = 355;
            $this->columnSpace = (($canvasWidth/2)-((($pixel *($row+1)) +($row*355))/2));
        }
    }
   
}
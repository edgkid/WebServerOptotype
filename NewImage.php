<?php

class NewImage {
    
    private $optotypePath = "C:/xampp/htdocs/WSOptotype/optotypesImage";
    private $optometricCardPath = "C:/xampp/htdocs/WSOptotype/OptometricCard";
    private $distance;
    private $lineSpace = 0;
    private $columnSpace = 0;
    
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
        
        /*$imageLienzo="C:/xampp/htdocs/NuevaImagen/Imagenes/Lienzo.png";
 
        //Creamos una variable imagen a partir de la imagen original
        $imgLienzo = imagecreatefrompng($imageLienzo);

        //Se define el maximo ancho y alto que tendra la imagen final
        $maxWidth = $width;
        $maxHeigh = $heigh;

        //Ancho y alto de la imagen original
        list($widthLienzo,$heighLienzo)=getimagesize($imageLienzo);

        //Se calcula ancho y alto de la imagen final
        $x_ratio = $maxWidth / $widthLienzo;
        $y_ratio = $maxHeigh / $heighLienzo;

        //Si el ancho y el alto de la imagen no superan los maximos,
        //ancho final y alto final son los que tiene actualmente
        if( ($widthLienzo <= $maxWidth) && ($heighLienzo <= $maxHeigh) ){//Si ancho
            $finalWidth = $widthLienzo;
            $finalHeigh = $heighLienzo;
        }
       
        elseif (($x_ratio * $heighLienzo) < $maxHeigh){
            $finalHeigh = ceil($x_ratio * $heighLienzo);
            $finalWidth = $maxWidth;
        }

        else{
            $finalWidth = ceil($y_ratio * $widthLienzo);
            $finalHeigh = $maxHeigh;
        }

        //Creamos una imagen en blanco de tamaño $ancho_final  por $alto_final .
        $newImage=imagecreatetruecolor($finalWidth,$finalHeigh);

        //Copiamos $img_original sobre la imagen que acabamos de crear en blanco ($tmp)
        imagecopyresampled($newImage,$imgLienzo,0,0,0,0,$finalWidth, $finalHeigh,$widthLienzo,$heighLienzo);

        //Se destruye variable $img_original para liberar memoria
        imagedestroy($imgLienzo);

        //Se crea la imagen final en el directorio indicado
        imagepng($newImage,"OptometricCard/".$name.".png");*/
        
        $img = imagecreate($witdh, $heigh);
        imagecolorallocate($img, 255, 255, 255);
        header("Content-type: image/png");
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
        echo "\n".count($interactionElements)."\n";
        echo count($pixelArray)."\n";
        
        while ($row <= $totalRow){
            while ($column <= $totalColumn){
                
                if ($position == count($interactionElements)-1)
                    $position = 0;
                
                $imageOptotype = $interactionElements[$position];
                $this->insertOptotypeInTest($testCode, $imageOptotype, $canvasWidth, $canvasHeigh, $pixelArray[$row-1], $y, $x);
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
    
    function insertOptotypeInTest($testCode, $optotype, $canvasWidth, $canvasHeigh, $pixel, $y, $x){
        
        // primero indico la dirección de las imagnes a utilzar
        $imageCanvas = "C:/xampp/htdocs/WSOptotype/OptometricCard/".$testCode.".png";
        $imageOptotype = "C:/xampp/htdocs/WSOptotype/OptotypeForCard/".$optotype.".png";

        // Creo identificadores para cada imagen a utilizar
        $imgCanvas = imagecreatefrompng($imageCanvas);
        $imgOptotype = imagecreatefrompng($imageOptotype);
        imagealphablending($imgOptotype,false);
        imagesavealpha($imgOptotype, false);
        
        // Se procede a combinar las imagenes
        imagecopyresampled(
        $imgCanvas,
        $imgOptotype,
        $this->columnSpace,/*posicion en X*/ 
        $this->lineSpace, /*posicion en Y*/
        0, 
        0,
        100,/*nuevo tamaño en X*/
        100,/*nuevo tamaño en Y*/
        imagesx($imgOptotype),/*tamaño original de la imagen en X*/
        imagesy($imgOptotype) /*tamaño original de la imagen en Y*/
        );
        
        //Solicito que se genere un resultado final
        imagepng($imgCanvas, "OptometricCard/".$testCode.".png");

        // por buena practica mando a limpipiar la memoria de las imagenes creadas
        imagedestroy($imgCanvas);
        imagedestroy($imgOptotype);
        
        $this->SettingOnRowAndColunm($x,$y);
        
    }
    
    function SettingOnRowAndColunm ($x, $y){
        if ($y){
            $this->lineSpace = $this->lineSpace + 100;
            $this->columnSpace = 0;
        }    
        
        if ($x){
            $this->columnSpace = $this->columnSpace + 150;
        }
    }
}
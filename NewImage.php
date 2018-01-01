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
    function resizeImage($heigh, $width, $name){
        
        $imageLienzo="C:/xampp/htdocs/NuevaImagen/Imagenes/Lienzo.png";
 
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
        /*
        * si proporcion horizontal*alto mayor que el alto maximo,
        * alto final es alto por la proporcion horizontal
        * es decir, le quitamos al ancho, la misma proporcion que
        * le quitamos al alto
        *
        */
        elseif (($x_ratio * $heighLienzo) < $maxHeigh){
            $finalHeigh = ceil($x_ratio * $heighLienzo);
            $finalWidth = $maxWidth;
        }
        /*
        * Igual que antes pero a la inversa
        */
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
        imagepng($newImage,"OptometricCard/".$name.".png");

    }

    /*sra la encargada de insertar cada optotipo dentro de la carta*/
    function newOptometricCard($iteractionElements, $testCode){
        
        echo 'llamado a función para incluir optotipos'."\n";
        echo $testCode."\n";
        echo count($iteractionElements);
        
    }
}
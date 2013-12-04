<?php

class Formatter extends CApplicationComponent{   
   //***************************************************************************
   // Initialization
   //***************************************************************************

   /**
    * Init method for the application component mode.
    */
   
    public function init() {}


    public function format_decimal($num,$decimales=3)
    {        
        $english_format_number2 = number_format($num, 10, ',', '.');
        $numtext=strval($english_format_number2);
        $position = strpos($numtext, ',');
        $numsub = substr($numtext,0,$position+$decimales); 
        return $numsub;
    }

    
    public function format_date($fecha, $tipo=NULL) {

        if($tipo==NULL){
            
            $arrayFecha = explode("/", $fecha);

            if (strlen($arrayFecha[0]) == 1) {
                $arrayFecha[0] = "0" . $arrayFecha[0];
            }
            if (strlen($arrayFecha[1]) == 1) {
                $arrayFecha[1] = "0" . $arrayFecha[1];
            }

            $fechaFinal = $arrayFecha[2] . "-" . $arrayFecha[0] . "-" . $arrayFecha[1];
            return $fechaFinal;
        }
        
        if($tipo=='etelixPeru'){
            
            $arrayFecha = explode(" ", $fecha);
            return $arrayFecha[0];
            
        }
        
    }

}

?>

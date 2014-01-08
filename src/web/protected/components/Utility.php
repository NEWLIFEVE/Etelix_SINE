<?php
/**
 * @version 1.3
 * @package components
 */
Class Utility
{
    /**
     * @param $fecha date la fecha a formatear
     * @return $fechaFinal string fecha formateada para base de datos
     */
	public static function formatDate($fecha=null)
	{
        if($fecha==NULL)
        {
        	$fechaFinal=date("Y-m-d");
        }
        else
        {   
            if(strpos($fecha,"-"))
            {
                $arrayFecha=explode("-", $fecha);
            }
            elseif(strpos($fecha,"/"))
            {
                $arrayFecha=explode("/", $fecha);
            }
            if(strlen($arrayFecha[0])==1)
            {
                $arrayFecha[0]="0".$arrayFecha[0];
            }
            if(strlen($arrayFecha[1])==1)
            {
                $arrayFecha[1]="0".$arrayFecha[1];
            }
            $fechaFinal=strval($arrayFecha[2]."-".$arrayFecha[0]."-".$arrayFecha[1]);
        }
        return $fechaFinal;
    }

    /**
     *
     */
    public static function notNull($valor)
    {
        if($valor===null)
            $valor="0.00";

        return $valor;
    }
    
    /**
     * retorna NULL si la variable viene vacia...
     * @access public
     * @param type $var Variable de entrada.
     * @return type Null si la vaiable de entrada es vacia, de lo contrario, la variable.
     */
    public static function snull($var)
    {
        if($var==NULL || $var=='')
        {
            return NULL;
        }else{
            return $var;
        }
    }
    
    /**
    * @param $hora time la hora a formatear
    * @return $horaMod string hora formateada para base de datos
    */	
    public static function ChangeTime($hora)
	{
		$doce = 12;
		if($hora[1] == ':')
		{
			if($hora[5] == 'A')
			{
				$horaMod = '0'.substr($hora, -7, 4).':00';
			}
			else
			{
				$horaMod = substr($hora, -7, 2)+$doce.substr($hora, -6, 3).':00';
			}
		}
		else if($hora[1] == '2')
		{
			if($hora[6] == 'A')
			{
				$horaMod = '00'.substr($hora, -6, 3).':00';
			}
			else
			{
				$horaMod = substr($hora, -8, 5).':00';
			}
		}
		else
		{
			if($hora[6] == 'A')
			{
				$horaMod = substr($hora, -8, 5).':00';
			}
			else
			{
				$horaMod = substr($hora, -8, 2)+$doce.substr($hora, -6, 3).':00';
			}
		}
		return $horaMod;
	}

    /**
    * @param $var time la hora a formatear
    * @return $horaAmPm string hora formateada para base de datos
    */	
    public static function ChangeTimeAmPm($var)
	{
        $hora = strtotime($var);
        //substr
        $horaAmPm = date("h:i:s A",$hora); 
        return $horaAmPm;
    }
        
	/*
	* Encargada de cambiar las comas recibidas por un punto.
	*/
    public static function ComaPorPunto($monto) 
    {
//            for ($i = 0; $i < strlen($monto); $i++) {
//                if ($monto{$i} == ',' || $monto{$i} == '%2C') {
//                    $monto{$i} = '.';
//                }
//                return $monto;
//            }
        $monto = str_replace(",",".",$monto);
        return $monto;
    }
    /**
     * resulve el formato especifico para repoprtes en sine, soporta cualquier formato, no obstante se le pasarian por ahora
     * "F j - Y": para issiu date y due date
     * "m-F-y": para la fecha que va en caracteristicas
     * @param type $fecha
     * @param type $formato
     * @return type
     */
    public static function formatDateSINE($fecha,$formato)
    {
        $fecha_actual =strtotime($fecha);
        $valid = date($formato,$fecha_actual );
        return $valid;
    }
}
?>
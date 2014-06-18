<?php
/**
 * clase con metodos estaticos para administrar las fechas
 * @version 0.6.1
 * @package components
 */
class DateManagement
{
	/**
	 * Metodo estatico encargado de restar o sumar dias a una fecha
	 * @access public
	 * @static
	 * @param string $days es la cantidad de dias a sumar o restar pero debe incluir el + o - para la operación
	 * ejemplo para sumar un dia seria "+1" o restar dos "-2"
	 * @param date $date es la fecha formato yyyy-mm-dd
	 * @return date la fecha nueva formato yyyy-mm-dd
	 */
	public static function calculateDate($days,$date)
	{
		$newDate=strtotime($days.' day',strtotime($date));
		return date('Y-m-d',$newDate);
	}
        /**
         * se encarga de sumar o restar semanas
         * @param type $week
         * @param type $date
         * @return type
         */
	public static function calculateWeek($week,$date)
	{
		$newDate=strtotime($week.' week',strtotime($date));
		return date('Y-m-d',$newDate);
	}

	/**
	 * Metodo que lleva a dia uno cualquier fecha pasada como parametro
	 * @access public
	 * @static
	 * @param date $date es la fecha en formato yyyy-mm-dd
	 * @return date yyyy-mm-dd
	 */
	public static function getDayOne($date)
	{
		$arrayDate=explode("-",$date);
		return $arrayDate[0]."-".$arrayDate[1]."-01";
	}

	/**
     * Retorna el numero del dia de la semana de una fecha
     * @return int
     */
    public static function getDayNumberWeek($date)
    {
        $date=strtotime($date);
        return (int)date('N',$date);
    }
    /**
     * retorna el numero de semana de la fecha que se le pase
     * @param type $date
     * @return null
     */
    public static function getNumberWeek($date)
    {
        if($date!=null)
             return date('W', strtotime($date));
        else
            return NULL;
    }
    /**
     * retorna dependiendo de '$typeDay' una fecha con el primer o el ultimo dia de la semana que se le pasa por '$date'
     * @param type $date
     * @param type $typeDay
     * @return type
     */
    public static function firstOrLastDayWeek($date,$typeDay)
    {
        if($date!=NULL){
            $month=date("m",strtotime($date));
            $day=date("d",strtotime($date));
            $diaSemana=date("N",strtotime($date));
            $year=date("Y",strtotime($date));
            if($typeDay=="first")                    # A la fecha recibida, le restamos el dia de la semana y se obtiene una fecha con el "d"igual a lunes
                 return date("Y-m-d",mktime(0,0,0,$month,$day-$diaSemana+1,$year));
              else                                   # A la fecha recibida, le sumamos el dia de la semana menos siete y obtendremos el  "d"igual a domingo
                 return date("Y-m-d",mktime(0,0,0,$month,$day+(7-$diaSemana),$year));  
        }
    }

    /**
     * Retorna el nombre del dia de la semana de una fecha
     * @return string
     */
    public static function getNameDayOfWeek($date)
    {
        $date=strtotime($date);
        return date('D',$date);
    }
    
    /**
     *
     */
    public static function getMonday($date)
    {
        $num=self::getDayNumberWeek($date);
        $num=$num-1;
        return self::calculateDate('-'.$num,$date);
    }
    /**
     * Recibe una fecha y devuelve un array con el 'year', 'month', 'day'
     * @access public
     * @static
     * @param date $date
     * @return array
     */
    public static function separatesDate($date)
    {
        $array=explode("-", $date);
        $complete=array(
            'year'=>$array[0],
            'month'=>$array[1],
            'day'=>$array[2]
            );
        return $complete;
    }

    /** 
     * Retorna la cantidad de dias de un mes
     * @access protected
     * @static
     * @param date $fecha la fecha que se dira la cantidad de dias que tiene el mes
     * @return int 
     */
    public static function howManyDays($date)
    {
        if(strpos($date,'-'))
        {
            $arrayFecha=explode('-',$date);
        }
        if(is_callable('cal_days_in_month'))
        {
            $num=cal_days_in_month(CAL_GREGORIAN, $arrayFecha[1], $arrayFecha[0]);
        }
        else
        {
            $num=date('d',mktime(0,0,0,$arrayFecha[1]+1,0,$arrayFecha[0]));
        }
        return (int)$num;
    }

    /**
     * Retorna la cantidad de dias que existe de una fecha a otra
     * @access protected
     * @static
     * @param date $startDate la fecha menor a consultar
     * @param date $endDate la fecha mayor del rango a consultar
     * @return int con el numero de dias entre ambas fechas
     */
    public static function howManyDaysBetween($startDate,$endDate)
    {
        $i=strtotime($startDate);
        $f=strtotime($endDate);
        $cant=$f-$i;
        return $cant/(60*60*24);
    }

    /**
     * Recibe como parametros una fecha y la cantidad de dias atras que debe para calcular una fecha
     * @access public
     * @static
     * @param date $date
     * @param int $num
     * @return date
     */
    public static function getFirstDayPeriod($date,$num)
    {
        $day=self::getDayNumberWeek($date);
        while($num!=$day)
        {
            $date=self::calculateDate('-1',$date);
            $day=self::getDayNumberWeek($date);
        }
        return $date;
    }

    /**
     * Recibe una fecha y retorna un array con la fecha inicio y fin de un mes menos, esta funcion trabaja con strtotime
     * recibe un segundo parametro que seria el numero de meses a restar o sumar, incluyendo el + ó -
     * @access public
     * @param date $date
     * @param string $month
     * @return array
     */
    public static function leastOneMonth($date,$month=null)
    {
        if($date==null) $date=date('Y-m-d');
        if($month===null) $month="-1";
        $arrayDate['firstday']=date('Y-m-d',strtotime($month.' month',strtotime(self::getDayOne($date))));
        $array=explode('-',$arrayDate['firstday']);
        $arrayDate['lastday']=$array[0]."-".$array[1]."-".self::howManyDays($arrayDate['firstday']);
        return $arrayDate;
    }
    public static function dateDiff($start, $end)
    {
       return ((strtotime($end)-strtotime($start))/86400);
    }
}
?>
<?php
/**
* @package components
*/
class Provisions extends CApplicationComponent
{
	/**
	 * @var date
	 * fecha que sera ejecutada en las busquedas de base de datos
	 */
	public $date;

	/**
	 * @var array
	 * contiene un array con los registros obtenidos de base de datos
	 */
	public $result;

	public function init() 
    {
       
    }

    /**
     * @access public
     * metodo encargado de correr las provisiones
     */
    public function run()
    {
    	$this->getDate();
    	echo $this->date;
    }
	/**
	 * @access public
	 * genera la fecha para las consultas a base de datos
	 */
	public function getDate()
	{
		$yesterday=strtotime('-1 day',strtotime(date('Y-m-d')));
		$this->date=date('Y-m-d',$yesterday);
	}

	/**
	 * @access public
	 * @param boolean $type true=facturas enviadas, false =facturas recibidas
	 * obtiene los datos para generar las inserciones respectivas
	 */
	/*public function getData($type=true)
	{
		$carrier="b.id_carrier_supplier";
		if($type) $carrier="b.id_carrier_customer";

		$margin="b.revenue";
		if($type) $margin="b.cost";

		$sql="SELECT b.date_balance AS date, c.id AS id, c.name AS carrier, SUM(b.minutes) AS minutes, SUM({$margin}) AS margin
			  FROM balance b, carrier c
			  WHERE date_balance='{$this->date}' AND {$carrier}=c.id AND b.id_destination IS NULL AND b.id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND b.id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
			  GROUP BY c.id, c.name, b.date_balance
			  ORDER BY margin DESC";
		
		$this->result=$this->connect->query($sql);
	}*/

	/**
	 * @access public
	 * @param boolean $type true=facturas enviadas, false=facturas recibidas
	 * Genera las provisiones de trafico con la data almacenada en $result
	 */
	/*public function generateTrafficProvision($type=true)
	{
		$values="";
		$type_document=$this->getIdTypeDocument("Provision Trafico Recibida");
		if($type) $type_document=$this->getIdTypeDocument("Provision Trafico Enviada");

		var_dump($type_document);*/
		/*$currency="1";
		$num=$this->result->rowCount();
		foreach ($this->result as $key => $factura)
		{
			$values.="(";
			$values.="'".$factura['date']."',";
			$values.="'".$factura['date']."',";
			$values.=$factura['minutes'].",";
			$values.=$factura['margin'].",";
			$values.=$type_document.",";
			$values.=$factura['id'].",";
			$values.=$currency.",";
			$values.="1";
			$values.=")";
			if($key<$num-1) $values.=",";
		}

		$sql="INSERT INTO accounting_document(issue_date, from_date, minutes, amount, id_type_accounting_document, id_carrier, id_currency, confirm)
			  VALUES ".$values;
		echo $sql;*/
	/*}*/

	/**
	 * Obtiene el id del tipo de documento
	 */
	/*private function getIdTypeDocument($name)
	{
		$sql="SELECT id FROM type_accounting_document WHERE name='{$name}'";
		$result=$this->connect->query($sql);
		foreach ($result as $key => $value)
		{
			return $value['id'];
		}
	}*/

	/**
	 * Obtiene el id del tipo de documento
	 */
/*	private function getIdTypeDocument($name)
	{
		$sql="SELECT id FROM type_accounting_document WHERE name='{$name}'";
		$result=$this->connect->query($sql);
		foreach ($result as $key => $value)
		{
			return $value['id'];
		}
	}*/
}
?>
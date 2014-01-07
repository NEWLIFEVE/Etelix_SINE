<?php
/**
 * @package components
 * @version 1.0
 */
class Provisions extends CApplicationComponent
{
	/**
	 * @var date
	 * fecha que sera ejecutada en las busquedas de base de datos
	 */
	public $date;

	/**
	 *
	 */
	public $invoicesSent;

	/**
	 *
	 */
	public $invoicesReceived;

	/**
	 *
	 */
	public function init() 
    {
       
    }

    /**
     * @access public
     * metodo encargado de correr las provisiones
     */
    public function run($dateSet=null)
    {
    	$this->getDate($dateSet);
    	//Obtengo la data de clientes
    	$this->getData(true);
    	//Obtengo la data de proveedores
    	$this->getData(false);
    	//Genero las provisiones de trafico y facturas enviadas
    	$this->generateTrafficProvision(true);
    	//Genero las provisions de trafico y facturas recibidas
    	$this->generateTrafficProvision(false);
    }

	/**
	 * @access public
	 * genera la fecha para las consultas a base de datos
	 */
	public function getDate($dateSet)
	{
		if($dateSet===null) $date=date('Y-m-d');
		else $date=$dateSet;

		$this->date=DateManagement::calculateDate('-1',$date);
	}

	/**
	 * @access public
	 * @param boolean $type true=facturas enviadas, false =facturas recibidas
	 * obtiene los datos para generar las inserciones respectivas
	 * nota: se usa el atributo id del balance, pero en realidad es el id del carrier de esos balances
	 */
	public function getData($type=true)
	{
		$data=array('title'=>'proveedor','id'=>'b.id_carrier_supplier','margin'=>'b.revenue','variable'=>'invoicesReceived');
		if($type) $data=array('title'=>'proveedor','id'=>'b.id_carrier_customer','margin'=>'b.cost','variable'=>'invoicesSent');

		$sql="SELECT c.id AS id, b.date_balance AS date_balance, c.name AS {$data['title']}, SUM(b.minutes) AS minutes, SUM({$data['margin']}) AS margin
			  FROM balance b, carrier c
			  WHERE date_balance='{$this->date}' AND {$data['id']}=c.id AND b.id_destination IS NULL AND b.id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND b.id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
			  GROUP BY c.id, c.name, b.date_balance
			  ORDER BY margin DESC";
		$this->$data['variable']=Balance::model()->findAllBySql($sql);
	}

	/**
	 * @access public
	 * @param boolean $type true=facturas enviadas, false=facturas recibidas
	 * Genera las provisiones de trafico con la data almacenada en $result
	 */
	public function generateTrafficProvision($type=true)
	{
		$values="";

		$data=array('variable'=>'invoicesReceived','condition'=>"name='Provision Trafico Recibida'");
		if($type) $data=array('variable'=>'invoicesSent','condition'=>"name='Provision Trafico Enviada'");

		$type_document=TypeAccountingDocument::model()->find($data['condition'])->id;

		$currency=Currency::model()->find("name='$'")->id;

		$num=count($this->$data['variable']);
		foreach ($this->$data['variable'] as $key => $factura)
		{
			$this->generateInvoiceProvision($factura->id,$type);
			$values.="(";
			$values.="'".$factura->date_balance."',";
			$values.="'".$factura->date_balance."',";
			$values.=$factura->minutes.",";
			$values.=$factura->margin.",";
			$values.=$type_document.",";
			$values.=$factura->id.",";
			$values.=$currency.",";
			$values.="1";
			$values.=")";
			if($key<$num-1) $values.=",";
		}

		$sql="INSERT INTO accounting_document(issue_date, from_date, minutes, amount, id_type_accounting_document, id_carrier, id_currency, confirm)
			  VALUES ".$values;
		$command = Yii::app()->db->createCommand($sql);
        if($command->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
	}

	/**
	 * @access public
	 * @param boolean $type true=facturas enviadas, false=facturas recibidas
	 */
	public function generateInvoiceProvision($idCarrier,$type)
	{
		$data=array('variable'=>'invoicesReceived','condition'=>"name='Provision Trafico Recibida'",'invoice'=>"name='Provision Factura Recibida'");
		if($type) $data=array('variable'=>'invoicesSent','condition'=>"name='Provision Trafico Enviada'",'invoice'=>"name='Provision Factura Enviada'");

		$typeProvisions=TypeAccountingDocument::model()->find($data['condition'])->id;
		$typeInvoice=TypeAccountingDocument::model()->find($data['invoice'])->id;

		$currency=Currency::model()->find("name='$'")->id;

		$sql="SELECT tp.*
			  FROM termino_pago tp, contrato_termino_pago ctp, contrato con
			  WHERE tp.id=ctp.id_termino_pago AND ctp.id_contrato=con.id AND con.id_carrier={$idCarrier} AND con.end_date IS NULL";

		$TerminoPago=TerminoPago::model()->findBySql($sql);
		if($TerminoPago!==null)
		{
			$tempdate=$firstDay=$trafficProvisions=$sql=null;
			switch (Reportes::define_tp($TerminoPago->name)['periodo'])
			{
				case 30:
					$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-".DateManagement::howManyDays($this->date);
					if($tempdate===$this->date)
					{
						$firstDay=DateManagement::getDayOne($this->date);
						$this->insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
					}
					break;

				case 15:
					$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-15";
					if($tempdate===$this->date)
					{
						$firstDay=DateManagement::getDayOne($this->date);
						$this->insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
					}
					$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-".DateManagement::howManyDays($this->date);
					if($tempdate===$this->date)
					{
						$firstDay=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-16";
						$this->insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
					}
					break;

				case 7:
					$num=DateManagement::getDayNumberWeek($this->date);
					switch ($num)
					{
						case 1:
							$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-".DateManagement::howManyDays($this->date);
							if($tempdate===$this->date)
							{
								$firstDay=$this->date;
								$this->insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
							}
							break;
						case 2:
						case 3:
						case 4:
						case 5:
						case 6:
						case 7:
							$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-".DateManagement::howManyDays($this->date);
							if($tempdate===$this->date)
							{
								$firstDay=DateManagement::calculateDate('-'.$num,date('Y-m-d'));
								$this->insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
							}
							break;
						case 7:
							$firstDayMonth=DateManagement::getDayOne($this->date);
							$cant=DateManagement::howManyDaysBetween($firstDayMonth,$this->date);
							if($cant>=7)
							{
								$firstDay=DateManagement::calculateDate('-'.$num,date('Y-m-d'));
								$this->insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
							}
							else
							{
								$firstDay=DateManagement::getDayOne($this->date);
								$this->insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
							}
							break;
					}
					break;
			}
		}
	}

	/**
	 * Trae todas las provisiones de trafico en el tiempo solicitado y del carrier pasado como parametro
	 * @access public
	 * @param date $stratDate
	 * @param date $endDate
	 * @param int $idCarrier
	 * @param int $idDocument
	 * @return CActiveRecord
	 */
	public function getTrafficProvision($startDate,$endDate,$idCarrier,$idDocument)
	{
		$sql="SELECT SUM(minutes) AS minutes, SUM(amount) AS amount FROM accounting_document WHERE from_date>='{$startDate}' AND from_date<='{$endDate}' AND id_type_accounting_document=$idDocument AND id_carrier=$idCarrier";
		return AccountingDocument::model()->findBySql($sql);
	}

	/**
	 *
	 */
	public function insertInvoiceProvision($startDate,$endDate,$idCarrier,$typeProvisions)
	{
		$trafficProvisions=$this->getTrafficProvision($startDate,$endDate,$idCarrier,$typeProvisions);
		$sql="INSERT INTO accounting_document(issue_date, from_date, to_date, minutes, amount, id_type_accounting_document, id_carrier, id_currency, confirm)
			  VALUES ('{$endDate}','$startDate','$endDate',{$trafficProvisions->minutes}, {$trafficProvisions->amount},{$typeInvoice},$idCarrier,$currency, 1)";
		$command = Yii::app()->db->createCommand($sql);
		if($command->execute())
		{
		    return true;
		}
		else
		{
		    return false;
		}
	}
}
?>
<?php
/**
 * @package components
 * @version 2.0
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

	public $numTrafficSend;
	public $numTrafficReceived;
	public $numInvoicesSend;
	public $numInvoicesReceived;

	/**
	 *
	 */
	public function init() 
    {
       $this->numTrafficSend=$this->numTrafficReceived=0;
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
    	//
    	$this->runInvoiceProvision(true);
    	//
    	$this->runInvoiceProvision(false);

    	$this->sendNotification();
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
		$data=array('title'=>'proveedor','id'=>'id_carrier_supplier','margin'=>'CASE WHEN ABS(SUM(revenue-margin))<ABS(SUM(cost)) THEN SUM(revenue-margin) ELSE SUM(cost) END','variable'=>'invoicesReceived');
		if($type) $data=array('title'=>'cliente','id'=>'id_carrier_customer','margin'=>'CASE WHEN ABS(SUM(cost+margin))<ABS(SUM(revenue)) THEN SUM(cost+margin) ELSE SUM(revenue) END','variable'=>'invoicesSent');

		$sql="SELECT {$data['id']} AS id, date_balance, SUM(minutes) AS minutes, {$data['margin']} AS margin
			  FROM balance
			  WHERE date_balance='{$this->date}' AND id_destination IS NULL AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
			  GROUP BY {$data['id']}, date_balance
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

		$data=array('variable'=>'invoicesReceived','condition'=>"name='Provision Trafico Recibida'",'num'=>"numTrafficReceived");
		if($type) $data=array('variable'=>'invoicesSent','condition'=>"name='Provision Trafico Enviada'",'num'=>"numTrafficSend");

		$type_document=TypeAccountingDocument::model()->find($data['condition'])->id;

		$currency=Currency::model()->find("name='$'")->id;

		$this->$data['num']=$num=count($this->$data['variable']);
		foreach ($this->$data['variable'] as $key => $factura)
		{
			if($factura->margin!=0)
			{
				if($key>0 && $key<$num) $values.=",";
				$values.="(";
				$values.="'".$factura->date_balance."',";
				$values.="'".$factura->date_balance."',";
				$values.="'".$factura->date_balance."',";
				$values.=$factura->minutes.",";
				$values.=$factura->margin.",";
				$values.=$type_document.",";
				$values.=$factura->id.",";
				$values.=$currency.",";
				$values.="1";
				$values.=")";
			}
		}
		$sql="INSERT INTO accounting_document(issue_date, from_date, to_date, minutes, amount, id_type_accounting_document, id_carrier, id_currency, confirm)
			  VALUES ".$values;
		if($num>0)
		{
			$command = Yii::app()->db->createCommand($sql);
	        if($command->execute())
	        {
				var_dump("Se generaron ".$this->$data['num']." ".$data['condition']."s para el dia ".$this->date);
	        }
	        else
	        {
	            return false;
	        }
	    }
	}

	/**
	 * Se encargad de generar las provisiones de facturas
	 * @param boolean $type true=clientes, false=proveedores
	 */
	public function runInvoiceProvision($type=true)
	{
		$model=Carrier::model()->findAll();
		if($type)
		{
			foreach ($model as $key => $carrier)
			{
				$this->_generateInvoiceProvisionCustomer($carrier->id);
			}
			var_dump("Se generaron ".$this->numInvoicesSend." facturas enviadas para el dia ".$this->date);
			$this->numTrafficSend=$this->numTrafficReceived=0;
		}
		else
		{
			foreach ($model as $key => $carrier)
			{
				$this->_generateInvoiceProvisionSupplier($carrier->id);
			}
			var_dump("Se generaron ".$this->numInvoicesReceived." facturas recibidas para el dia ".$this->date);
			$this->numTrafficSend=$this->numTrafficReceived=0;
		}
	}

	/**
	 * Genera la provision de factura de los clientes
	 * @access private
	 * @param int $idCarrier
	 */
	private function _generateInvoiceProvisionCustomer($idCarrier)
	{
		$data=array('variable'=>'invoicesSent','condition'=>"name='Provision Trafico Enviada'",'invoice'=>"name='Provision Factura Enviada'",'real'=>"name='Factura Enviada'");
		$typeProvisions['traffic']=TypeAccountingDocument::model()->find($data['condition'])->id;
		$typeProvisions['invoice']=TypeAccountingDocument::model()->find($data['invoice'])->id;
		$typeProvisions['real']=TypeAccountingDocument::model()->find($data['real'])->id;
		$typeProvisions['currency']=Currency::model()->find("name='$'")->id;
		$typeProvisions['num']="numInvoicesSend";

		$sql="SELECT tp.*
			  FROM termino_pago tp,
			  	   (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago
			  	   	FROM contrato_termino_pago
			  	   	WHERE start_date<='{$this->date}') ctp,
				   (SELECT id, sign_date, production_date, id_carrier, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date
				   	FROM contrato
				   	WHERE sign_date<='{$this->date}') con
			  WHERE tp.id=ctp.id_termino_pago AND ctp.id_contrato=con.id AND con.id_carrier={$idCarrier} AND con.end_date>'{$this->date}' AND ctp.end_date>'{$this->date}'";

		$TerminoPago=TerminoPago::model()->findBySql($sql);
		if($TerminoPago!==null)
		{
			$tempdate=$firstDay=$trafficProvisions=$sql=null;
			switch ($TerminoPago->period)
			{
				case 30:
					$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-".DateManagement::howManyDays($this->date);
					if($tempdate===$this->date)
					{
						$firstDay=DateManagement::getDayOne($this->date);
						$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
					}
					break;

				case 15:
					$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-15";
					if($tempdate===$this->date)
					{
						$firstDay=DateManagement::getDayOne($this->date);
						$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
					}
					$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-".DateManagement::howManyDays($this->date);
					if($tempdate===$this->date)
					{
						$firstDay=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-16";
						$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
					}
					break;

				case 7:
					$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-".DateManagement::howManyDays($this->date);
					if($tempdate===$this->date)
					{
						$firstDay=DateManagement::getMonday($this->date);
						$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
					}
					$num=DateManagement::getDayNumberWeek($this->date);
					if($num==7)
					{
						$monday=DateManagement::getMonday($this->date);
						if(DateManagement::separatesDate($monday)['month']==DateManagement::separatesDate($this->date)['month'])
						{
							$this->_insertInvoiceProvision($monday,$this->date,$idCarrier,$typeProvisions);
						}
						else
						{
							$firstDay=DateManagement::getDayOne($this->date);
							$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
						}
					}
			}
		}
	}

	/**
	 * Genera las provisiones de proveedores
	 * @access private
	 * @param int $idCarrier
	 */
	private function _generateInvoiceProvisionSupplier($idCarrier)
	{
		$data=array('variable'=>'invoicesReceived','condition'=>"name='Provision Trafico Recibida'",'invoice'=>"name='Provision Factura Recibida'",'real'=>"name='Factura Recibida'");

		$typeProvisions['traffic']=TypeAccountingDocument::model()->find($data['condition'])->id;
		$typeProvisions['invoice']=TypeAccountingDocument::model()->find($data['invoice'])->id;
		$typeProvisions['real']=TypeAccountingDocument::model()->find($data['real'])->id;
		$typeProvisions['currency']=Currency::model()->find("name='$'")->id;
		$typeProvisions['num']="numInvoicesReceived";

		$sql="SELECT ctps.id, ctps.start_date, ctps.end_date, ctps.id_contrato, ctps.month_break, ctps.first_day, tp.period AS payment_term, fp.name AS billing_period
			  FROM (SELECT id, sign_date, production_date, id_carrier, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date
			  		FROM contrato
			  		WHERE sign_date<='{$this->date}') con,
				   (SELECT id, start_date, CASE WHEN end_date IS NULL THEN current_date ELSE end_date END AS end_date, id_contrato, id_termino_pago_supplier, month_break, first_day, id_fact_period
				   	FROM contrato_termino_pago_supplier
				   	WHERE start_date<='{$this->date}') ctps,
				   termino_pago tp,
				   fact_period fp
			  WHERE con.id_carrier={$idCarrier} AND con.id=ctps.id_contrato AND tp.id=ctps.id_termino_pago_supplier AND fp.id=ctps.id_fact_period AND con.end_date>'{$this->date}' AND ctps.end_date>'{$this->date}'";

		$TerminoPago=TerminoPago::model()->findBySql($sql);
		$carrier=Carrier::getName($idCarrier);
		if($carrier=="BSG-SHARE")
		{
			$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-27";
			if($tempdate===$this->date)
			{
				$firstDay=DateManagement::getDayOne($this->date);
				$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
				//var_dump("Generando factura de BSH SHARE del ".$firstDay." al ".$this->date);
			}
			$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-".DateManagement::howManyDays($this->date);
			if($tempdate===$this->date)
			{
				$firstDay=$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-28";
				$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
				//var_dump("Generando factura de BSH SHARE del ".$firstDay." al ".$this->date);
			}
		}
		else
		{
			if($TerminoPago!==null)
			{
				$tempdate=$firstDay=$trafficProvisions=null;
				switch ($TerminoPago->payment_term)
				{
					case 30:
						$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-".DateManagement::howManyDays($this->date);
						if($tempdate===$this->date)
						{
							$firstDay=DateManagement::getDayOne($this->date);
							$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
							//var_dump("Generando factura mensual del ".$firstDay." al ".$this->date);
						}
						break;
					case 15:
						if($TerminoPago->billing_period=="Regular(1-15/16-ULT)")
						{
							$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-15";
							if($tempdate===$this->date)
							{
								$firstDay=DateManagement::getDayOne($this->date);
								$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
								//var_dump("Generando factura de quincenal regular del ".$firstDay." al ".$this->date);
							}
							$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-".DateManagement::howManyDays($this->date);
							if($tempdate===$this->date)
							{
								$firstDay=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-16";
								$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
								//var_dump("Generando factura de quincenal regular del ".$firstDay." al ".$this->date);
							}
						}
						elseif($TerminoPago->billing_period=="Dia Antes (ULT-14/15-PEN)")
						{
							$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-14";
							if($tempdate===$this->date)
							{
								$firstDay=DateManagement::leastOneMonth($this->date)['lastday'];
								$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
								//var_dump("Generando factura de quincenal dia anterior del ".$firstDay." al ".$this->date);
							}
							$ultimo=DateManagement::howManyDays($this->date)-1;
							$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-".$ultimo;
							if($tempdate===$this->date)
							{
								$firstDay=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-15";
								$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
								//var_dump("Generando factura de quincenal dia anterior del ".$firstDay." al ".$this->date);
							}
						}
						break;
					case 7:
						//si es por dia del mes
						if($TerminoPago->billing_period=="Dia Mes(1-7/8-14/15-21/22-ULT)")
						{
							$last=DateManagement::howManyDays($this->date);
							switch (DateManagement::separatesDate($this->date)['day'])
							{
								case 7:
								case 14:
								case 21:
									$firstDay=DateManagement::calculateDate('-6',$this->date);
									$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
									//var_dump("Generando factura de 7/7 dia de mes del ".$firstDay." al ".$this->date);
									break;
								case $last:
									$firstDay=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-22";
									$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
									//var_dump("Generando factura de 7/7 dia de mes del ".$firstDay." al ".$this->date);
									break;
							}
						}
						if($TerminoPago->billing_period=="Dia Semana(L/M/M/J/V/S/D)")
						{
							//Obtengo el numero del dia que seria el fin del periodo
							$end=$this->_getEndPeriod($TerminoPago->first_day);
							//primero verifico si pica mes
							if($TerminoPago->month_break===1)
							{
								//Cuando es fin de mes
								$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-".DateManagement::howManyDays($this->date);
								if($tempdate==$this->date)
								{
									$firstDay=DateManagement::getFirstDayPeriod($this->date,$TerminoPago->first_day);
									$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
									//var_dump("Generando factura de 7/7 dia de semana que pican mes(fin de mes) del ".$firstDay." al ".$this->date);
									break;
								}
								//cuando esta en el fin del periodo de facturacion
								if($end==DateManagement::getDayNumberWeek($this->date))
								{
									$firstDay=DateManagement::getFirstDayPeriod($this->date,$TerminoPago->first_day);
									if(DateManagement::separatesDate($firstDay)['month']==DateManagement::separatesDate($this->date)['month'])
									{
										$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
										//var_dump("Generando factura de 7/7 dia de semana que pican mes(durante el mes) del ".$firstDay." al ".$this->date);
										break;
									}
									else
									{
										$firstDay=DateManagement::getDayOne($this->date);
										$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
										//var_dump("Generando factura de 7/7 dia de semana que pican mes(inicio de mes) del ".$firstDay." al ".$this->date);
										break;
									}
								}
							}
							else
							{
								if($end===DateManagement::getDayNumberWeek($this->date))
								{
									$firstDay=DateManagement::getFirstDayPeriod($this->date,$TerminoPago->first_day);
									$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
									//var_dump("Generando factura de 7/7 dia de semana que no pican mes del ".$firstDay." al ".$this->date);
									break;
								}
							}
						}
						break;
				}
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
		$sql="SELECT SUM(minutes) AS minutes, SUM(amount) AS amount FROM accounting_document WHERE from_date>='{$startDate}' AND from_date<='{$endDate}' AND id_type_accounting_document={$idDocument} AND id_carrier={$idCarrier}";
		return AccountingDocumentProvisions::model()->findBySql($sql);
	}

	/**
	 * Metodo encargado de cambiar el estado de una provision de trafico si se le generó una provision de factura
	 * @access private
	 * @param date $startDate
	 * @param date $endDate
	 * @param int $idCarrier
	 * @param int $idDocument
	 * @return boolean
	 */
	private function _changeStatusProvision($startDate,$endDate,$idCarrier,$idDocument)
	{
		$model=AccountingDocumentProvisions::model()->find('from_date>=:start AND from_date<=:end AND id_carrier=:id AND id_type_accounting_document=:type', array(':start'=>$startDate,':end'=>$endDate,':id'=>$idCarrier,':type'=>$idDocument));
		if($model->id!=null)
		{
			$model->confirm=-1;
			if($model->save())
	        {
	        	return true;
	        }
		}
        return false;
	}

	/**
	 * Cambia el estado de una provision de factura si ya dicha provision tiene una factura cargada
	 * @access private
	 * @param date $startDate
	 * @param date $endDate
	 * @param int $idCarrier
	 * @param array $data
	 * @return boolean
	 */
	public function _changeStatusInvoiceProvision($startDate,$endDate,$idCarrier,$data)
	{
		$invoice=AccountingDocumentProvisions::model()->find('from_date>=:start AND to_date<=:end AND id_carrier=:id AND id_type_accounting_document=:type', array(':start'=>$startDate,':end'=>$endDate,':id'=>$idCarrier,':type'=>$data['real']));
		if(isset($invoice->id) && $invoice->id!=null)
		{
			$provision=AccountingDocumentProvisions::model()->find('from_date>=:start AND to_date<=:end AND id_carrier=:id AND id_type_accounting_document=:type', array(':start'=>$startDate,':end'=>$endDate,':id'=>$idCarrier,':type'=>$data['invoice']));
			$provision->confirm=-1;
			if($provision->save())
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Recibe cuatro parametros, fecha de inicio y fecha fin, el id del carrier, por ultimo el id del tipo de provision
	 * genera un registro en base de datos con el tipo de provision pasada como parametro, incluyendo las provisiones de 
	 * de trafico que esten dentro de las fechas asignadas
	 * @access private
	 * @param date $startDate
	 * @param date $endDate
	 * @param int $idCarrier
	 * @param int $typeProvisions
	 * @return void
	 */
	private function _insertInvoiceProvision($startDate,$endDate,$idCarrier,$typeProvisions)
	{
		$trafficProvisions=$this->getTrafficProvision($startDate,$endDate,$idCarrier,$typeProvisions['traffic']);
		if($trafficProvisions->amount!=null)
		{
			$doccument=new AccountingDocumentProvisions;
			$doccument->issue_date=$endDate;
			$doccument->from_date=$startDate;
			$doccument->to_date=$endDate;
			$doccument->minutes=$trafficProvisions->minutes;
			$doccument->amount=$trafficProvisions->amount;
			$doccument->id_type_accounting_document=$typeProvisions['invoice'];
			$doccument->id_carrier=$idCarrier;
			$doccument->id_currency=$typeProvisions['currency'];
			$doccument->confirm=1;
			if($doccument->save())
			{
				$this->_changeStatusProvision($startDate,$endDate,$idCarrier,$typeProvisions['traffic']);
				$this->_changeStatusInvoiceProvision($startDate,$endDate,$idCarrier,$typeProvisions);
				$this->$typeProvisions['num']=$this->$typeProvisions['num']+1;
			}
		}
	}

	/**
	 * @return int
	 */
	private function _getEndPeriod($num)
	{
		$day=$num;
		for ($i=1; $i < 7; $i++)
		{ 
			if($day<7)
			{
				$day+=1;
			}
			else
			{
				$day=1;
			}
		}
		return (int)$day;
	}

	public function sendNotification()
	{
		$body="<!DOCTYPE html>
				<html lang='es'>
					<head>
						<title>Notificación</title>
						<meta charset='utf-8'>
					</head>
					<body style='font-family:Segoe UI;'>
						<header style='font-size:3em;'>Provisiones Generadas</header>
						<section>Ya estan disponibles las provisiones hasta el día ".$this->date."</section>
						<footer style='font-size:0.8em;'>Correo enviado automaticamente a las ".date("H:i:s A")."</footer>
					</body>
				</html>";
		$user="manuelz@sacet.biz";
		Yii::app()->mail->enviar($body, $user, "Provisiones Generadas");
	}
}
?>

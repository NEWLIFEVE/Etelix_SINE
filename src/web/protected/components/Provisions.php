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
	 * Contiene el id de carrier en caso de ser provisiones para un solo carrier
	 * @var int
	 */
	public $carriers;

	/**
	 * Contiene el nombre del grupo introducido por parametros
	 * @var string
	 */
	public $group;

	/**
	 * Contiene el array con las provisiones de trafico enviado encontradas en el balance
	 * @var array
	 */
	public $invoicesSent;

	/**
	 * Contiene el array con las provisiones de trafico recibido encontrado en el balance
	 * @var array
	 */
	public $invoicesReceived;

	/**
	 * Contiene el numero de provisiones de trafico enviado generado en una fecha
	 * @var int
	 */
	public $numTrafficSend;

	/**
	 * Contiene el numero de provisiones de trafico recibido generado en una fecha especifica
	 * @var int
	 */
	public $numTrafficReceived;

	/**
	 * Contiene el numero de provisiones de facturas enviadas generadas en una fecha especifica
	 * @var int
	 */
	public $numInvoicesSend;

	/**
	 * Contiene el numero de provisiones de facturas recibidas generadas en una fecha especfica
	 * @var int
	 */
	public $numInvoicesReceived;

	/**
	 * Le dice a Yii que debe hacer al instanciar la clase
	 */
	public function init() 
    {
       $this->numTrafficSend=$this->numTrafficReceived=0;
       $this->carriers=$this->group=null;
    }

    /**
     * Metodo encargado de correr las provisiones
     * @access public
     */
    public function run($dateSet=null,$group=null)
    {
    	if($group!=null)
    	{
    		$this->group=$group;
    		$this->carriers=Carrier::model()->findAll('id_carrier_groups=:id',array(':id'=>CarrierGroups::getId($group)));
    	}
    	else
    	{
    		$this->carriers=Carrier::model()->findAll();
    	}

        $seg=count($this->carriers) * 5 * DateManagement::dateDiff($dateSet,date('Y-m-d'));
        ini_set('max_execution_time', $seg);
        
    	$this->getDate($dateSet);
    	//Obtengo la data de clientes
    	$this->getData(true);
    	//Obtengo la data de proveedores
    	$this->getData(false);
    	//Genero las provisiones de trafico enviadas
    	$this->generateTrafficProvision(true);
    	//Genero las provisions de trafico recibidas
    	$this->generateTrafficProvision(false);
    	//Genero las provisions de factura enviadas
    	$this->runInvoiceProvision(true);
    	//Genero las provisions de factura recibidas
    	$this->runInvoiceProvision(false);
    	
    	if($this->numInvoicesSend>0 || $this->numInvoicesReceived>0)
            var_dump("Se generaron ".$this->numInvoicesSend." facturas enviadas y ".$this->numInvoicesReceived." facturas recibidas para el dia ".$this->date);
    	
        if(!YII_DEBUG) $this->sendNotification();
    }

	/**
	 * Genera la fecha para las consultas a base de datos
	 * @access public
	 */
	public function getDate($dateSet)
	{
		if($dateSet===null) $date=date('Y-m-d');
		else $date=$dateSet;

		$this->date=DateManagement::calculateDate('-1',$date);
	}

	/**
	 * Obtiene los datos para generar las inserciones respectivas
	 * Nota: se usa el atributo id del balance, pero en realidad es el id del carrier de esos balances
	 * @access public
	 * @param boolean $type true=facturas enviadas, false =facturas recibidas
	 */
	public function getData($type=true)
	{
		$data=array('title'=>'proveedor','id'=>'id_carrier_supplier','margin'=>'CASE WHEN ABS(SUM(revenue-margin))<ABS(SUM(cost)) THEN SUM(revenue-margin) ELSE SUM(cost) END','variable'=>'invoicesReceived');
		if($type) $data=array('title'=>'cliente','id'=>'id_carrier_customer','margin'=>'CASE WHEN ABS(SUM(cost+margin))<ABS(SUM(revenue)) THEN SUM(cost+margin) ELSE SUM(revenue) END','variable'=>'invoicesSent');

		$one="";
		if($this->group!==null && $this->group!==false) $one="AND {$data['id']} IN(".$this->_carriers().")";

		$sql="SELECT {$data['id']} AS id, date_balance, SUM(minutes) AS minutes, {$data['margin']} AS margin
			  FROM balance
			  WHERE date_balance='{$this->date}' AND id_destination IS NULL AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') {$one}
			  GROUP BY {$data['id']}, date_balance
			  ORDER BY margin DESC";
		//	  var_dump($sql);
		$this->$data['variable']=Balance::model()->findAllBySql($sql);
	}

	/**
	 * Genera las provisiones de trafico con la data almacenada en $result
	 * @access public
	 * @param boolean $type true=facturas enviadas, false=facturas recibidas
	 */
	public function generateTrafficProvision($type=true)
	{
		$values="";
		$count=0;

		if($type) 
		{
			$data=array('variable'=>'invoicesSent','condition'=>"name='Provision Trafico Enviada'",'num'=>"numTrafficSend");
		}
		else
		{
			$data=array('variable'=>'invoicesReceived','condition'=>"name='Provision Trafico Recibida'",'num'=>"numTrafficReceived");
		}

		$type_document=TypeAccountingDocument::model()->find($data['condition'])->id;

		$currency=Currency::model()->find("name='$'")->id;

		$this->$data['num']=$num=count($this->$data['variable']);
		foreach ($this->$data['variable'] as $key => $factura)
		{
			$this->_deleteProvision($factura->date_balance,$factura->date_balance,$factura->id,$type_document);
			if($factura->margin!=0)
			{
				$count+=1;
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
		if($count>0)
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
	 * Se encargada de generar las provisiones de facturas
	 * @param boolean $type true=clientes, false=proveedores
	 */
	public function runInvoiceProvision($type=true)
	{
		
		if($type)
		{
			foreach ($this->carriers as $key => $carrier)
			{
				$this->_generateInvoiceProvisionCustomer($carrier->id);
			}
			$this->numTrafficSend=$this->numTrafficReceived=0;
		}
		else
		{
			foreach ($this->carriers as $key => $carrier)
			{
				$this->_generateInvoiceProvisionSupplier($carrier->id);
			}
			
			$this->numTrafficSend=$this->numTrafficReceived=0;
		}
	}

	/**
	 * Envia un correo de notificacion de las provisiones generadas
	 * @access public
	 */
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

	/**
	 * Genera la provision de factura de los clientes
	 * @access private
	 * @param int $idCarrier
	 */
	private function _generateInvoiceProvisionCustomer($idCarrier)
	{
		//$data=array('variable'=>'invoicesSent');
		$typeProvisions['traffic']=TypeAccountingDocument::model()->find("name='Provision Trafico Enviada'")->id;
		$typeProvisions['invoice']=TypeAccountingDocument::model()->find("name='Provision Factura Enviada'")->id;
		$typeProvisions['real']=TypeAccountingDocument::model()->find("name='Factura Enviada'")->id;
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
					$num=DateManagement::getDayNumberWeek($this->date);
					if($tempdate===$this->date)
					{
						$firstDay=DateManagement::getMonday($this->date);
						$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
					}
					elseif($num==7)
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
		//$data=array('variable'=>'invoicesReceived','condition'=>"name='Provision Trafico Recibida'",'invoice'=>"name='Provision Factura Recibida'",'real'=>"name='Factura Recibida'");

		$typeProvisions['traffic']=TypeAccountingDocument::model()->find("name='Provision Trafico Recibida'")->id;
		$typeProvisions['invoice']=TypeAccountingDocument::model()->find("name='Provision Factura Recibida'")->id;
		$typeProvisions['real']=TypeAccountingDocument::model()->find("name='Factura Recibida'")->id;
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
		if($carrier=="BSG-SHARE" /*|| $carrier=="BSG-SHARE T1" || $carrier=="BSG-SHARE T2" || $carrier=="BSG-SHARE T3" || $carrier=="BSG-SHARE PROPER"*/)
		{
			$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-27";
			if(DateManagement::separatesDate($this->date)['month']=="12") $tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-25";
			if($tempdate===$this->date)
			{
				$firstDay=DateManagement::getDayOne($this->date);
				$this->_insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
				//var_dump("Generando factura de BSH SHARE del ".$firstDay." al ".$this->date);
			}
			$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-".DateManagement::howManyDays($this->date);
			if($tempdate===$this->date)
			{
				$firstDay=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-28";
				if(DateManagement::separatesDate($this->date)['month']=="12") $firstDay=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-26";
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
								elseif($end==DateManagement::getDayNumberWeek($this->date))
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
	 * @access private
	 * @param date $stratDate
	 * @param date $endDate
	 * @param int $idCarrier
	 * @param int $idDocument
	 * @return CActiveRecord
	 */
	private function _getTrafficProvision($startDate,$endDate,$idCarrier,$idDocument)
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
	private function _changeStatusProvisions($startDate,$endDate,$idCarrier,$idDocument,$otherDocument)
	{
		var_dump($startDate,$endDate,$idCarrier,TypeAccountingDocument::model()->findByPk($idDocument)->name);
		$model=AccountingDocument::model()->findAll('from_date>=:start AND from_date<=:end AND id_carrier=:id AND id_type_accounting_document=:type', array(':start'=>$startDate,':end'=>$endDate,':id'=>$idCarrier,':type'=>$idDocument));
		foreach ($model as $key => $value)
		{
			$this->_changeStatusProvision($value->id,$otherDocument);
		}
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
	private function _changeStatusProvision($id,$otherDocument)
	{
		$provision=AccountingDocumentProvisions::model()->findByPk($id);
		$provision->confirm=-1;
		$provision->id_accounting_document=$otherDocument;
		if($provision->save())
		{
			return true;
		}
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
	private function _changeStatusInvoiceProvision($startDate,$endDate,$idCarrier,$data)
	{
		$invoice=AccountingDocumentProvisions::model()->find('from_date>=:start AND to_date<=:end AND id_carrier=:id AND id_type_accounting_document=:type', array(':start'=>$startDate,':end'=>$endDate,':id'=>$idCarrier,':type'=>$data['real']));
		if(isset($invoice->id) && $invoice->id!=null)
		{
			$provision=AccountingDocumentProvisions::model()->find('from_date>=:start AND to_date<=:end AND id_carrier=:id AND id_type_accounting_document=:type', array(':start'=>$startDate,':end'=>$endDate,':id'=>$idCarrier,':type'=>$data['invoice']));
			$provision->confirm=-1;
			$provision->id_accounting_document=$invoice->id;
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
		//var_dump("la suma desde ".TypeAccountingDocument::model()->findByPk($typeProvisions['traffic'])->name." para generar una ".TypeAccountingDocument::model()->findByPk($typeProvisions['invoice'])->name." en las fechas ".$startDate." ".$endDate);
		$trafficProvisions=$this->_getTrafficProvision($startDate,$endDate,$idCarrier,$typeProvisions['traffic']);
		if($trafficProvisions->amount!=null)
		{
			$this->_deleteProvision($startDate,$endDate,$idCarrier,$typeProvisions['invoice']);
			$doccument=new AccountingDocumentProvisions;
			$doccument->issue_date=DateManagement::calculateDate('+1',$endDate);
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
				$this->_changeStatusProvisions($startDate,$endDate,$idCarrier,$typeProvisions['traffic'],$doccument->id);
				$this->_changeStatusInvoiceProvision($startDate,$endDate,$idCarrier,$typeProvisions);
				$this->$typeProvisions['num']=$this->$typeProvisions['num']+1;
			}
		}
	}

	/**
	 * Funcion encargada de eliminar una provision especifica
	 * @access private
	 * @param date $startDate
	 * @param date $endDate
	 * @param int $idCarrier
	 * @param int $typeProvision
	 */
	private function _deleteProvision($startDate,$endDate,$idCarrier,$typeProvision)
	{
		//var_dump($startDate,$endDate,$idCarrier,TypeAccountingDocument::model()->findByPk($typeProvision)->name);
		return AccountingDocumentProvisions::model()->deleteAll('from_date=:from AND to_date=:to AND id_carrier=:id AND id_type_accounting_document=:type',array(':from'=>$startDate,':to'=>$endDate,':id'=>$idCarrier,':type'=>$typeProvision));
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

	/**
	 * 
	 */
	private function _carriers()
	{
		$body="";
		$num=count($this->carriers);
		foreach ($this->carriers as $key => $carrier)
		{
			if($key>0 && $key<$num) $body.=",";
			$body.=$carrier->id;
		}
		return $body;
	}
}
?>

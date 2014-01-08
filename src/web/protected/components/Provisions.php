<?php
/**
 * @package components
 * @version 1.7
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
    	//
    	$this->runInvoiceProvision(true);
    	//
    	$this->runInvoiceProvision(false);
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
		$data=array('title'=>'proveedor','id'=>'id_carrier_supplier','margin'=>'cost','variable'=>'invoicesReceived');
		if($type) $data=array('title'=>'cliente','id'=>'id_carrier_customer','margin'=>'revenue','variable'=>'invoicesSent');

		$sql="SELECT {$data['id']} AS id, date_balance, SUM(minutes) AS minutes, SUM({$data['margin']}) AS margin
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

		$data=array('variable'=>'invoicesReceived','condition'=>"name='Provision Trafico Recibida'");
		if($type) $data=array('variable'=>'invoicesSent','condition'=>"name='Provision Trafico Enviada'");

		$type_document=TypeAccountingDocument::model()->find($data['condition'])->id;

		$currency=Currency::model()->find("name='$'")->id;

		$num=count($this->$data['variable']);
		foreach ($this->$data['variable'] as $key => $factura)
		{
			if($factura->margin!=0)
			{
				if($key>0 && $key<$num) $values.=",";
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
			}
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

	public function runInvoiceProvision($type=true)
	{
		$model=Carrier::model()->findAll();
		foreach ($model as $key => $carrier)
		{
			$this->generateInvoiceProvision($carrier->id,$type);
		}
	}

	/**
	 * @access public
	 * @param boolean $type true=facturas enviadas, false=facturas recibidas
	 */
	public function generateInvoiceProvision($idCarrier,$type)
	{
		$data=array('variable'=>'invoicesReceived','condition'=>"name='Provision Trafico Recibida'",'invoice'=>"name='Provision Factura Recibida'",'real'=>"name='Factura Recibida'");
		if($type) $data=array('variable'=>'invoicesSent','condition'=>"name='Provision Trafico Enviada'",'invoice'=>"name='Provision Factura Enviada'",'real'=>"name='Factura Enviada'");

		$typeProvisions['traffic']=TypeAccountingDocument::model()->find($data['condition'])->id;
		$typeProvisions['invoice']=TypeAccountingDocument::model()->find($data['invoice'])->id;
		$typeProvisions['real']=TypeAccountingDocument::model()->find($data['real'])->id;
		$typeProvisions['currency']=Currency::model()->find("name='$'")->id;

		$sql="SELECT tp.*
			  FROM termino_pago tp, contrato_termino_pago ctp, contrato con
			  WHERE tp.id=ctp.id_termino_pago AND ctp.id_contrato=con.id AND con.id_carrier={$idCarrier} AND con.end_date IS NULL AND ctp.end_date IS NULL";

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
					$tempdate=DateManagement::separatesDate($this->date)['year']."-".DateManagement::separatesDate($this->date)['month']."-".DateManagement::howManyDays($this->date);
					if($tempdate===$this->date)
					{
						$firstDay=DateManagement::getMonday($this->date);
						$this->insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
					}
					$num=DateManagement::getDayNumberWeek($this->date);
					if($num==7)
					{
						$monday=DateManagement::getMonday($this->date);
						if(DateManagement::separatesDate($monday)['month']==DateManagement::separatesDate($this->date)['month'])
						{
							$this->insertInvoiceProvision($monday,$this->date,$idCarrier,$typeProvisions);
						}
						else
						{
							$firstDay=DateManagement::getDayOne($this->date);
							$this->insertInvoiceProvision($firstDay,$this->date,$idCarrier,$typeProvisions);
						}
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
	 *
	 */
	public function changeStatusProvision($startDate,$endDate,$idCarrier,$idDocument)
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
	 *
	 */
	public function changeStatusInvoiceProvision($startDate,$endDate,$idCarrier,$data)
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
	 *
	 */
	public function insertInvoiceProvision($startDate,$endDate,$idCarrier,$typeProvisions)
	{
		var_dump($startDate,$endDate);
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
				$this->changeStatusProvision($startDate,$endDate,$idCarrier,$typeProvisions['traffic']);
				$this->changeStatusInvoiceProvision($startDate,$endDate,$idCarrier,$typeProvisions);
			}
		}
	}
}
?>
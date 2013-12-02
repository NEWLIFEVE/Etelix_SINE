<?php

/**
 * This is the model class for table "accounting_document".
 *
 * The followings are the available columns in table 'accounting_document':
 * @property integer $id
 * @property string $issue_date
 * @property string $from_date
 * @property string $to_date
 * @property string $valid_received_date
 * @property string $sent_date
 * @property string $doc_number
 * @property double $minutes
 * @property double $amount
 * @property string $note
 * @property integer $id_type_accounting_document
 * @property integer $id_carrier
 * @property string $email_received_date
 * @property string $valid_received_hour
 * @property string $email_received_hour
 * @property integer $id_currency
 * @property integer $confirm
 * @property double $min_etx
 * @property double $min_carrier
 * @property double $rate_etx
 * @property double $rate_carrier
 * @property integer $id_accounting_document
 * @property integer $id_destination
 * @property integer $id_destination_supplier
 *
 * The followings are the available model relations:
 * @property Carrier $idCarrier
 * @property Currency $idCurrency
 * @property TypeAccountingDocument $idTypeAccountingDocument
 * @property AccountingDocument $idAccountingDocument
 * @property Destination $id_destination
 * @property DestinationSupplier $id_destination_supplier
 * @property AccountingDocument[] $accountingDocuments
 * @property AccountingDocumentTemp[] $accountingDocuments
 */
class AccountingDocument extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'accounting_document';
	}
        public $carrier_groups;
        public $amount_etx;
        public $amount_carrier;
        public $dispute;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_type_accounting_document', 'required'),
			array('id_type_accounting_document, id_carrier, id_currency, confirm, id_accounting_document, id_destination, id_destination_supplier, dispute', 'numerical', 'integerOnly'=>true),
			array('minutes, amount, min_etx, min_carrier, rate_etx, rate_carrier', 'numerical'),
			array('doc_number', 'length', 'max'=>50),
			array('note', 'length', 'max'=>250),
			array('issue_date, from_date, to_date, valid_received_date, sent_date, email_received_date, valid_received_hour, email_received_hour,dispute', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, issue_date, from_date, to_date, valid_received_date, sent_date, doc_number, minutes, amount, note, id_type_accounting_document, id_carrier, email_received_date, valid_received_hour, email_received_hour, id_currency, confirm, min_etx, min_carrier, rate_etx, rate_carrier, id_accounting_document, id_destination, id_destination_supplier, dispute', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'idCarrier' => array(self::BELONGS_TO, 'Carrier', 'id_carrier'),
			'idCurrency' => array(self::BELONGS_TO, 'Currency', 'id_currency'),
			'idTypeAccountingDocument' => array(self::BELONGS_TO, 'TypeAccountingDocument', 'id_type_accounting_document'),
			'idAccountingDocument' => array(self::BELONGS_TO, 'AccountingDocument', 'id_accounting_document'),
			'accountingDocuments' => array(self::HAS_MANY, 'AccountingDocument', 'id_accounting_document'),
                        'accountingDocumentTemps' => array(self::HAS_MANY, 'AccountingDocumentTemp', 'id_accounting_document'),
                        'idDestination' => array(self::BELONGS_TO, 'Destination', 'id_destination'),
                        'idDestinationSupplier' => array(self::BELONGS_TO, 'DestinationSupplier', 'id_destination_supplier'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'issue_date' => 'Fecha de Emisión',
			'from_date' => 'Inicio Periodo a Facturar',
			'to_date' => 'Fin Periodo a Facturar',
			'valid_received_date' => 'Valid Received Date',
			'email_received_date' => 'Fecha de recepción de Email',
			'valid_received_hour' => 'Valid Received Hour',
			'email_received_hour' => 'Hora de recepción de Email',
			'sent_date' => 'Fecha de envio',
			'doc_number' => 'Número de documento',
			'minutes' => 'Minutos',
			'amount' => 'Monto',
			'note' => 'Nota',
			'id_type_accounting_document' => 'Tipo de documento contable',
			'id_carrier' => 'Carrier',
			'id_currency' => 'Moneda',
			'confirm' => 'Confirmada',
			'min_etx' => 'Min Etx',
			'min_carrier' => 'Min Carrier',
			'rate_etx' => 'Tarifa Etx',
			'rate_carrier' => 'Tarifa Carrier',
			'id_accounting_document' => 'Documento Relacionado',
                        'id_destination' => 'Destino Etelix',
			'id_destination_supplier' => 'Destino Proveedor',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('issue_date',$this->issue_date,true);
		$criteria->compare('from_date',$this->from_date,true);
		$criteria->compare('to_date',$this->to_date,true);
		$criteria->compare('valid_received_date',$this->valid_received_date,true);
		$criteria->compare('sent_date',$this->sent_date,true);
		$criteria->compare('doc_number',$this->doc_number,true);
		$criteria->compare('minutes',$this->minutes);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('id_type_accounting_document',$this->id_type_accounting_document);
		$criteria->compare('id_carrier',$this->id_carrier);
		$criteria->compare('email_received_date',$this->email_received_date,true);
		$criteria->compare('valid_received_hour',$this->valid_received_hour,true);
		$criteria->compare('email_received_hour',$this->email_received_hour,true);
		$criteria->compare('id_currency',$this->id_currency);
		$criteria->compare('confirm',$this->confirm);
		$criteria->compare('min_etx',$this->min_etx);
		$criteria->compare('min_carrier',$this->min_carrier);
		$criteria->compare('rate_etx',$this->rate_etx);
		$criteria->compare('rate_carrier',$this->rate_carrier);
		$criteria->compare('id_accounting_document',$this->id_accounting_document);
                $criteria->compare('$id_destination',$this->id_destination);
		$criteria->compare('$id_destination_supplier',$this->id_destination_supplier);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccountingDocument the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
?>
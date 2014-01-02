<?php

/**
 * This is the model class for table "provision".
 *
 * The followings are the available columns in table 'provision':
 * @property integer $id
 * @property string $from_date
 * @property string $to_date
 * @property double $minutes
 * @property double $amount
 * @property integer $id_carrier
 * @property integer $id_type_accounting_document
 * @property integer $id_currency
 *
 * The followings are the available model relations:
 * @property Carrier $idCarrier
 * @property Currency $idCurrency
 * @property TypeAccountingDocument $idTypeAccountingDocument
 */
class Provision extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Provision the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'provision';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('minutes, amount, id_carrier, id_type_accounting_document', 'required'),
			array('id_carrier, id_type_accounting_document, id_currency', 'numerical', 'integerOnly'=>true),
			array('minutes, amount', 'numerical'),
			array('from_date, to_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, from_date, to_date, minutes, amount, id_carrier, id_type_accounting_document, id_currency', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'from_date' => 'From Date',
			'to_date' => 'To Date',
			'minutes' => 'Minutes',
			'amount' => 'Amount',
			'id_carrier' => 'Id Carrier',
			'id_type_accounting_document' => 'Id Type Accounting Document',
			'id_currency' => 'Id Currency',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('from_date',$this->from_date,true);
		$criteria->compare('to_date',$this->to_date,true);
		$criteria->compare('minutes',$this->minutes);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('id_carrier',$this->id_carrier);
		$criteria->compare('id_type_accounting_document',$this->id_type_accounting_document);
		$criteria->compare('id_currency',$this->id_currency);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
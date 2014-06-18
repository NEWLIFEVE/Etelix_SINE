<?php

/**
 * This is the model class for table "billing".
 *
 * The followings are the available columns in table 'billing':
 * @property integer $id
 * @property string $carrier
 * @property integer $id_carrier
 * @property string $date_balance
 * @property double $amount
 */
class Billing extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'billing';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_carrier', 'numerical', 'integerOnly'=>true),
			array('amount', 'numerical'),
			array('carrier, date_balance', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, carrier, id_carrier, date_balance, amount', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'carrier' => 'Carrier',
			'id_carrier' => 'Id Carrier',
			'date_balance' => 'Date Balance',
			'amount' => 'Amount',
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
		$criteria->compare('carrier',$this->carrier,true);
		$criteria->compare('id_carrier',$this->id_carrier);
		$criteria->compare('date_balance',$this->date_balance,true);
		$criteria->compare('amount',$this->amount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Billing the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

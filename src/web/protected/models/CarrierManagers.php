<?php

/**
 * This is the model class for table "carrier_managers".
 *
 * The followings are the available columns in table 'carrier_managers':
 * @property string $start_date
 * @property string $end_date
 * @property integer $id_carrier
 * @property integer $id_managers
 *
 * The followings are the available model relations:
 * @property Carrier $idCarrier
 * @property Managers $idManagers
 */
class CarrierManagers extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CarrierManagers the static model class
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
		return 'carrier_managers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_carrier, id_managers', 'numerical', 'integerOnly'=>true),
			array('start_date, end_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('start_date, end_date, id_carrier, id_managers', 'safe', 'on'=>'search'),
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
			'idManagers' => array(self::BELONGS_TO, 'Managers', 'id_managers'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'start_date' => 'Start Date',
			'end_date' => 'End Date',
			'id_carrier' => 'Id Carrier',
			'id_managers' => 'Id Managers',
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

		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('id_carrier',$this->id_carrier);
		$criteria->compare('id_managers',$this->id_managers);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function getManager($id,$fecha=null)
	{
		if($fecha==null)
		{
			$fecha=date('Y-m-d');
		}
		$model=self::model()->find('id_carrier=:id AND start_date<=:fecha AND end_date IS NULL',array(':id'=>$id,':fecha'=>$fecha));
		if($model!=null)
		{
			$vendedor=Managers::getName($model->id_managers);
		}
		else
		{
			$vendedor="No Asignado";
		}
		return $vendedor;
	}
}
<?php

/**
 * This is the model class for table "managers".
 *
 * The followings are the available columns in table 'managers':
 * @property integer $id
 * @property string $name
 * @property string $lastname
 * @property string $address
 * @property string $record_date
 * @property string $position
 *
 * The followings are the available model relations:
 * @property CarrierManagers[] $carrierManagers
 */
class Managers extends CActiveRecord
{
	public $vendedor;
	public $operador;
	public $company;
	public $termino_pago;
	public $monetizable;
	public $dias_disputa;
	public $limite_credito;
	public $limite_compra;
	public $production_unit;
	public $status;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Managers the static model class
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
		return 'managers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name,lastname, record_date','required'),
			array('name,lastname, position','length','max'=>50),
			array('address','safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name,lastname, address, record_date, position','safe','on'=>'search'),
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
			'carrierManagers'=>array(self::HAS_MANY,'CarrierManagers','id_managers'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'name'=>'Name',
			'lastname'=>'Last Name',
			'address'=>'Address',
			'record_date'=>'Record Date',
			'position'=>'Position',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('lastname',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('record_date',$this->record_date,true);
		$criteria->compare('position',$this->position,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @access public
	 * @static
	 */
	public static function getName($id)
	{
		$model=self::model()->find('id=:id',array(':id'=>$id));
		if($model->lastname!=null)
		{
			return $model->lastname;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * @access public
	 * @static
	 * @return CActiveRecord
	 */
	public static function getManagers()
	{
		$model=self::model()->findAll();
		return $model;
	}
}
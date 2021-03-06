<?php

/**
 * This is the model class for table "carrier".
 *
 * The followings are the available columns in table 'carrier':
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $record_date
 *
 * The followings are the available model relations:
 * @property CarrierManagers[] $carrierManagers
 * @property Balance[] $balances
 * @property Balance[] $balances1
 */
class Carrier extends CActiveRecord
{
    public $days;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Carrier the static model class
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
		return 'carrier';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name,record_date','required'),
			array('name','length','max'=>50),
			array('address','safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, address, record_date','safe','on'=>'search'),
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
			'carrierManagers'=>array(self::HAS_MANY,'CarrierManagers','id_carrier'),
			'balances'=>array(self::HAS_MANY,'Balance','id_carrier_supplier'),
			'balances1'=>array(self::HAS_MANY,'Balance','id_carrier_customer'),
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
			'address'=>'Address',
			'record_date'=>'Record Date',
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
		$criteria->compare('address',$this->address,true);
		$criteria->compare('record_date',$this->record_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Obtiene el nombre de un carrier a traves del id
	 * @access public
	 * @static
	 * @param int $id id del carrier
	 * @return string $name el nombre del carrier
	 */
	public static function getName($id)
	{
		return self::model()->findByPk($id)->name;
	}

	/**
	 *
	 */
	public static function getNames()
	{
//		return self::model()->findAll();
            return self::model()->findAllBySql("SELECT name FROM carrier_groups WHERE id not in(select id from carrier where name='Unknown_Carrier')");
	}

	/**
	 * Retorna el id de un carrier solicitado
	 * @access public
	 * @static
	 * @param string $carrier
	 * @return int
	 */
	public static function getId($name)
	{
		if($name!=null)
		{
			$carrier=self::model()->find('name=:name',array(':name'=>$name));
			if($carrier!=null) return $carrier->id;
			else return false;
		}
	}
        /**
         * retorna listado de carriers limitado por el id_carriers_groups
         * @param type $idGrupo
         * @return type
         */
        public static function getListCarriersGrupo($idGrupo)
        {
            return Carrier::model()->findAll("id_carrier_groups =:grupo order by name ASC",array(":grupo"=>$idGrupo));
        }
        /**
         * retorna listado de todos los carriers menos Unknown Carrier
         * @return type
         */
        public static function getListCarrier()
        {
            return self::model()->findAllBySql("SELECT * FROM carrier WHERE id not in(select id from carrier where name='Unknown_Carrier')");
        } 
}
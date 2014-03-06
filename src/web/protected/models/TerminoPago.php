<?php

/**
 * This is the model class for table "termino_pago".
 *
 * The followings are the available columns in table 'termino_pago':
 * @property integer $id
 * @property string $name
 * @property integer $period
 * @property integer $expiration
 *
 * The followings are the available model relations:
 * @property ContratoTerminoPago[] $contratoTerminoPagos
 * @property ContratoTerminoPagoSupplier[] $contratoTerminoPagoSuppliers
 */
class TerminoPago extends CActiveRecord
{
	/**
	 * Atributos utilizados para calculo de provisiones de proveedor
	 */
	public $month_break;

	public $first_day;

	public $payment_term;

	public $billing_period;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TerminoPago the static model class
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
        return 'termino_pago';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('period, expiration', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, period, expiration', 'safe', 'on'=>'search'),
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
            'contratoTerminoPagos' => array(self::HAS_MANY, 'ContratoTerminoPago', 'id_termino_pago'),
            'contratoTerminoPagoSuppliers' => array(self::HAS_MANY, 'ContratoTerminoPagoSupplier', 'id_termino_pago_supplier'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'period' => 'Period',
            'expiration' => 'Expiration',
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
        $criteria->compare('period',$this->period);
        $criteria->compare('expiration',$this->expiration);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * @access public
     * @static
     */
    public static function getName($termino_pago)
    {           
        return self::model()->find("id=:id", array(':id'=>$termino_pago))->name;
    }

    /**
     * @access public
     * @static
     */
    public static function getModel()
    {
    	return self::model()->findAll();
    }
}
<?php

/**
 * This is the model class for table "contrato".
 *
 * The followings are the available columns in table 'contrato':
 * @property integer $id
 * @property string $sign_date
 * @property string $production_date
 * @property string $end_date
 * @property integer $id_carrier
 * @property integer $id_company
 * @property integer $up
 *
 * The followings are the available model relations:
 * @property Carrier $idCarrier
 * @property Company $idCompany
 * @property DaysDisputeHistory[] $daysDisputeHistories
 * @property ContratoTerminoPago[] $contratoTerminoPagos
 * @property ContratoMonetizable[] $contratoMonetizables
 * @property CreditLimit[] $creditLimits
 * @property PurchaseLimit[] $purchaseLimits
 * @property SolvedDaysDisputeHistory[] $solvedDaysDisputeHistories
 */
class Contrato extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Contrato the static model class
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
        return 'contrato';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_carrier, id_company', 'required'),
            array('id_carrier, id_company, up', 'numerical', 'integerOnly'=>true),
            array('sign_date, production_date, end_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, sign_date, production_date, end_date, id_carrier, id_company, up', 'safe', 'on'=>'search'),
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
            'idCompany' => array(self::BELONGS_TO, 'Company', 'id_company'),
            'daysDisputeHistories' => array(self::HAS_MANY, 'DaysDisputeHistory', 'id_contrato'),
            'contratoTerminoPagos' => array(self::HAS_MANY, 'ContratoTerminoPago', 'id_contrato'),
            'contratoMonetizables' => array(self::HAS_MANY, 'ContratoMonetizable', 'id_contrato'),
            'creditLimits' => array(self::HAS_MANY, 'CreditLimit', 'id_contrato'),
            'purchaseLimits' => array(self::HAS_MANY, 'PurchaseLimit', 'id_contrato'),
            'solvedDaysDisputeHistories' => array(self::HAS_MANY, 'SolvedDaysDisputeHistory', 'id_contrato'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'sign_date' => 'Sign Date',
            'production_date' => 'Production Date',
            'end_date' => 'End Date',
            'id_carrier' => 'Id Carrier',
            'id_company' => 'Id Company',
            'up' => 'Up',
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
        $criteria->compare('sign_date',$this->sign_date,true);
        $criteria->compare('production_date',$this->production_date,true);
        $criteria->compare('end_date',$this->end_date,true);
        $criteria->compare('id_carrier',$this->id_carrier);
        $criteria->compare('id_company',$this->id_company);
        $criteria->compare('up',$this->up);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}
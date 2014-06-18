<?php

/**
 * This is the model class for table "log".
 *
 * The followings are the available columns in table 'log':
 * @property integer $id
 * @property string $date
 * @property string $hour
 * @property integer $id_log_action
 * @property integer $id_users
 * @property string $description_date
 * @property integer $id_esp
 *
 * The followings are the available model relations:
 * @property LogAction $idLogAction
 * @property Users $idUsers
 */
class Log extends CActiveRecord
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Log the static model class
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
		return 'log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, hour', 'required'),
			array('id_log_action, id_users, id_esp', 'numerical', 'integerOnly'=>true),
			array('description_date', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, date, hour, id_log_action, id_users, description_date, id_esp', 'safe', 'on'=>'search'),
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
			'idLogAction' => array(self::BELONGS_TO, 'LogAction', 'id_log_action'),
			'idUsers' => array(self::BELONGS_TO, 'Users', 'id_users'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => 'Date',
			'hour' => 'Hour',
			'id_log_action' => 'Id Log Action',
			'id_users' => 'Id Users',
			'description_date' => 'Description Date',
			'id_esp' => 'Id Esp',
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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('hour',$this->hour,true);
		$criteria->compare('id_log_action',$this->id_log_action);
		$criteria->compare('id_users',$this->id_users);
		$criteria->compare('description_date',$this->description_date,true);
		$criteria->compare('id_esp',$this->id_esp);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
     * Funcion que devuelve true si la accion ya fue realizada en la fecha consultada
     * @param $id int numero de la accion
     * @param $fecha date fecha consultada
     * @return boolean
     */
    public static function existe($id,$fecha)
    {
        $model=self::model()->find('id_log_action=:id AND date=:fecha', array(':id'=>$id, ':fecha'=>$fecha));
        if($model!=null)
            return true;
        else
            return false;
    }
    
    /**
     * funcioin que devuelve la letra 
     */
    public static function preliminar($fecha)
    {
    	$nuevafecha=strtotime('+1 day',strtotime($fecha));
        $nuevafecha=date('Y-m-d',$nuevafecha);
        if(self::existe(LogAction::getId('Carga Ruta External Preliminar'),$nuevafecha) && self::existe(LogAction::getId('Carga Ruta Internal Preliminar'),$nuevafecha))
        {
            if(self::existe(LogAction::getId('Carga Ruta External Definitivo'),$nuevafecha) && self::existe(LogAction::getId('Carga Ruta Internal Definitivo'),$nuevafecha))
            {
                $var="D";
            }
            else
            {
                $var="P";
            }
        }
        else
        {
            $var="";
        }
        return $var;
    }
}
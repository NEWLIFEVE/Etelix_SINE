<?php

/**
 * This is the model class for table "users_sine".
 *
 * The followings are the available columns in table 'users_sine':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $activkey
 * @property boolean $superuser
 * @property boolean $status
 * @property string $create_at
 * @property string $lastvisit_at
 * @property integer $id_type_of_user
 *
 * The followings are the available model relations:
 * @property TypeOfUser $idTypeOfUser
 * @property ProfilesSine[] $profilesSines
 */
class UsersRenoc extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UsersSine the static model class
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
		return 'users_sine';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, email, activkey, superuser, status, create_at, lastvisit_at', 'required'),
			array('id_type_of_user', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>20),
			array('password, email, activkey', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, email, activkey, superuser, status, create_at, lastvisit_at, id_type_of_user', 'safe', 'on'=>'search'),
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
			'idTypeOfUser' => array(self::BELONGS_TO, 'TypeOfUser', 'id_type_of_user'),
			'profilesSines' => array(self::HAS_MANY, 'ProfilesSine', 'id_users_sine'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
			'activkey' => 'Activkey',
			'superuser' => 'Superuser',
			'status' => 'Status',
			'create_at' => 'Create At',
			'lastvisit_at' => 'Lastvisit At',
			'id_type_of_user' => 'Id Type Of User',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('activkey',$this->activkey,true);
		$criteria->compare('superuser',$this->superuser);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('lastvisit_at',$this->lastvisit_at,true);
		$criteria->compare('id_type_of_user',$this->id_type_of_user);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
                public function validatePassword($password){
return $this->hashPassword($password)===$this->password;
}
 
public function hashPassword($password){
return md5($password);
        
}
}
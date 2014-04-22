<?php

class CarrierController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index','nombres'),
				'users'=>array('*'),
			),
		);
	}


	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Carrier');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Action que retorna un JSON con los nombres y los ids de los carriers
	 * @access public
	 */
	public function actionNombres()
	{
		$model=Carrier::getNames();
		$array=array();
		$pos=0;
		foreach ($model as $key => $value)
		{
			$array[$pos]['id']=$value->id;
			$array[$pos]['name']=$value->name;
			$pos=$pos+1;
		}
		echo json_encode($array);
	}
}

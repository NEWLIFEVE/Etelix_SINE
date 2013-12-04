<?php

class GruposController extends Controller
{
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('nombres'),
				'users'=>array('*'),
			),
		);
	}

	public function actionNombres()
	{
		$model=CarrierGroups::getNames();
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
<?php
/**
 * @var $this SiteController
 */
class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     * @access public
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
                ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page'=>array(
                'class'=>'CViewAction',
                ),
            );
    }

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

    public function accessRules()
    {
        return array(
            array(
                'allow',
                'actions'=>array(
                    'index',
                    'error',
                    'login',
                    'logout',
                    'mail',
                    'excel',
                    'previa',
                    'updateTerminoPago',
                    'provisions',
                    'genProvisions',
                    'calcTimeProvisions'
                    ),
                'users'=>UsersSine::getUsersByRole("Administrador")
                ),
            array(
                'allow',
                'actions'=>array(
                    'index',
                    'error',
                    'login',
                    'logout',
                    'mail',
                    'excel',
                    'previa',
                    'updateTerminoPago',
                    ),
                'users'=>UsersSine::getUsersByRole("Finanzas")
                )
            );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     * @access public
     */
    public function actionIndex()
    {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        if(!Yii::app()->user->isGuest)
        {
            $this->render('index');
        }
        else
        {
            $model=new LoginForm;
            // if it is ajax validation request
            if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            // collect user input data
            if(isset($_POST['LoginForm']))
            {
                $model->attributes=$_POST['LoginForm'];
                // validate user input and redirect to the previous page if valid
                if($model->validate() && $model->login())
                    $this->redirect(Yii::app()->user->returnUrl);
            }
            // display the login form
            $this->render('login', array('model'=>$model));
        }
    }

    /**
     * This is the action to handle external exceptions.
     * @access public
     */
    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the login page
     * @access public
     */
    public function actionLogin()
    {
        $model=new LoginForm;
        // if it is ajax validation request
        if(isset($_GET['ajax']) && $_GET['ajax']==='login-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        // collect user input data
        if(isset($_GET['LoginForm']))
        {
            $model->attributes=$_GET['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model'=>$model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     * @access public
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * funcion encargada de enviar reportes por correo
     */
    public function actionMail()
    {
        $this->vaciarAdjuntos();
        $date=$group=$from_date=$to_date=$period=null;
        $correos=array();
        $user=UserIdentity::getEmail();
        if(isset($_POST['datepicker']))
        {
            $date=(string)$_POST['datepicker'];
            
            if($_POST['tipo_report']=="soa"||$_POST['tipo_report']=="balance"){
                if(($_POST['grupo'])!=NULL) $group=$_POST['grupo'];
                if(isset($_POST['No_prov'])) $provision=Reportes::define_prov($_POST['No_prov'],$group);
                if(isset($_POST['No_disp'])) $dispute=Reportes::define_disp($_POST['No_disp'],$_POST['tipo_report'],$group,$date);
            }
            switch($_POST['tipo_report'])
            {
               case 'soa':
                    $correos['soa']['asunto']="SINE - SOA de {$group}".self::reportTitle($date);
                    $correos['soa']['cuerpo']=Yii::app()->reportes->SOA($group,$date,$dispute,$provision);
                    $correos['soa']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['soa']['asunto'].".xls";
                    break;
               case 'summary':
//                    $correos['summary']['asunto']="SINE - SUMMARY ".Reportes::defineNameExtra($_POST['id_termino_pago'],$this->trueFalse($_POST['type_termino_pago']),NULL)." ".self::reportTitle($date);
                    $correos['summary']['asunto']="SINE - SUMMARY ".self::reportTitle($date);
                    $correos['summary']['cuerpo']=Yii::app()->reportes->summary($date,$this->trueFalse($_POST['Si_inter']),$this->trueFalse($_POST['Si_act']),$this->trueFalse($_POST['type_termino_pago']),$_POST['id_termino_pago']);
                    $correos['summary']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['summary']['asunto'].".xls";
                    break;
               case 'balance':
                    $correos['balance']['asunto']="SINE - BALANCE de {$group}".self::reportTitle($date);
                    $correos['balance']['cuerpo']=Yii::app()->reportes->balance_report($group,$date,$dispute);
                    $correos['balance']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['balance']['asunto'].".xls";
                    break;
               case 'reteco':
                    $correos['reteco']['asunto']="SINE - RETECO ".Reportes::defineNameExtra($_POST['id_termino_pago'],$this->trueFalse($_POST['type_termino_pago']),NULL)." ".self::reportTitle($date);
                    $correos['reteco']['cuerpo']=Yii::app()->reportes->reteco($this->trueFalse($_POST['Si_car_act']),$this->trueFalse($_POST['type_termino_pago']),$_POST['id_termino_pago']);
                    $correos['reteco']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['reteco']['asunto'].".xls";
                    break;
               case 'refac':
                    $correos['refac']['asunto']="SINE - REFAC ".Reportes::defineNameExtra($_POST['id_periodo'],NULL,$date)." ".self::reportTitle($date);
                    $correos['refac']['cuerpo']=Yii::app()->reportes->refac($date,"REFAC",$_POST['id_periodo'],$this->trueFalse($_POST['Si_sum']));
                    $correos['refac']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['refac']['asunto'].".xls";
                    break;
               case 'refi_prov':
                    $correos['refi_prov']['asunto']="SINE - REPROV ". Reportes::defineNameExtra( $_POST['id_termino_pago'],TRUE,$date ).self::reportTitle(date('Y-m-d'));
                    $correos['refi_prov']['cuerpo']=Yii::app()->reportes->refi_prov($date,"REPROV",$_POST['id_termino_pago'],$_POST['Si_div'],$this->trueFalse($_POST['Si_sum']));
                    $correos['refi_prov']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['refi_prov']['asunto'].".xls";
                    break;
               case 'recredi':
//                  $correos['recredi']['asunto']="SINE - RECREDI ".Reportes::defineNameExtra($_POST['id_termino_pago'],$this->trueFalse($_POST['type_termino_pago']),NULL)." ".self::reportTitle($date);
                    $correos['recredi']['asunto']="SINE - RECREDI".self::reportTitle($date);
                    $correos['recredi']['cuerpo']=Yii::app()->reportes->recredi($date,$this->trueFalse($_POST['Si_inter']),$this->trueFalse($_POST['Si_act']),$this->trueFalse($_POST['type_termino_pago']),$_POST['id_termino_pago']);
                    $correos['recredi']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['recredi']['asunto'].".xls";
                    break;
               case 'recopa':
                    $correos['recopa']['asunto']="SINE - RECOPA".self::reportTitle($date);
                    $correos['recopa']['cuerpo']=Yii::app()->reportes->recopa($date,$_POST['id_filter_oper'],$_POST['No_venc'],$this->trueFalse($_POST['order_recopa']));
                    $correos['recopa']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['recopa']['asunto'].".xls";
                    break;
            }
        }
        foreach($correos as $key => $correo)
        {
            $this->genExcel($correo['asunto'],$correo['cuerpo'],false);
            Yii::app()->mail->enviar($correo['cuerpo'], $user, $correo['asunto'],$correo['ruta']);
        }
        echo "Mensaje Enviado";
    }

    /**
     * funcion encargada de exportar reportes por excel
     */
    public function actionExcel()
    {
        $this->vaciarAdjuntos();
        $date=$group=$to_date=null;
        $archivos=array();
        if(isset($_GET['datepicker']))
        {
            $date=(string)$_GET['datepicker'];
            
            if($_GET['tipo_report']=="soa"||$_GET['tipo_report']=="balance"){
                if(($_GET['grupo'])!=NULL) $group=$_GET['grupo'];
                if(isset($_GET['No_prov'])) $provision=SOA::define_prov($_GET['No_prov'],$group);
                if(isset($_GET['No_disp'])) $dispute=Reportes::define_disp($_GET['No_disp'],$_GET['tipo_report'],$group,$date);
            }

            switch ($_GET['tipo_report'])
            {
                case 'soa':
                    $archivos['soa']['nombre']="SINE - SOA de {$group}".self::reportTitle($date)."-".date("g:i a");
                    $archivos['soa']['cuerpo']=Yii::app()->reportes->SOA($group,$date,$dispute,$provision,$_GET['grupo']);
                    break;
                case 'summary':
                    $archivos['summary']['nombre']="SINE - SUMMARY ".Reportes::defineNameExtra($_GET['id_termino_pago'],$this->trueFalse($_GET['type_termino_pago']),NULL)." ".self::reportTitle($date)."-".date("g:i a");
                    $archivos['summary']['cuerpo']=Yii::app()->reportes->summary($date,$this->trueFalse($_GET['Si_inter']),$this->trueFalse($_GET['Si_act']),$this->trueFalse($_GET['type_termino_pago']),$_GET['id_termino_pago']);
                    break;
                case 'balance':
                    $archivos['balance']['nombre']="SINE - BALANCE de {$group}".self::reportTitle($date)."-".date("g:i a");
                    $archivos['balance']['cuerpo']=Yii::app()->reportes->balance_report($group,$date,$dispute,$_GET['grupo']);
                    break;
                case 'reteco':
                    $archivos['reteco']['nombre']="SINE - RETECO ".Reportes::defineNameExtra($_GET['id_termino_pago'],$this->trueFalse($_GET['type_termino_pago']),NULL)." ".self::reportTitle($date)."-".date("g:i a");
                    $archivos['reteco']['cuerpo']=Yii::app()->reportes->reteco($this->trueFalse($_GET['Si_car_act']),$this->trueFalse($_GET['type_termino_pago']),$_GET['id_termino_pago']);
                    break;
                case 'refac':
                    $archivos['refac']['nombre']="SINE - REFAC ".Reportes::defineNameExtra($_GET['id_periodo'],NULL,$date).self::reportTitle(date('Y-m-d'))." ".date("g:i a");
                    $archivos['refac']['cuerpo']=Yii::app()->reportes->refac($date,"REFAC",$_GET['id_periodo'],$this->trueFalse($_GET['Si_sum']));
                    break;
                case 'refi_prov':
                    $archivos['refi_prov']['nombre']="SINE - REPROV ". Reportes::defineNameExtra( $_GET['id_termino_pago'],TRUE,$date ).self::reportTitle(date('Y-m-d'))." ".date("g:i a");
                    $archivos['refi_prov']['cuerpo']=Yii::app()->reportes->refi_prov($date,"REPROV",$_GET['id_termino_pago'],$_GET['Si_div'],$this->trueFalse($_GET['Si_sum']));
                    break;
                case 'recredi':
                    $archivos['recredi']['nombre']="SINE - RECREDI ".Reportes::defineNameExtra($_GET['id_termino_pago'],$this->trueFalse($_GET['type_termino_pago']),NULL)." ".self::reportTitle($date)."-".date("g:i a");
                    $archivos['recredi']['cuerpo']=Yii::app()->reportes->recredi($date,$this->trueFalse($_GET['Si_inter']),$this->trueFalse($_GET['Si_act']),$this->trueFalse($_GET['type_termino_pago']),$_GET['id_termino_pago']);
                    break;
                case 'recopa':
                    $archivos['recopa']['nombre']="SINE - RECOPA ".self::reportTitle($date)."-".date("g:i a");
                    $archivos['recopa']['cuerpo']=Yii::app()->reportes->recopa($date,$_GET['id_filter_oper'],$_GET['No_venc'],$this->trueFalse($_GET['order_recopa']));
                    break;
            }     
        }
        foreach($archivos as $key => $archivo)
        {
            $this->genExcel($archivo['nombre'],$archivo['cuerpo']);
        }
    }

    /**
     * @access public
     */
    public function actionPrevia()
    {
        $this->vaciarAdjuntos();
        $date=$group=$to_date=null;
        $archivos=array();
        if(isset($_GET['datepicker']))
        {
            $date=(string)$_GET['datepicker'];
            
            if($_GET['tipo_report']=="soa"||$_GET['tipo_report']=="balance"){
                if(($_GET['grupo'])!=NULL) $group=$_GET['grupo'];
                if(isset($_GET['No_prov'])) $provision=SOA::define_prov($_GET['No_prov'],$group);
                if(isset($_GET['No_disp'])) $dispute=Reportes::define_disp($_GET['No_disp'],$_GET['tipo_report'],$group,$date);
            }

            switch($_GET['tipo_report'])
            {
                case 'soa':
                    $archivos['soa']['cuerpo']=Yii::app()->reportes->SOA($group,$date,$dispute,$provision);
                    break;
                case 'summary':
                    $archivos['summary']['cuerpo']=Yii::app()->reportes->summary($date,$this->trueFalse($_GET['Si_inter']),$this->trueFalse($_GET['Si_act']),$this->trueFalse($_GET['type_termino_pago']),$_GET['id_termino_pago']);
                    break;
                case 'balance':
                    $archivos['balance']['cuerpo']=Yii::app()->reportes->balance_report($group,$date,$dispute);
                    break;
                case 'reteco':
                    $archivos['reteco']['cuerpo']=Yii::app()->reportes->reteco($this->trueFalse($_GET['Si_car_act']),$this->trueFalse($_GET['type_termino_pago']),$_GET['id_termino_pago']);
                    break;
                case 'refac':
                    $archivos['refac']['cuerpo']=Yii::app()->reportes->refac($date,"REFAC",$_GET['id_periodo'],$this->trueFalse($_GET['Si_sum']));
                    break;
                case 'refi_prov':
                    $archivos['refi_prov']['cuerpo']=Yii::app()->reportes->refi_prov($date,"REPROV",$_GET['id_termino_pago'],$_GET['Si_div'],$this->trueFalse($_GET['Si_sum']));
                    break;
                case 'recredi':
                    $archivos['recredi']['cuerpo']=Yii::app()->reportes->recredi($date,$this->trueFalse($_GET['Si_inter']),$this->trueFalse($_GET['Si_act']),$this->trueFalse($_GET['type_termino_pago']),$_GET['id_termino_pago']);
                    break;
                case 'recopa':
                    $archivos['recopa']['cuerpo']=Yii::app()->reportes->recopa($date,$_GET['id_filter_oper'],$_GET['No_venc'],$this->trueFalse($_GET['order_recopa']));
                    break;
            }  
        }
        foreach($archivos as $key => $archivo)
        {
            echo $archivo['cuerpo'];
        }
    }

    /**
     * @access public
     * @param type $nombre
     * @param type $html
     * @param type $salida
     */
    public function genExcel($nombre,$html,$salida=true)
    {
        if($salida)
        {
            header("Content-type: application/vnd.ms-excel; charset=utf-8");
            header("Content-Disposition: attachment; filename={$nombre}.xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            echo $html;
        }
        else
        {
            $ruta=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR;
            $fp=fopen($ruta."$nombre.xls","w+");
            $cuerpo="<!DOCTYPE html>
                     <html>
                        <head>
                            <meta charset='utf-8'>
                            <meta http-equiv='Content-Type' content='application/vnd.ms-excel charset=utf-8'>
                        </head>
                        <body>";
            $cuerpo.=$html;
            $cuerpo.="</body>
            </html>";
            fwrite($fp,$cuerpo);
        }
    }

    /**
     * @access public
     */
    public function vaciarAdjuntos()
    {
        $ruta=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR;
        if(is_dir($ruta))
        {
            $archivos=@scandir($ruta);
        }
        if(count($archivos)>1)
        {
            foreach ($archivos as $key => $value)
            {
                if($key>1)
                {
                    if($value!='index.html')
                    {
                        unlink($ruta.$value);
                    }
                }
            }
        }
    }

    /**
     * @access private
     * @static 
     * @param type $start
     * @param type $end
     * @return type
     */
    private static function reportTitle($start,$end=null)
    {
        if($end==null)
        {
            return " al ".str_replace("-","",$start);
        }
        else
        {
            return Reportes::reportTitle($start,$end);
        }
    }

    /**
     * @access public
     * @static
     */
    public static function trueFalse($var)
    {
        if($var=="null" || $var==null)
            return NULL;
        if($var==""||$var=="0")
            return FALSE;
        else
            return TRUE;
        
    }
    /**
     * Carga los select de termino pago al iniciar la aplicacion
     */
    public static function actionUpdateTerminoPago()
    {   
        $tp="";
        $TerminoPago=TerminoPago::getModel();
        foreach ($TerminoPago as $key => $model) {
            if($model->name!="Sin estatus")
            $tp.= "<option value=".$model->id.">".$model->name."</option>";
        }
        echo "<option value='todos'>Todos</option>".$tp;
    }
    /**
     * Renderiza la vista de provisiones de forma parcial para mostrarse en la vista principal dentro de un div
     */
    public function actionProvisions()
    {
        $this->renderPartial("Provisions");
    }
    /**
     * envia los campos necesarios al componente provisions para generar las provisiones dependiendo del grupo y la fecha
     */
    public function actionGenProvisions()
    {
        $group=null;
        $date=DateManagement::calculateDate('+1',$_GET['datepickerOne']);
        $final=date('Y-m-d');
        if(isset($_GET['group'])) $group=$_GET['group'];
        while ($date <= $final)
        {
            Yii::app()->provisions->run($date,$group);
            $date=DateManagement::calculateDate('+1',$date);
        }
    }
    /**
     * calcula el tiempo necesario para generar las provisiones segun la cantidad de carriers y el numero de dias que existe desde la fecha introducida por el usuario y la fecha actual
     */
    public function actionCalcTimeProvisions()
    {
        if($_GET['group']!="")$carriersList=Carrier::getListCarriersGrupo(CarrierGroups::getId($_GET['group']));
          else                $carriersList=Carrier::getListCarrier();
        
        $daysNum=  DateManagement::dateDiff( $_GET['datepickerOne'], date('Y-m-d') ); 
        
        if(count($carriersList) * 4 * $daysNum <= 60)
            echo Yii::app()->format->format_decimal( count($carriersList) * 4 * $daysNum)." Seg";
        else
            echo Yii::app()->format->format_decimal( count($carriersList) * 4 * $daysNum/60)." Min";
    }
    /**
     * Calcula tiempo de espera para generar los reportes, recibe los mismos parametros que se le pasan al componente para generar reportes.
     * @return string
     */
    public function actionCalcTimeReport()
    {
        switch($_GET['tipo_report'])
            {
                case 'summary':
                    $time=count(Reportes::getNumCarriersForTime($_GET['datepicker'], $this->trueFalse($_GET['Si_inter']), $this->trueFalse($_GET['Si_act']), $this->trueFalse($_GET['type_termino_pago']), $_GET['id_termino_pago']))*0.7;
                    if($time <= 60)
                        $time= Yii::app()->format->format_decimal( $time )." Seg";
                    else
                        $time= Yii::app()->format->format_decimal( $time / 60)." Min";
                    echo "<h3> este proceso puede tomar <b>".$time."</b></h3> no cierre su navegador durante ese tiempo";
                    break;
                case 'recredi':
                    $time=count(Reportes::getNumCarriersForTime($_GET['datepicker'], $this->trueFalse($_GET['Si_inter']), $this->trueFalse($_GET['Si_act']), $this->trueFalse($_GET['type_termino_pago']), $_GET['id_termino_pago']))*1.2;
                    if($this->trueFalse($_GET['type_termino_pago'])===NULL)
                        $time= $time * 2; 
                    if($time <= 60)
                        $time= Yii::app()->format->format_decimal( $time )." Seg";
                    else
                        $time= Yii::app()->format->format_decimal( $time / 60)." Min";
                    echo "<h3> este proceso puede tomar <b>".$time."</b></h3> no cierre su navegador durante ese tiempo";
                    break;
                case 'refi_prov':
                    $period=0.5;
                    if($_GET['id_termino_pago']=="todos")
                        $period=1;
                    if($this->trueFalse($_GET['Si_sum'])==TRUE){
                        $time=( DateManagement::howManyDaysBetween("2013-09-30",$_GET['datepicker'])/7 ) * $period;
                        if($time <= 60)
                            $time= Yii::app()->format->format_decimal( $time )." Seg";
                        else
                            $time= Yii::app()->format->format_decimal( $time / 60)." Min";
                        echo "<h3> este proceso puede tomar <b>".$time."</b></h3> no cierre su navegador durante ese tiempo";
                    }else{
                        echo "<br>";
                    } 
                    break;
                default:
                    echo "<br>";
                    break;
            } 
    }
    /**
     * Establece los links para cada tipo de usuarios
     */
    public static function accessControl($idUser)
    {
        $tipo=UsersSine::model()->findByPk($idUser)->idTypeOfUser->nombre;
        if($tipo=="Administrador")
        {
            return "<span class='element-divider'></span>
                <label id='showProvisions'class='element'>Provisiones</label>";
        }
        return false;
    }
}
?>
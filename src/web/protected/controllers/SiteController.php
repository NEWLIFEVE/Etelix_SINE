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
            $model = new LoginForm;
            // if it is ajax validation request
            if(isset($_POST['ajax']) && $_POST['ajax'] === 'login-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            // collect user input data
            if(isset($_POST['LoginForm']))
            {
                $model->attributes = $_POST['LoginForm'];
                // validate user input and redirect to the previous page if valid
                if($model->validate() && $model->login())
                    $this->redirect(Yii::app()->user->returnUrl);
            }
            // display the login form
            $this->render('login', array('model' => $model));
        }
    }

    /**
     * This is the action to handle external exceptions.
     * @access public
     */
    public function actionError()
    {
        if($error = Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        } 
    }
    /**
     * @access public
     */
    public function actionContact()
    {
        $model=new ContactForm;
        if(isset($_GET['ContactForm']))
        {
            $model->attributes=$_GET['ContactForm'];
            if($model->validate())
            {
                $name='=?UTF-8?B?'.base64_encode($model->name).'?=';
                $subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
                $headers="From: $name <{$model->email}>\r\n".
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-type: text/plain; charset=UTF-8";
                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     * @access public
     */
    public function actionLogin()
    {
        $model = new LoginForm;
        // if it is ajax validation request
        if(isset($_GET['ajax']) && $_GET['ajax'] === 'login-form')
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
        $this->render('login', array('model' => $model));
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
        $date=$group=$from_date=$to_date=null;
        $correos=array();
        $user=UserIdentity::getEmail();
        if(isset($_POST['datepicker']))
        {
            $date=(string)$_POST['datepicker'];

            if(($_POST['id_periodo'])!=NULL) $from_date=Reportes::define_fecha_from($_POST['id_periodo'],$date);

            if(($_POST['grupo'])!=NULL) $group=$_POST['grupo'];

            if(isset($_POST['No_prov'])) $provition=Reportes::define_prov($_POST['No_prov']);

            if(isset($_POST['No_disp'])) $dispute=Reportes::define_disp($_POST['No_disp'],$_POST['tipo_report'],$group,$date);
            
            switch ($_POST['tipo_report'])
            {
                case 'soa':
                    $correos['soa']['asunto']="SINE - SOA de {$group}".self::reportTitle($date);
                    $correos['soa']['cuerpo']=Yii::app()->reportes->SOA($group,$date,$dispute,$provition);
                    $correos['soa']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['soa']['asunto'].".xls";
                    break;
                case 'summary':
                    $correos['summary']['asunto']="SINE - SUMMARY".self::reportTitle($date);
                    $correos['summary']['cuerpo']=Yii::app()->reportes->summary($date,$this->trueFalse($_POST['Si_inter']),$this->trueFalse($_POST['Si_act']),$this->trueFalse($_POST['type_termino_pago']),$_POST['id_termino_pago']);
                    $correos['summary']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['summary']['asunto'].".xls";
                    break;
                case 'balance':
                    $correos['balance']['asunto']="SINE - BALANCE de {$group}".self::reportTitle($date);
                    $correos['balance']['cuerpo']=Yii::app()->reportes->balance_report($group,$date,$dispute);
                    $correos['balance']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['balance']['asunto'].".xls";
                    break;
                case 'reteco':
                    $correos['reteco']['asunto']="SINE - RETECO".self::reportTitle($date);
                    $correos['reteco']['cuerpo']=Yii::app()->reportes->reteco($this->trueFalse($_POST['Si_car_act']),$this->trueFalse($_POST['type_termino_pago']),$_POST['id_termino_pago']);
                    $correos['reteco']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['reteco']['asunto'].".xls";
                    break;
                case 'refac':
                    $correos['refac']['asunto']="SINE - REFAC ".Reportes::define_num_dias($from_date, $date)." ".str_replace("-","",$from_date).self::reportTitle($date)."-".date("g:i a");
                    $correos['refac']['cuerpo']=Yii::app()->reportes->refac($from_date,$date,"REFAC",$_POST['id_periodo']);
                    $correos['refac']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['refac']['asunto'].".xls";
                    break;
                case 'refi_prov':
                    $correos['refi_prov']['asunto']="SINE - REPROV ".Reportes::define_num_dias($from_date, $date)." ".str_replace("-","",$from_date).self::reportTitle($date)."-".date("g:i a");
                    $correos['refi_prov']['cuerpo']=Yii::app()->reportes->refi_prov($from_date,$date,"REFI PROV",$_POST['id_periodo']);
                    $correos['refi_prov']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['refi_prov']['asunto'].".xls";
                    break;
               case 'recredi':
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
            if(($_GET['id_periodo'])!=NULL)  $from_date=Reportes::define_fecha_from($_GET['id_periodo'],$date);       
            if(($_GET['grupo'])!=NULL)  $group=$_GET['grupo'];       
            if(isset($_GET['No_prov'])) $provition=SOA::define_prov($_GET['No_prov']);     
            if(isset($_GET['No_disp'])) $dispute=Reportes::define_disp($_GET['No_disp'],$_GET['tipo_report'],$group,$date);
            
            switch ($_GET['tipo_report']) {
              case 'soa':
                   $archivos['soa']['nombre']="SINE - SOA de {$group}".self::reportTitle($date)."-".date("g:i a");
                   $archivos['soa']['cuerpo']=Yii::app()->reportes->SOA($group,$date,$dispute,$provition,$_GET['grupo']);
                   break;
               case 'summary':
                   $archivos['summary']['nombre']="SINE - SUMMARY".self::reportTitle($date)."-".date("g:i a");
                   $archivos['summary']['cuerpo']=Yii::app()->reportes->summary($date,$this->trueFalse($_GET['Si_inter']),$this->trueFalse($_GET['Si_act']),$this->trueFalse($_GET['type_termino_pago']),$_GET['id_termino_pago']);
                   break;
              case 'balance':
                   $archivos['balance']['nombre']="SINE - BALANCE de {$group}".self::reportTitle($date)."-".date("g:i a");
                   $archivos['balance']['cuerpo']=Yii::app()->reportes->balance_report($group,$date,$dispute,$_GET['grupo']);
                   break;
              case 'reteco':
                   $archivos['reteco']['nombre']="SINE - RETECO".self::reportTitle($date)."-".date("g:i a");
                   $archivos['reteco']['cuerpo']=Yii::app()->reportes->reteco($this->trueFalse($_GET['Si_car_act']),$this->trueFalse($_GET['type_termino_pago']),$_GET['id_termino_pago']);
                   break;
              case 'refac':
                   $archivos['refac']['nombre']="SINE - REFAC ".Reportes::define_num_dias($from_date, $date)." ".str_replace("-","",$from_date).self::reportTitle($date)."-".date("g:i a");
                   $archivos['refac']['cuerpo']=Yii::app()->reportes->refac($from_date,$date,"REFAC",$_GET['id_periodo']);
                   break;
              case 'refi_prov':
                   $archivos['refi_prov']['nombre']="SINE - REPROV ".Reportes::define_num_dias($from_date, $date)." ".str_replace("-","",$from_date).self::reportTitle($date)."-".date("g:i a");
                   $archivos['refi_prov']['cuerpo']=Yii::app()->reportes->refi_prov($from_date,$date,"REFI PROV",$_GET['id_periodo']);
                   break;
              case 'recredi':
                   $archivos['recredi']['nombre']="SINE - RECREDI ".self::reportTitle($date)."-".date("g:i a");
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
     *
     */
    public function actionPrevia()
    {
        $this->vaciarAdjuntos();
        $date=$group=$to_date=null;
        $archivos=array();
        if(isset($_GET['datepicker']))
        {
            $date=(string)$_GET['datepicker'];
            if(($_GET['id_periodo'])!=NULL)  $from_date=Reportes::define_fecha_from($_GET['id_periodo'],$date);
            if(($_GET['grupo'])!=NULL)  $group=$_GET['grupo'];       
            if(isset($_GET['No_prov'])) $provition=SOA::define_prov($_GET['No_prov']);     
            if(isset($_GET['No_disp'])) $dispute=Reportes::define_disp($_GET['No_disp'],$_GET['tipo_report'],$group,$date);
            
            switch ($_GET['tipo_report']) {
              case 'soa':
                   $archivos['soa']['cuerpo']=Yii::app()->reportes->SOA($group,$date,$dispute,$provition);
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
                   $archivos['refac']['cuerpo']=Yii::app()->reportes->refac($from_date,$date,"REFAC",$_GET['id_periodo']);
                   break;
              case 'refi_prov':
                   $archivos['refi_prov']['cuerpo']=Yii::app()->reportes->refi_prov($from_date,$date,"REFI PROV",$_GET['id_periodo']);
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
     * 
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
            $cuerpo="
            <!DOCTYPE html>
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
     * 
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
    public static function trueFalse($var)
    {
        if($var=="null")
            return NULL;
        if($var==""||$var=="0")
            return FALSE;
        else
            return TRUE;
        
    }
    public static function ActionUpdateTerminoPago()
    {   
        $tp="";
        $TerminoPago=TerminoPago::getModel();
        foreach ($TerminoPago as $key => $model) {
            if($model->name!="Sin estatus")
            $tp.= "<option value=".$model->id.">".$model->name."</option>";
        }
        echo $tp;
    }
}
?>
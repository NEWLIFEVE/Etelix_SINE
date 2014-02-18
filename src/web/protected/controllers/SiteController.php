<?php
/**
* @var $this SiteController
*/
class SiteController extends Controller
{
    protected $letra;
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
        $this->letra=Log::preliminar($_POST['datepicker']);
        $fecha=$grupo=$fecha_from=$fecha_to=null;
        $correos=null;
        $user=UserIdentity::getEmail();
        if(isset($_POST['datepicker']))
        {
             $fecha=(string)$_POST['datepicker'];
            if(($_POST['id_periodo'])!=NULL)  $fecha_from=Reportes::define_fecha_from($_POST['id_periodo'],$fecha);   
            if(($_POST['grupo'])!=NULL)  $grupo=Reportes::define_grupo($_POST['grupo']);   
            if(isset($_POST['No_prov'])) $no_prov=Reportes::define_prov($_POST['No_prov'],$grupo,$fecha);
            if(isset($_POST['No_disp'])) $no_disp=Reportes::define_disp($_POST['No_disp'],$_POST['tipo_report'],$grupo,$fecha);
            
            switch ($_POST['tipo_report']) {
              case 'soa':
                   $correos['soa']['asunto']="SINE -  SOA".self::reportTitle($fecha);
                   $correos['soa']['cuerpo']=Yii::app()->reportes->SOA($grupo,$fecha,$no_disp,$no_prov,$_POST['grupo']);
                   $correos['soa']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['soa']['asunto'].".xls";
                   break;
              case 'balance':
                   $correos['balance']['asunto']="SINE -  BALANCE".self::reportTitle($fecha);
                   $correos['balance']['cuerpo']=Yii::app()->reportes->balance_report($grupo,$fecha,$no_disp,$_POST['grupo']);
                   $correos['balance']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['balance']['asunto'].".xls";
                   break;
               case 'refac':
                   $correos['refac']['asunto']="SINE - REFAC ".Reportes::define_num_dias($fecha_from, $fecha)." ".str_replace("-","",$fecha_from).self::reportTitle($fecha)."-".date("g:i a");
                   $correos['refac']['cuerpo']=Yii::app()->reportes->refac($fecha_from,$fecha,"REFAC");
                   $correos['refac']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['refac']['asunto'].".xls";
                   break;
               case 'recredi':
                   $correos['recredi']['asunto']="SINE - RECREDI ".Reportes::define_num_dias($fecha_from, $fecha)." ".str_replace("-","",$fecha_from).self::reportTitle($fecha)."-".date("g:i a");
                   $correos['recredi']['cuerpo']=Yii::app()->reportes->recredi($fecha);
                   $correos['recredi']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['recredi']['asunto'].".xls";
                   break;
               case 'refi_prov':
                   $correos['refi_prov']['asunto']="SINE - REPROV ".Reportes::define_num_dias($fecha_from, $fecha)." ".str_replace("-","",$fecha_from).self::reportTitle($fecha)."-".date("g:i a");
                   $correos['refi_prov']['cuerpo']=Yii::app()->reportes->refi_prov($fecha_from,$fecha,"REFI PROV");
                   $correos['refi_prov']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['refi_prov']['asunto'].".xls";
                   break;
               case 'recopa':
                   $correos['recopa']['asunto']="SINE - RECOPA ".self::reportTitle($fecha)."-".date("g:i a");
                   $correos['recopa']['cuerpo']=Yii::app()->reportes->recopa($fecha, Utility::snull($_GET['id_filter_oper']));
                   $correos['recopa']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['recopa']['asunto'].".xls";
                   break;
            }  
        }
        $tiempo=30*count($correos);
        ini_set('max_execution_time', $tiempo);
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
        $this->letra=Log::preliminar($_GET['datepicker']);
        $fecha=$grupo=$fecha_to=null;
        $archivos=array();
        if(isset($_GET['datepicker']))
        {
            $fecha=(string)$_GET['datepicker'];
            if(($_GET['id_periodo'])!=NULL)  $fecha_from=Reportes::define_fecha_from($_GET['id_periodo'],$fecha);       
            if(($_GET['grupo'])!=NULL)  $grupo=Reportes::define_grupo($_GET['grupo']);       
            if(isset($_GET['No_prov'])) $no_prov=SOA::define_prov($_GET['No_prov'],$grupo,$fecha);     
            if(isset($_GET['No_disp'])) $no_disp=Reportes::define_disp($_GET['No_disp'],$_GET['tipo_report'],$grupo,$fecha);
            
            switch ($_GET['tipo_report']) {
              case 'soa':
                   $archivos['soa']['nombre']="SINE - SOA".self::reportTitle($fecha)."-".date("g:i a");
                   $archivos['soa']['cuerpo']=Yii::app()->reportes->SOA($grupo,$fecha,$no_disp,$no_prov,$_GET['grupo']);
                   break;
              case 'balance':
                   $archivos['balance']['nombre']="SINE - BALANCE".self::reportTitle($fecha)."-".date("g:i a");
                   $archivos['balance']['cuerpo']=Yii::app()->reportes->balance_report($grupo,$fecha,$no_disp,$_GET['grupo']);
                   break;
              case 'refac':
                   $archivos['refac']['nombre']="SINE - REFAC ".Reportes::define_num_dias($fecha_from, $fecha)." ".str_replace("-","",$fecha_from).self::reportTitle($fecha)."-".date("g:i a");
                   $archivos['refac']['cuerpo']=Yii::app()->reportes->refac($fecha_from,$fecha,"REFAC");
                   break;
              case 'recredi':
                   $archivos['recredi']['nombre']="SINE - RECREDI ".self::reportTitle($fecha)."-".date("g:i a");
                   $archivos['recredi']['cuerpo']=Yii::app()->reportes->recredi($fecha);
                   break;
              case 'refi_prov':
                   $archivos['refi_prov']['nombre']="SINE - REPROV ".Reportes::define_num_dias($fecha_from, $fecha)." ".str_replace("-","",$fecha_from).self::reportTitle($fecha)."-".date("g:i a");
                   $archivos['refi_prov']['cuerpo']=Yii::app()->reportes->refi_prov($fecha_from,$fecha,"REFI PROV");
                   break;
              case 'recopa':
                  var_dump($_GET['tipo_report']);
                   $archivos['recopa']['nombre']="SINE - RECOPA ".self::reportTitle($fecha)."-".date("g:i a");
                   $archivos['recopa']['cuerpo']=Yii::app()->reportes->recopa($fecha,'1');
                   break;
            }  
        }
        foreach($archivos as $key => $archivo)
        {
            $this->genExcel($archivo['nombre'],$archivo['cuerpo']);
        }
    }
    public function actionPrevia()
    {
        $this->vaciarAdjuntos();
        $this->letra=Log::preliminar($_GET['datepicker']);
        $fecha=$grupo=$fecha_to=null;
        $archivos=array();
        if(isset($_GET['datepicker']))
        {
            $fecha=(string)$_GET['datepicker'];
            if(($_GET['id_periodo'])!=NULL)  $fecha_from=Reportes::define_fecha_from($_GET['id_periodo'],$fecha);
            if(($_GET['grupo'])!=NULL)  $grupo=Reportes::define_grupo($_GET['grupo']);       
            if(isset($_GET['No_prov'])) $no_prov=SOA::define_prov($_GET['No_prov'],$grupo,$fecha);     
            if(isset($_GET['No_disp'])) $no_disp=Reportes::define_disp($_GET['No_disp'],$_GET['tipo_report'],$grupo,$fecha);
            
            switch ($_GET['tipo_report']) {
              case 'soa':
                   $archivos['soa']['cuerpo']=Yii::app()->reportes->SOA($grupo,$fecha,$no_disp,$no_prov,$_GET['grupo']);
                   break;
              case 'balance':
                   $archivos['balance']['cuerpo']=Yii::app()->reportes->balance_report($grupo,$fecha,$no_disp,$_GET['grupo']);
                   break;
              case 'refac':
                   $archivos['refac']['cuerpo']=Yii::app()->reportes->refac($fecha_from,$fecha,"REFAC");
                   break;
              case 'refi_prov':
                   $archivos['refi_prov']['cuerpo']=Yii::app()->reportes->refi_prov($fecha_from,$fecha,"REFI PROV");
                   break;
              case 'recredi':
                   $archivos['recredi']['cuerpo']=Yii::app()->reportes->recredi($fecha);
                   break;
              case 'recopa':
                   $archivos['recopa']['cuerpo']=Yii::app()->reportes->recopa($fecha, Utility::snull($_GET['id_filter_oper']));
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
        if(stripos($nombre,"Evolucion") || stripos($nombre,"Comercial"))
        {
            header("Location: /adjuntos/{$nombre}.xlsx");
        }
        else
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
}
?>
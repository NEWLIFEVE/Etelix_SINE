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
        $this->letra=Log::preliminar($_POST['fecha']);
        $fecha=$grupo=null;
        $correos=null;
        $user=UserIdentity::getEmail();
        if(isset($_POST['fecha']))
        {
             $fecha=(string)$_POST['fecha'];
            if(isset($_POST['grupo'])) $grupo=CarrierGroups::getID($_POST['grupo']);
//            if(isset($_POST['Si_prov'])) $Si_prov=Reportes::define_prov($_POST['Si_prov']);
            if(isset($_POST['Si_disp'])) $Si_disp=Reportes::define_disp($_POST['Si_disp']);
            
            switch ($_POST['tipo_report']) {
              case 'soa':
                   $correos['soa']['asunto']="SINE - ".$this->letra." SOA".self::reportTitle($fecha);
                   $correos['soa']['cuerpo']=Yii::app()->reportes->SOA($grupo,$fecha,$Si_disp);
                   $correos['soa']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['soa']['asunto'].".xls";
                   break;
              case 'balance':
                   $correos['balance']['asunto']="SINE - ".$this->letra." balance".self::reportTitle($fecha);
                   $correos['balance']['cuerpo']=Yii::app()->reportes->balance($grupo,$fecha,$Si_disp);
                   $correos['balance']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['balance']['asunto'].".xls";
                   break;
            }  
        }
        $tiempo=30*count($correos);
        ini_set('max_execution_time', $tiempo);
        foreach($correos as $key => $correo)
        { 
            //Esto es para que no descargue los archivos cuando se genere uno de estos reportes
            if(stripos($correo['asunto'],"Evolucion")==false && stripos($correo['asunto'],"Comercial")==false)
            {
                $this->genExcel($correo['asunto'],$correo['cuerpo'],false);
            }
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
        $this->letra=Log::preliminar($_GET['fecha']);
        $fecha=$grupo=null;
        $archivos=array();
        if(isset($_GET['fecha']))
        {
            $fecha=(string)$_GET['fecha'];
            if(isset($_GET['grupo']))   $grupo=CarrierGroups::getID($_GET['grupo']);      
//            if(isset($_GET['Si_prov'])) $Si_prov=SOA::define_prov($_GET['Si_prov']);
            if(isset($_GET['Si_disp'])) $Si_disp=Reportes::define_disp($_GET['Si_disp']);
            
            switch ($_GET['tipo_report']) {
              case 'soa':
                   $archivos['soa']['nombre']="SINE - ".$this->letra."SOA".self::reportTitle($fecha);
                   $archivos['soa']['cuerpo']=Yii::app()->reportes->SOA($grupo,$fecha,$Si_disp);
                   break;
              case 'balance':
                   $archivos['balance']['nombre']="SINE - ".$this->letra."balance".self::reportTitle($fecha);
                   $archivos['balance']['cuerpo']=Yii::app()->reportes->balance($grupo,$fecha,$Si_disp);
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
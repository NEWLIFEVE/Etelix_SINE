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
     * renderiza vista rutinarios
     * @access public
     */
    public function actionRutinarios()
    {
        $this->render('rutinarios');
    }

    /**
     * Renderiza vista personalizados
     * @access public
     */
    public function actionPersonalizados()
    {
        $this->render('personalizados');
    }

    /**
     * Renderiza vista especificos
     * @access public
     */
    public function actionEspecificos()
    {
        $this->render('especificos');
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
     * Action encargada de envuiar por mail el tipo de reporte seleccionado,
     * las especificaciones seran recibidas desde el array $_GET
     * @access public
     */
    public function actionMail()
    {
        $this->vaciarAdjuntos();
        $this->letra=Log::preliminar($_POST['startDate']);
        $startDate=$endingDate=$carrier=null;
        $correos=null;
        $user=UserIdentity::getEmail();
        if(isset($_POST['startDate']))
        {
            $startDate=(string)$_POST['startDate'];
            if(isset($_POST['endingDate'])) $endingDate=$_POST['endingDate'];
            if(isset($_POST['carrier'])) $carrier=$_POST['carrier'];
            //Ranking Compra Venta
            if(isset($_POST['lista']['compraventa']))
            {
                $correos['compraventa']['asunto']="RENOC".$this->letra." Ranking CompraVenta".self::reportTitle($startDate,$endingDate);
                $correos['compraventa']['cuerpo']=Yii::app()->reportes->RankingCompraVenta($startDate,$endingDate);
                $correos['compraventa']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['compraventa']['asunto'].".xls";
            }
            //Perdidas
            if(isset($_POST['lista']['perdidas']))
            {
                $correos['perdidas']['asunto']="RENOC".$this->letra." Perdidas".self::reportTitle($startDate,$endingDate);
                $correos['perdidas']['cuerpo']=Yii::app()->reportes->Perdidas($startDate);
                $correos['perdidas']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['perdidas']['asunto'].".xls";
            }
            // Alto Impacto Retail
            if(isset($_POST['lista']['AIR']))
            {
                $correos['AIR']['asunto']="RENOC".$this->letra." Alto Impacto RETAIL (+1$)".self::reportTitle($startDate,$endingDate);
                $correos['AIR']['cuerpo']=Yii::app()->reportes->AltoIMpactoRetail($startDate);
                $correos['AIR']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['AIR']['asunto'].".xls";
            }
            //Alto Impacto +10$ Completo
            if(isset($_POST['lista']['AI10']))
            {
                $correos['AI10']['asunto']="RENOC".$this->letra." Alto Impacto (+10$)".self::reportTitle($startDate,$endingDate);
                $correos['AI10']['cuerpo']=Yii::app()->reportes->AltoImpacto($startDate,$endingDate,true);
                $correos['AI10']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['AI10']['asunto'].".xls";
            }
            //Alto Impacto +10$ Resumido
            if(isset($_POST['lista']['AI10R']))
            {
                $correos['AI10R']['asunto']="RENOC".$this->letra." Alto Impacto Resumido (+10$)".self::reportTitle($startDate,$endingDate);
                $correos['AI10R']['cuerpo']=Yii::app()->reportes->AltoImpacto($startDate,$endingDate,false);
                $correos['AI10R']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['AI10R']['asunto'].".xls";
            }
            //Alto Impacto +10$ por Vendedor
            if(isset($_POST['lista']['AI10V']))
            {
                $correos['AI10V']['asunto']="RENOC".$this->letra." Alto Impacto (+10$) por Vendedor".self::reportTitle($startDate,$endingDate);
                $correos['AI10V']['cuerpo']=Yii::app()->reportes->AltoImpactoVendedor($startDate);
                $correos['AI10V']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['AI10V']['asunto'].".xls";
            }
            //Posicion Neta
            if(isset($_POST['lista']['PN']))
            {
                $correos['PN']['asunto']="RENOC".$this->letra." Posicion Neta".self::reportTitle($startDate,$endingDate);
                $correos['PN']['cuerpo']=Yii::app()->reportes->posicionNeta($startDate);
                $correos['PN']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['PN']['asunto'].".xls";
            }
            //Posicion Neta por vendedor
            if(isset($_POST['lista']['PNV']))
            {
                $correos['PNV']['asunto']="RENOC".$this->letra." Posicion Neta por Vendedor".self::reportTitle($startDate,$endingDate);
                $correos['PNV']['cuerpo']=Yii::app()->reportes->PNV($startDate);
                $correos['PNV']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['PNV']['asunto'].".xls";
            }
            //Arbol de Trafico Destinos Internal
            if(isset($_POST['lista']['ADI']))
            {
                $correos['ADI']['asunto']="RENOC".$this->letra." Arbol Destinos Internal".self::reportTitle($startDate,$endingDate);
                $correos['ADI']['cuerpo']=Yii::app()->reportes->ArbolDestino($startDate,false);
                $correos['ADI']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['ADI']['asunto'].".xls";
            }
            //Arbol de Trafico Destino External
            if(isset($_POST['lista']['ADE']))
            {
                $correos['ADE']['asunto']="RENOC".$this->letra." Arbol Destinos External".self::reportTitle($startDate,$endingDate);
                $correos['ADE']['cuerpo']=Yii::app()->reportes->ArbolDestino($startDate,true);
                $correos['ADE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['ADE']['asunto'].".xls";
            }
            //Arbol de Trafico Clientes Internal
            if(isset($_POST['lista']['ACI']))
            {
                $correos['ACI']['asunto']="RENOC".$this->letra." Arbol Clientes Internal".self::reportTitle($startDate,$endingDate);
                $correos['ACI']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,true,false);
                $correos['ACI']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['ACI']['asunto'].".xls";
            }
            //Arbol de Trafico Clientes External
            if(isset($_POST['lista']['ACE']))
            {
                $correos['ACE']['asunto']="RENOC".$this->letra." Arbol Clientes External".self::reportTitle($startDate,$endingDate);
                $correos['ACE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,true,true);
                $correos['ACE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR."RENOC".$this->letra." Arbol Clientes External al ".str_replace("-","",$startDate).".xls";
            }
            //Arbol de Trafico Proveedores Internal
            if(isset($_POST['lista']['API']))
            {
                $correos['API']['asunto']="RENOC".$this->letra." Arbol Proveedores Internal".self::reportTitle($startDate,$endingDate);
                $correos['API']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,false,false);
                $correos['API']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['API']['asunto'].".xls";
            }
            //Arbol de Trafico Proveedores External
            if(isset($_POST['lista']['APE']))
            {
                $correos['APE']['asunto']="RENOC".$this->letra." Arbol Proveedores External".self::reportTitle($startDate,$endingDate);
                $correos['APE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,false,true);
                $correos['APE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['APE']['asunto'].".xls";
            }
            //Distribucion Comercial
            if(isset($_POST['lista']['DC']))
            {
                $correos['DC']['asunto']="RENOC".$this->letra." Distribucion Comercial";
                $correos['DC']['cuerpo']=Yii::app()->reportes->DistribucionComercial($correos['DC']['asunto'].".xlsx");
                $correos['DC']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['DC']['asunto'].".xlsx";
            }
            if(isset($_POST['lista']['Ev']))
            {
                $correos['Ev']['asunto']="RENOC".$this->letra." Evolucion".self::reportTitle($startDate,$endingDate);
                $correos['Ev']['cuerpo']=Yii::app()->reportes->Evolucion($startDate,$correos['Ev']['asunto'].".xlsx");
                $correos['Ev']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['Ev']['asunto'].".xlsx";
            }
            if(isset($_POST['lista']['calidad']))
            {
                $correos['calidad']['asunto']="RENOC Calidad ".$carrier.self::reportTitle($startDate,$endingDate);
                $correos['calidad']['cuerpo']=Yii::app()->reportes->Calidad($startDate,$endingDate,Carrier::model()->find("name=:nombre",array(':nombre'=>$carrier))->id);
                $correos['calidad']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['calidad']['asunto'].".xls";
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
     * @access public
     */
    public function actionExcel()
    {
        $this->vaciarAdjuntos();
        $this->letra=Log::preliminar($_GET['startDate']);
        $startDate=$endingDate=$carrier=null;
        $archivos=array();
        if(isset($_GET['startDate']))
        {
            $startDate=(string)$_GET['startDate'];
            if(isset($_GET['endingDate'])) $endingDate=$_GET['endingDate'];
            if(isset($_GET['carrier'])) $carrier=$_GET['carrier'];
            if(isset($_GET['lista']['compraventa']))
            {
                $archivos['compraventa']['nombre']="RENOC".$this->letra." Ranking CompraVenta".self::reportTitle($startDate,$endingDate);
                $archivos['compraventa']['cuerpo']=Yii::app()->reportes->RankingCompraVenta($startDate,$endingDate);
            }
            if(isset($_GET['lista']['perdidas']))
            {
                $archivos['perdidas']['nombre']="RENOC".$this->letra." Perdidas".self::reportTitle($startDate,$endingDate);
                $archivos['perdidas']['cuerpo']=Yii::app()->reportes->Perdidas($startDate);
            }
            if(isset($_GET['lista']['AIR']))
            {
                $archivos['AIR']['nombre']="RENOC".$this->letra." Alto Impacto RETAIL (+1$)".self::reportTitle($startDate,$endingDate);
                $archivos['AIR']['cuerpo']=Yii::app()->reportes->AltoIMpactoRetail($startDate);
            }
            //Alto Impacto Completo
            if(isset($_GET['lista']['AI10']))
            {
                $archivos['AI10']['nombre']="RENOC".$this->letra." Alto Impacto (+10$)".self::reportTitle($startDate,$endingDate);
                $archivos['AI10']['cuerpo']=Yii::app()->reportes->AltoImpacto($startDate,$endingDate,true);
            }
            //Alto Impacto Resumen
            if(isset($_GET['lista']['AI10R']))
            {
                $archivos['AI10R']['nombre']="RENOC".$this->letra." Alto Impacto Resumido (+10$)".self::reportTitle($startDate,$endingDate);
                $archivos['AI10R']['cuerpo']=Yii::app()->reportes->AltoImpacto($startDate,$endingDate,false);
            }
            if(isset($_GET['lista']['AI10V']))
            {
                $archivos['AI10V']['nombre']="RENOC".$this->letra." Alto Impacto (+10$) por Vendedor".self::reportTitle($startDate,$endingDate);
                $archivos['AI10V']['cuerpo']=Yii::app()->reportes->AltoImpactoVendedor($startDate);
            } 
            if(isset($_GET['lista']['PN']))
            {
                $archivos['PN']['nombre']="RENOC".$this->letra." Posicion Neta".self::reportTitle($startDate,$endingDate);
                $archivos['PN']['cuerpo']=Yii::app()->reportes->posicionNeta($startDate);
            }
            if(isset($_GET['lista']['PNV']))
            {
                $archivos['PNV']['nombre']="RENOC".$this->letra." Posicion Neta por Vendedor".self::reportTitle($startDate,$endingDate);
                $archivos['PNV']['cuerpo']=Yii::app()->reportes->PosicionNetaVendedor($startDate);
            }
            //Arbol de Trafico Destinos Internal
            if(isset($_GET['lista']['ADI']))
            {
                $archivos['ADI']['nombre']="RENOC".$this->letra." Arbol Destinos Internal".self::reportTitle($startDate,$endingDate);
                $archivos['ADI']['cuerpo']=Yii::app()->reportes->ArbolDestino($startDate,false);
            }
            //Arbol de Trafico Destino External
            if(isset($_GET['lista']['ADE']))
            {
                $archivos['ADE']['nombre']="RENOC".$this->letra." Arbol Destinos External".self::reportTitle($startDate,$endingDate);
                $archivos['ADE']['cuerpo']=Yii::app()->reportes->ArbolDestino($startDate,true);
            }
            //Arbol de Trafico Clientes Internal
            if(isset($_GET['lista']['ACI']))
            {
                $archivos['ACI']['nombre']="RENOC".$this->letra." Arbol Clientes Internal".self::reportTitle($startDate,$endingDate);
                $archivos['ACI']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,true,false);
            }
            //Arbol de Trafico Clientes External
            if(isset($_GET['lista']['ACE']))
            {
                $archivos['ACE']['nombre']="RENOC".$this->letra." Arbol Clientes External".self::reportTitle($startDate,$endingDate);
                $archivos['ACE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,true,true);
            }
            //Arbol de Trafico Proveedores Internal
            if(isset($_GET['lista']['API']))
            {
                $archivos['API']['nombre']="RENOC".$this->letra." Arbol Proveedores Internal".self::reportTitle($startDate,$endingDate);
                $archivos['API']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,false,false);
            }
            //Arbol de Trafico Proveedores External
            if(isset($_GET['lista']['APE']))
            {
                $archivos['APE']['nombre']="RENOC".$this->letra." Arbol Proveedores External".self::reportTitle($startDate,$endingDate);
                $archivos['APE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,false,true);
            }
            //Distribucion Comercial
            if(isset($_GET['lista']['DC']))
            {
                $archivos['DC']['nombre']="RENOC".$this->letra." Distribucion Comercial";
                $archivos['DC']['cuerpo']=Yii::app()->reportes->DistribucionComercial($archivos['DC']['nombre'].".xlsx");
            }
            if(isset($_GET['lista']['Ev']))
            {
                $archivos['Ev']['nombre']="RENOC".$this->letra." Evolucion".self::reportTitle($startDate,$endingDate);
                $archivos['Ev']['cuerpo']=Yii::app()->reportes->Evolucion($startDate,$archivos['Ev']['nombre'].".xlsx");
            }
            if(isset($_GET['lista']['calidad']))
            {
                $archivos['calidad']['nombre']="RENOC Calidad ".$carrier.self::reportTitle($startDate,$endingDate);
                $archivos['calidad']['cuerpo']=Yii::app()->reportes->Calidad($startDate,$endingDate,Carrier::model()->find("name=:nombre",array(':nombre'=>$carrier))->id);
            }
        }
        foreach($archivos as $key => $archivo)
        {
            $this->genExcel($archivo['nombre'],$archivo['cuerpo']);
        }
    }

    /**
     * Action encargada de enviar por mail el tipo de reporte seleccionado,
     * las especificaciones seran recibidas desde el array $_GET
     * @access public
     */
    public function actionMaillista()
    {
        $this->vaciarAdjuntos();
        $this->letra=Log::preliminar($_POST['startDate']);
        $startDate=$endingDate=$carrier=null;
        $correos=null;
        $user="renoc@etelix.com";
        if(isset($_POST['startDate']))
        {
            $startDate=(string)$_POST['startDate'];
            if(isset($_POST['endingDate'])) $endingDate=$_POST['endingDate'];
            if(isset($_POST['carrier'])) $carrier=$_POST['carrier'];
            //Ranking Compra Venta
            if(isset($_POST['lista']['compraventa']))
            {
                $correos['compraventa']['asunto']="RENOC".$this->letra." Ranking CompraVenta".self::reportTitle($startDate,$endingDate);
                $correos['compraventa']['cuerpo']=Yii::app()->reportes->RankingCompraVenta($startDate,$endingDate);
                $correos['compraventa']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['compraventa']['asunto'].".xls";
            }
            //Perdidas
            if(isset($_POST['lista']['perdidas']))
            {
                $correos['perdidas']['asunto']="RENOC".$this->letra." Perdidas".self::reportTitle($startDate,$endingDate);
                $correos['perdidas']['cuerpo']=Yii::app()->reportes->Perdidas($startDate);
                $correos['perdidas']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['perdidas']['asunto'].".xls";
            }
            // Alto Impacto Retail
            if(isset($_POST['lista']['AIR']))
            {
                $correos['AIR']['asunto']="RENOC".$this->letra." Alto Impacto RETAIL (+1$)".self::reportTitle($startDate,$endingDate);
                $correos['AIR']['cuerpo']=Yii::app()->reportes->AltoIMpactoRetail($startDate);
                $correos['AIR']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['AIR']['asunto'].".xls";
            }
            //Alto Impacto +10$ Completo
            if(isset($_POST['lista']['AI10']))
            {
                $correos['AI10']['asunto']="RENOC".$this->letra." Alto Impacto (+10$)".self::reportTitle($startDate,$endingDate);
                $correos['AI10']['cuerpo']=Yii::app()->reportes->AltoImpacto($startDate,$endingDate,true);
                $correos['AI10']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['AI10']['asunto'].".xls";
            }
            //Alto Impacto +10$ Resumen
            if(isset($_POST['lista']['AI10R']))
            {
                $correos['AI10R']['asunto']="RENOC".$this->letra." Alto Impacto Resumido (+10$)".self::reportTitle($startDate,$endingDate);
                $correos['AI10R']['cuerpo']=Yii::app()->reportes->AltoImpacto($startDate,$endingDate,false);
                $correos['AI10R']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['AI10R']['asunto'].".xls";
            }
            //Alto Impacto +10$ por Vendedor
            if(isset($_POST['lista']['AI10V']))
            {
                $correos['AI10V']['asunto']="RENOC".$this->letra." Alto Impacto (+10$) por Vendedor".self::reportTitle($startDate,$endingDate);
                $correos['AI10V']['cuerpo']=Yii::app()->reportes->AltoImpactoVendedor($startDate);
                $correos['AI10V']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['AI10V']['asunto'].".xls";
            }
            //Posicion Neta
            if(isset($_POST['lista']['PN']))
            {
                $correos['PN']['asunto']="RENOC".$this->letra." Posicion Neta".self::reportTitle($startDate,$endingDate);
                $correos['PN']['cuerpo']=Yii::app()->reportes->posicionNeta($startDate);
                $correos['PN']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['PN']['asunto'].".xls";
            }
            //Posicion Neta por vendedor
            if(isset($_POST['lista']['PNV']))
            {
                $correos['PNV']['asunto']="RENOC".$this->letra." Posicion Neta por Vendedor".self::reportTitle($startDate,$endingDate);
                $correos['PNV']['cuerpo']=Yii::app()->reportes->PosicionNetaVendedor($startDate);
                $correos['PNV']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['PNV']['asunto'].".xls";
            }
            //Arbol de Trafico Destinos Internal
            if(isset($_POST['lista']['ADI']))
            {
                $correos['ADI']['asunto']="RENOC".$this->letra." Arbol Destinos Internal".self::reportTitle($startDate,$endingDate);
                $correos['ADI']['cuerpo']=Yii::app()->reportes->ArbolDestino($startDate,false);
                $correos['ADI']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['ADI']['asunto'].".xls";
            }
            //Arbol de Trafico Destino External
            if(isset($_POST['lista']['ADE']))
            {
                $correos['ADE']['asunto']="RENOC".$this->letra." Arbol Destinos External".self::reportTitle($startDate,$endingDate);
                $correos['ADE']['cuerpo']=Yii::app()->reportes->ArbolDestino($startDate,true);
                $correos['ADE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['ADE']['asunto'].".xls";
            }
            //Arbol de Trafico Clientes Internal
            if(isset($_POST['lista']['ACI']))
            {
                $correos['ACI']['asunto']="RENOC".$this->letra." Arbol Clientes Internal".self::reportTitle($startDate,$endingDate);
                $correos['ACI']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,true,false);
                $correos['ACI']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['ACI']['asunto'].".xls";
            }
            //Arbol de Trafico Clientes External
            if(isset($_POST['lista']['ACE']))
            {
                $correos['ACE']['asunto']="RENOC".$this->letra." Arbol Clientes External".self::reportTitle($startDate,$endingDate);
                $correos['ACE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,true,true);
                $correos['ACE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['ACE']['asunto'].".xls";
            }
            //Arbol de Trafico Proveedores Internal
            if(isset($_POST['lista']['API']))
            {
                $correos['API']['asunto']="RENOC".$this->letra." Arbol Proveedores Internal".self::reportTitle($startDate,$endingDate);
                $correos['API']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,false,false);
                $correos['API']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['API']['asunto'].".xls";
            }
            //Arbol de Trafico Proveedores External
            if(isset($_POST['lista']['APE']))
            {
                $correos['APE']['asunto']="RENOC".$this->letra." Arbol Proveedores External".self::reportTitle($startDate,$endingDate);
                $correos['APE']['cuerpo']=Yii::app()->reportes->ArbolTrafico($startDate,false,true);
                $correos['APE']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['APE']['asunto'].".xls";
            }
            //Distribucion Comercial
            if(isset($_POST['lista']['DC']))
            {
                $correos['DC']['asunto']="RENOC".$this->letra." Distribucion Comercial";
                $correos['DC']['cuerpo']=Yii::app()->reportes->DistribucionComercial($correos['DC']['asunto'].".xlsx");
                $correos['DC']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['DC']['asunto'].".xlsx";
            }
            if(isset($_POST['lista']['Ev']))
            {
                $correos['Ev']['asunto']="RENOC".$this->letra." Evolucion".self::reportTitle($startDate,$endingDate);
                $correos['Ev']['cuerpo']=Yii::app()->reportes->Evolucion($startDate,$correos['Ev']['asunto'].".xlsx");
                $correos['Ev']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['Ev']['asunto'].".xlsx";
            }
            if(isset($_POST['lista']['calidad']))
            {
                $correos['calidad']['asunto']="RENOC Calidad ".$carrier.self::reportTitle($startDate,$endingDate);
                $correos['calidad']['cuerpo']=Yii::app()->reportes->Calidad($startDate,$endingDate,Carrier::model()->find("name=:nombre",array(':nombre'=>$carrier))->id);
                $correos['calidad']['ruta']=Yii::getPathOfAlias('webroot.adjuntos').DIRECTORY_SEPARATOR.$correos['calidad']['asunto'].".xls";
            }
        }
        $tiempo=30*count($correos);
        ini_set('max_execution_time', $tiempo);
        foreach($correos as $key => $correo)
        {
            //esto es para evitar que cuando sea alguno de estos reportes no descargue el archivo
            if(stripos($correo['asunto'],"Evolucion")==false && stripos($correo['asunto'],"Comercial")==false)
            {
                $this->genExcel($correo['asunto'],$correo['cuerpo'],false);
            }
            if(stripos($correo['asunto'], "RETAIL"))
            {
                $lista=array('CarlosBuona@etelix.com','sig@etelix.com');
                Yii::app()->mail->enviar($correo['cuerpo'], $user, $correo['asunto'],$correo['ruta'],$lista);
            }
            elseif (stripos($correo['asunto'], "Calidad"))
            {
                $userDif="ceo@etelix.com";
                $lista=array('alvaroquitana@etelix.com','eykiss@etelix.com','txadmin@netuno.net','sig@etelix.com');
                Yii::app()->mail->enviar($correo['cuerpo'], $userDif, $correo['asunto'],$correo['ruta'],$lista);
            }
            else
            {
                $lista=array('sig@etelix.com');
                Yii::app()->mail->enviar($correo['cuerpo'], $user, $correo['asunto'],$correo['ruta'],$lista);
            }
        }
        echo "Mensaje Enviado";
    }

    /**
     * @access public
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
     * Metodo encargado de ajustar nombre de archivo dependiendo de las fechas,
     * si se le pasa una sola fecha, retornará algo como: al $fecha
     * si se le pasan dos fechas retornará algo como: desde $fecha hasta $fecha
     * la las fechas completan principio y fin de un mismo mes retornará algom como: al $mes
     * @access private
     * @static
     * @param date $start fecha incial
     * @param date $end fecha fin
     * @return string con el texto apropiado
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




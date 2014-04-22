<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language;?>">
    <head>
        <!--<title><?php echo CHtml::encode($this->pageTitle); ?></title>-->

        <title>SINE</title>
        <meta charset="<?php echo Yii::app()->charset;?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/yii.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/css/bootstrap-responsive.min.css" />
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/metro-bootstrap.css" rel="stylesheet" type="text/css">
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/metro-bootstrap-responsive.css" rel="stylesheet" type="text/css">
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/docs.css" rel="stylesheet" type="text/css">
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/prettify/prettify.css" rel="stylesheet">
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico" type="image/x-icon"/>
    </head>
    <body class="metro">
        <header class="bg-dark">
            <!-- AQUÍ COMIENZA EL MENU -->
            <div class="navigation-bar dark">
                <div class="navigation-bar-content container">
                    <a href="/" class="element titlePage">
                        <span class="icon-list"></span>
                        <?php echo Yii::app()->name; ?>
                    </a>
                    <?php if (!Yii::app()->user->isGuest): ?>
                    <span class="element-divider"></span>
                        <label id='showProvisions'class="element">
                            Provisiones
                        </label>
                    
                        <span class="element-divider"></span>
                        <?php echo CHtml::link('<i class="icon-locked on-right on-left"></i> Logout ('.Yii::app()->user->name.')', array('/site/logout'), array('class'=>'element')); ?>
                        <span class="element-divider"></span>
                    <?php endif; ?> 
                </div>
            </div>
            <!-- /MENU -->
        </header>
        <div class="page">
            <div class="page-region">
                <div class="page-region-content">
                    <div class="grid">
                        <div class="row">
                            <?php echo $content; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="footer">
                Copyright &copy; <?php echo date('Y'); ?> SACET All Rights Reserved. Version 1.2.9.1
            </div>
            <div class="clear"></div>
        </div>
        
        <script>
        var _root_ = "<?php echo Yii::app()->getBaseUrl(true) . '/'; ?>";
        </script>
        <!-- Javascript - jQuery -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.widget.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.mousewheel.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/prettify/prettify.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/metro/metro-dropdown.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/docs.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/github.info.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.ui.datepicker-es.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery-ui-timepicker-addon.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo Yii::app()->baseUrl; ?>/js/sine.js"></script>
        <!--<script src="<?php // echo Yii::app()->baseUrl; ?>/js/views.js"></script>-->
        <!--<script>
                var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
                (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
                        g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
                        s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>-->
    </body>
</html>
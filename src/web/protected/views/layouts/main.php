<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language;?>">
    <head>
       <!-- <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta charset="<?php echo Yii::app()->charset;?>">
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/metro-bootstrap.css" rel="stylesheet" type="text/css">
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/metro-bootstrap-responsive.css" rel="stylesheet" type="text/css">
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/docs.css" rel="stylesheet" type="text/css">
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/prettify/prettify.css" rel="stylesheet">
        <?php Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl().'/jui/css/base/jquery-ui.css'); ?>
        
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.widget.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.mousewheel.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/prettify/prettify.js"></script>
        <!--COMENTARIO TEST-->
        <!-- Local JavaScript -->
        <!--<script src="<?php // echo Yii::app()->theme->baseUrl; ?>/js/metro/metro-loader.js"></script>-->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/metro/metro-dropdown.js"></script>
        <!-- Local JavaScript -->
        <!--<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/docs.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/github.info.js"></script>
        
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.ui.datepicker-es.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery-ui-timepicker-addon.js"></script>
        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico" type="image/x-icon"/> -->
    </head>
    <body class="metro">
        <header class="bg-dark">
            <!-- AQUÍ COMIENZA EL MENU -->
            <div class="navigation-bar dark">
                <div class="navigation-bar-content container">
                    <a href="/" class="element">
                        <span class="icon-grid-view"></span>
                        <?php echo Yii::app()->name; ?>
                        <sup>ETELIX</sup>
                    </a>
                    <?php if (!Yii::app()->user->isGuest): ?>
                    <span class="element-divider"></span>
                    <?php echo CHtml::link('<i class="icon-home on-right on-left"></i> Home', array('/site/index'), array('class' => 'element')); ?>
                    <?php echo CHtml::link('<i class="icon-locked on-right on-left"></i> Logout', array('/site/logout'), array('class' => 'element')); ?>
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
                Copyright &copy; <?php echo date('Y'); ?> SACET All Rights Reserved.
            </div>
            <div class="clear"></div>
        </div>
        <script>
            var _root_ = "<?php echo Yii::app()->getBaseUrl(true) . '/'; ?>";
        </script>
    </body>
</html>
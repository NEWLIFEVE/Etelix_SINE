<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language;?>">
    <head>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta charset="<?php echo Yii::app()->charset;?>">
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/metro-bootstrap.css" rel="stylesheet" type="text/css">
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/metro-bootstrap-responsive.css" rel="stylesheet" type="text/css">
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/docs.css" rel="stylesheet" type="text/css">
        <link href="<?php echo Yii::app()->theme->baseUrl; ?>/js/prettify/prettify.css" rel="stylesheet">
        
        <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
        <?php Yii::app()->clientScript->registerCoreScript('jquery.ui'); ?>
        <?php Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl().'/jui/css/base/jquery-ui.css'); ?>
        <?php // Yii::app()->clientScript->registerCssFile(Yii::app()->assetManager->publish(Yii::app()->basePath . '/vendors/jquery.ui/redmond/').'/jquery-ui-1.8.11.custom.css', 'screen'); ?>
        
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.widget.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.mousewheel.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/prettify/prettify.js"></script>
        <!--COMENTARIO TEST-->
        <!-- Local JavaScript -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/metro/metro-dropdown.js"></script>
        <!-- Local JavaScript -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/docs.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/github.info.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery.ui.datepicker-es.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery/jquery-ui-timepicker-addon.js"></script>
        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico" type="image/x-icon"/>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    </head>
    <body class="metro">
        <header class="bg-dark">
            <!--
            AQUÍ COMIENZA EL MENU
            -->
            <div class="navigation-bar dark">
                <div class="navigation-bar-content container">
                    <a href="/" class="element titlePage"><span class="icon-list"> </span> <?php echo Yii::app()->name; ?> <!--<sup>ETELIX</sup>--></a>
                    
                    <?php if (!Yii::app()->user->isGuest): ?>
                        <span class="element-divider"></span>

                        <?php // echo CHtml::link('<i class="icon-home on-right on-left"></i> Home', array('/site/index'), array('class' => 'element')); ?>
                    
                                                                        <!--<div class="element">-->
                                                                        <?php // echo CHtml::link('<i class="icon-box-add on-right on-left"></i> Tickets', '#', array('class' => 'dropdown-toggle')); ?>
                                                                        <?php // $this->widget('zii.widgets.CMenu',array(
                                                //                            'items'=>array(
                                                //                                    array('label'=>'My tickets', 'url'=>array('/tickets/admin')),
                                                //                                    array('label'=>'Open ticket', 'url'=>array('/tickets/create')),
                                                //                            ),
                                                //                            'htmlOptions' => array(
                                                //                                'class' => 'dropdown-menu',
                                                //                                'id' => 'base-submenu',
                                                //                                'data-role' => 'dropdown'
                                                //                                ),

                                                //                        )); ?>
                                                                        <!--</div>-->
                        <?php // if(Yii::app()->getSession()->get('role') === 1) echo CHtml::link('<i class="icon-user on-right on-left"></i> Usuarios', array('/usuarios/index'), array('class' => 'element')); ?>
                        <?php echo CHtml::link('<i class="icon-locked on-right on-left"></i> Logout ('.Yii::app()->user->name.')', array('/site/logout'), array('class' => 'element')); ?>
                        <span class="element-divider"></span>
                    <?php endif; ?> 
                </div>
            </div><!-- /MENU -->
        </header>
        <div class="page">
            <div class="page-region">
                <div class="page-region-content">
<!--                    <h1 style="border-bottom: 1px solid silver">
                        <a href="/"><i class="icon-arrow-left-3 fg-darker smaller"></i></a>
                        <?php // echo Yii::app()->name; ?><small class="on-right">ETELIX</small>
                    </h1>-->
                    <div class="grid">
                        <div class="row">
                            <?php echo $content; ?>
                        </div>
                    </div>
                </div>
            </div>
    
        <div id="footer">
            Copyright &copy; <?php echo date('Y'); ?> SACET All Rights Reserved. Version 1.0
        </div>
            
    <div class="clear"></div>
        </div>
        <script>
        var _root_ = "<?php echo Yii::app()->getBaseUrl(true) . '/'; ?>";
        </script>
        <!-- Javascript - jQuery -->
	<script src="http://code.jquery.com/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="<?php echo Yii::app()->baseUrl; ?>/js/jquery-2.0.2.min.js"><\/script>')</script>
	<script src="<?php echo Yii::app()->baseUrl; ?>/js/sine.js"></script>
	<script src="<?php echo Yii::app()->baseUrl; ?>/js/views.js"></script>
        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script>
		var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
		(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
			g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g,s)}(document,'script'));
	</script>
    </body>
</html>
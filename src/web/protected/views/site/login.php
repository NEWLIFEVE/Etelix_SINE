<?php
/**
 * @var $this SiteController
 * @var $model LoginForm
 * @var $form CActiveForm
 */
$this->pageTitle=Yii::app()->name.' - Login';
$this->breadcrumbs=array(
	'Login',
	);
?>
<div class="bodylogin">
	<div class="cuadro">
                <p class="titulo">
			<font class="SI">SI</font><font class="NE">NE</font>
		</p>
		<div class="cuadro_login">
			<p class="login_titulo">
				<font color="Gray"  size="4">Ingrese sus Datos</font>
			</p>
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'login-form',
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
					),
				)
			);
			?>
			<div class="login">
				<?php echo $form->labelEx($model,''); ?>
				<?php echo $form->textField($model,'username',array('placeholder' => 'usuario')); ?>
			</div>
			<div class="login">
				<?php echo $form->labelEx($model,''); ?>
				<?php echo $form->passwordField($model,'password',array('placeholder' => 'contraseña')); ?>
			</div>
			<div class="row rememberMe">
				<div class="botonLogin">
					<div id="remember"class="input-control checkbox" data-role="input-control">
                                            <label>
                                            <?php echo $form->checkBox($model,'rememberMe'); ?>
                                            <span class="check"></span>  Recuerdame
                                            <?php echo $form->error($model,'rememberMe'); ?>
                                            </label>
                                        </div>
					 <?php echo CHtml::submitButton('Ingresar', array('class' => 'primary large')); ?>
					<?php echo $form->error($model,'username'); ?>
					<?php echo $form->error($model,'password'); ?>
				</div>
			</div>
			<?php $this->endWidget(); ?>
		</div>
		<div id="minifooter">
			<font color="white">Copyrigth 2013 by</font> <a id="enlacerenoc" href="http://www.sacet.com.ve/">www.sacet.com.ve</a>
			<font color="white"> Legal privacy</font>
		</div>
	</div>
</div>
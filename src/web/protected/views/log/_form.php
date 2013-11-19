<?php
/* @var $this LogController */
/* @var $model Log */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'log-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php echo $form->textField($model,'date'); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hour'); ?>
		<?php echo $form->textField($model,'hour'); ?>
		<?php echo $form->error($model,'hour'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_log_action'); ?>
		<?php echo $form->textField($model,'id_log_action'); ?>
		<?php echo $form->error($model,'id_log_action'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_users'); ?>
		<?php echo $form->textField($model,'id_users'); ?>
		<?php echo $form->error($model,'id_users'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description_date'); ?>
		<?php echo $form->textField($model,'description_date'); ?>
		<?php echo $form->error($model,'description_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_esp'); ?>
		<?php echo $form->textField($model,'id_esp'); ?>
		<?php echo $form->error($model,'id_esp'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
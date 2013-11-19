<?php
/* @var $this LogController */
/* @var $data Log */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hour')); ?>:</b>
	<?php echo CHtml::encode($data->hour); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_log_action')); ?>:</b>
	<?php echo CHtml::encode($data->id_log_action); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_users')); ?>:</b>
	<?php echo CHtml::encode($data->id_users); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description_date')); ?>:</b>
	<?php echo CHtml::encode($data->description_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_esp')); ?>:</b>
	<?php echo CHtml::encode($data->id_esp); ?>
	<br />


</div>
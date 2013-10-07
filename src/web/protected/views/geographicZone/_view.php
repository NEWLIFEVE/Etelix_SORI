<?php
/* @var $this GeographicZoneController */
/* @var $data GeographicZone */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name_zona')); ?>:</b>
	<?php echo CHtml::encode($data->name_zona); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('color_zona')); ?>:</b>
	<?php echo CHtml::encode($data->color_zona); ?>
	<br />


</div>
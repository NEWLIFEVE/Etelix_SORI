<?php
/* @var $this DestinationSupplierController */
/* @var $data DestinationSupplier */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_carrier')); ?>:</b>
	<?php echo CHtml::encode($data->id_carrier); ?>
	<br />


</div>
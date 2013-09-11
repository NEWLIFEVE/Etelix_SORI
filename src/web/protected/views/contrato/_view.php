<?php
/* @var $this ContratoController */
/* @var $data Contrato */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sign_date')); ?>:</b>
	<?php echo CHtml::encode($data->sign_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('production_date')); ?>:</b>
	<?php echo CHtml::encode($data->production_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end_date')); ?>:</b>
	<?php echo CHtml::encode($data->end_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_carrier')); ?>:</b>
	<?php echo CHtml::encode($data->id_carrier); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_company')); ?>:</b>
	<?php echo CHtml::encode($data->id_company); ?>
	<br />


</div>
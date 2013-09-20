<?php
/* @var $this ContratoTerminoPagoController */
/* @var $data ContratoTerminoPago */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('start_date')); ?>:</b>
	<?php echo CHtml::encode($data->start_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('end_date')); ?>:</b>
	<?php echo CHtml::encode($data->end_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_contrato')); ?>:</b>
	<?php echo CHtml::encode($data->id_contrato); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_termino_pago')); ?>:</b>
	<?php echo CHtml::encode($data->id_termino_pago); ?>
	<br />


</div>
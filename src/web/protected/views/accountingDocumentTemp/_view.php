<?php
/* @var $this AccountingDocumentTempController */
/* @var $data AccountingDocumentTemp */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('issue_date')); ?>:</b>
	<?php echo CHtml::encode($data->issue_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('from_date')); ?>:</b>
	<?php echo CHtml::encode($data->from_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('to_date')); ?>:</b>
	<?php echo CHtml::encode($data->to_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('received_date')); ?>:</b>
	<?php echo CHtml::encode($data->received_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sent_date')); ?>:</b>
	<?php echo CHtml::encode($data->sent_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('doc_number')); ?>:</b>
	<?php echo CHtml::encode($data->doc_number); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('minutes')); ?>:</b>
	<?php echo CHtml::encode($data->minutes); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('amount')); ?>:</b>
	<?php echo CHtml::encode($data->amount); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('note')); ?>:</b>
	<?php echo CHtml::encode($data->note); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_type_accounting_document')); ?>:</b>
	<?php echo CHtml::encode($data->id_type_accounting_document); ?>
	<br />

	*/ ?>

</div>
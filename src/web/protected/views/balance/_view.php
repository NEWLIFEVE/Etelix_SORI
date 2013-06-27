<?php
/* @var $this BalanceController */
/* @var $data Balance */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_balance')); ?>:</b>
	<?php echo CHtml::encode($data->date_balance); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('minutes')); ?>:</b>
	<?php echo CHtml::encode($data->minutes); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('acd')); ?>:</b>
	<?php echo CHtml::encode($data->acd); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('asr')); ?>:</b>
	<?php echo CHtml::encode($data->asr); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('margin_percentage')); ?>:</b>
	<?php echo CHtml::encode($data->margin_percentage); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('margin_per_minute')); ?>:</b>
	<?php echo CHtml::encode($data->margin_per_minute); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('cost_per_minute')); ?>:</b>
	<?php echo CHtml::encode($data->cost_per_minute); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('revenue_per_min')); ?>:</b>
	<?php echo CHtml::encode($data->revenue_per_min); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pdd')); ?>:</b>
	<?php echo CHtml::encode($data->pdd); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('incomplete_calls')); ?>:</b>
	<?php echo CHtml::encode($data->incomplete_calls); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('complete_calls_ner')); ?>:</b>
	<?php echo CHtml::encode($data->complete_calls_ner); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('complete_calls')); ?>:</b>
	<?php echo CHtml::encode($data->complete_calls); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('calls_attempts')); ?>:</b>
	<?php echo CHtml::encode($data->calls_attempts); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('duration_real')); ?>:</b>
	<?php echo CHtml::encode($data->duration_real); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('duration_cost')); ?>:</b>
	<?php echo CHtml::encode($data->duration_cost); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ner02_efficient')); ?>:</b>
	<?php echo CHtml::encode($data->ner02_efficient); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ner02_seizure')); ?>:</b>
	<?php echo CHtml::encode($data->ner02_seizure); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('pdd_calls')); ?>:</b>
	<?php echo CHtml::encode($data->pdd_calls); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('revenue')); ?>:</b>
	<?php echo CHtml::encode($data->revenue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cost')); ?>:</b>
	<?php echo CHtml::encode($data->cost); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('margin')); ?>:</b>
	<?php echo CHtml::encode($data->margin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_change')); ?>:</b>
	<?php echo CHtml::encode($data->date_change); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_carrier')); ?>:</b>
	<?php echo CHtml::encode($data->id_carrier); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_destination')); ?>:</b>
	<?php echo CHtml::encode($data->id_destination); ?>
	<br />

	*/ ?>

</div>
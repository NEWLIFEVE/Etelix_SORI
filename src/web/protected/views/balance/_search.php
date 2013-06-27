<?php
/* @var $this BalanceController */
/* @var $model Balance */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_balance'); ?>
		<?php echo $form->textField($model,'date_balance'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'minutes'); ?>
		<?php echo $form->textField($model,'minutes'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'acd'); ?>
		<?php echo $form->textField($model,'acd'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'asr'); ?>
		<?php echo $form->textField($model,'asr'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'margin_percentage'); ?>
		<?php echo $form->textField($model,'margin_percentage'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'margin_per_minute'); ?>
		<?php echo $form->textField($model,'margin_per_minute'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cost_per_minute'); ?>
		<?php echo $form->textField($model,'cost_per_minute'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'revenue_per_min'); ?>
		<?php echo $form->textField($model,'revenue_per_min'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pdd'); ?>
		<?php echo $form->textField($model,'pdd'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'incomplete_calls'); ?>
		<?php echo $form->textField($model,'incomplete_calls'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'complete_calls_ner'); ?>
		<?php echo $form->textField($model,'complete_calls_ner'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'complete_calls'); ?>
		<?php echo $form->textField($model,'complete_calls'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'calls_attempts'); ?>
		<?php echo $form->textField($model,'calls_attempts'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'duration_real'); ?>
		<?php echo $form->textField($model,'duration_real'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'duration_cost'); ?>
		<?php echo $form->textField($model,'duration_cost'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ner02_efficient'); ?>
		<?php echo $form->textField($model,'ner02_efficient'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ner02_seizure'); ?>
		<?php echo $form->textField($model,'ner02_seizure'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pdd_calls'); ?>
		<?php echo $form->textField($model,'pdd_calls'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'revenue'); ?>
		<?php echo $form->textField($model,'revenue'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cost'); ?>
		<?php echo $form->textField($model,'cost'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'margin'); ?>
		<?php echo $form->textField($model,'margin'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'date_change'); ?>
		<?php echo $form->textField($model,'date_change'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_carrier'); ?>
		<?php echo $form->textField($model,'id_carrier'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_destination'); ?>
		<?php echo $form->textField($model,'id_destination'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
<?php
/* @var $this BalanceController */
/* @var $model Balance */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'balance-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
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
		<?php echo $form->labelEx($model,'minutes'); ?>
		<?php echo $form->textField($model,'minutes'); ?>
		<?php echo $form->error($model,'minutes'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'acd'); ?>
		<?php echo $form->textField($model,'acd'); ?>
		<?php echo $form->error($model,'acd'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'asr'); ?>
		<?php echo $form->textField($model,'asr'); ?>
		<?php echo $form->error($model,'asr'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'margin_percentage'); ?>
		<?php echo $form->textField($model,'margin_percentage'); ?>
		<?php echo $form->error($model,'margin_percentage'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'margin_per_minute'); ?>
		<?php echo $form->textField($model,'margin_per_minute'); ?>
		<?php echo $form->error($model,'margin_per_minute'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cost_per_minute'); ?>
		<?php echo $form->textField($model,'cost_per_minute'); ?>
		<?php echo $form->error($model,'cost_per_minute'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'revenue_per_min'); ?>
		<?php echo $form->textField($model,'revenue_per_min'); ?>
		<?php echo $form->error($model,'revenue_per_min'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pdd'); ?>
		<?php echo $form->textField($model,'pdd'); ?>
		<?php echo $form->error($model,'pdd'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'incomplete_calls'); ?>
		<?php echo $form->textField($model,'incomplete_calls'); ?>
		<?php echo $form->error($model,'incomplete_calls'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'complete_calls_ner'); ?>
		<?php echo $form->textField($model,'complete_calls_ner'); ?>
		<?php echo $form->error($model,'complete_calls_ner'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'complete_calls'); ?>
		<?php echo $form->textField($model,'complete_calls'); ?>
		<?php echo $form->error($model,'complete_calls'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'calls_attempts'); ?>
		<?php echo $form->textField($model,'calls_attempts'); ?>
		<?php echo $form->error($model,'calls_attempts'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'duration_real'); ?>
		<?php echo $form->textField($model,'duration_real'); ?>
		<?php echo $form->error($model,'duration_real'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'duration_cost'); ?>
		<?php echo $form->textField($model,'duration_cost'); ?>
		<?php echo $form->error($model,'duration_cost'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ner02_efficient'); ?>
		<?php echo $form->textField($model,'ner02_efficient'); ?>
		<?php echo $form->error($model,'ner02_efficient'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ner02_seizure'); ?>
		<?php echo $form->textField($model,'ner02_seizure'); ?>
		<?php echo $form->error($model,'ner02_seizure'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pdd_calls'); ?>
		<?php echo $form->textField($model,'pdd_calls'); ?>
		<?php echo $form->error($model,'pdd_calls'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'revenue'); ?>
		<?php echo $form->textField($model,'revenue'); ?>
		<?php echo $form->error($model,'revenue'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cost'); ?>
		<?php echo $form->textField($model,'cost'); ?>
		<?php echo $form->error($model,'cost'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'margin'); ?>
		<?php echo $form->textField($model,'margin'); ?>
		<?php echo $form->error($model,'margin'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'date_change'); ?>
		<?php echo $form->textField($model,'date_change'); ?>
		<?php echo $form->error($model,'date_change'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_carrier'); ?>
		<?php echo $form->textField($model,'id_carrier'); ?>
		<?php echo $form->error($model,'id_carrier'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_destination'); ?>
		<?php echo $form->textField($model,'id_destination'); ?>
		<?php echo $form->error($model,'id_destination'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
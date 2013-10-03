<?php
/* @var $this AccountingDocumentTempController */
/* @var $model AccountingDocumentTemp */
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
		<?php echo $form->label($model,'issue_date'); ?>
		<?php echo $form->textField($model,'issue_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'from_date'); ?>
		<?php echo $form->textField($model,'from_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'to_date'); ?>
		<?php echo $form->textField($model,'to_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'received_date'); ?>
		<?php echo $form->textField($model,'received_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sent_date'); ?>
		<?php echo $form->textField($model,'sent_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'doc_number'); ?>
		<?php echo $form->textField($model,'doc_number',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'minutes'); ?>
		<?php echo $form->textField($model,'minutes'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'amount'); ?>
		<?php echo $form->textField($model,'amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'note'); ?>
		<?php echo $form->textField($model,'note',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_type_accounting_document'); ?>
		<?php echo $form->textField($model,'id_type_accounting_document'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
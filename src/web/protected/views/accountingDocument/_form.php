<?php
/* @var $this AccountingDocumentController */
/* @var $model AccountingDocument */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'accounting-document-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'issue_date'); ?>
		<?php echo $form->textField($model,'issue_date'); ?>
		<?php echo $form->error($model,'issue_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'from_date'); ?>
		<?php echo $form->textField($model,'from_date'); ?>
		<?php echo $form->error($model,'from_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'to_date'); ?>
		<?php echo $form->textField($model,'to_date'); ?>
		<?php echo $form->error($model,'to_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'received_date'); ?>
		<?php echo $form->textField($model,'received_date'); ?>
		<?php echo $form->error($model,'received_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sent_date'); ?>
		<?php echo $form->textField($model,'sent_date'); ?>
		<?php echo $form->error($model,'sent_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'doc_number'); ?>
		<?php echo $form->textField($model,'doc_number',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'doc_number'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'minutes'); ?>
		<?php echo $form->textField($model,'minutes'); ?>
		<?php echo $form->error($model,'minutes'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'amount'); ?>
		<?php echo $form->textField($model,'amount'); ?>
		<?php echo $form->error($model,'amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'note'); ?>
		<?php echo $form->textField($model,'note',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'note'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_type_accounting_document'); ?>
		<?php echo $form->textField($model,'id_type_accounting_document'); ?>
		<?php echo $form->error($model,'id_type_accounting_document'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'id_carrier'); ?>
		<?php echo $form->textField($model,'id_carrier'); ?>
		<?php echo $form->error($model,'id_carrier'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
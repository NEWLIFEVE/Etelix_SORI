<?php
/* @var $this ContratoController */
/* @var $model Contrato */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contrato-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'sign_date'); ?>
		<?php echo $form->textField($model,'sign_date'); ?>
		<?php echo $form->error($model,'sign_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'production_date'); ?>
		<?php echo $form->textField($model,'production_date'); ?>
		<?php echo $form->error($model,'production_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'end_date'); ?>
		<?php echo $form->textField($model,'end_date'); ?>
		<?php echo $form->error($model,'end_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_carrier'); ?>
		<?php echo $form->textField($model,'id_carrier'); ?>
		<?php echo $form->error($model,'id_carrier'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_compania'); ?>
		<?php echo $form->textField($model,'id_compania'); ?>
		<?php echo $form->error($model,'id_compania'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
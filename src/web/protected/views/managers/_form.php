<?php
/* @var $this ManagersController */
/* @var $model Managers */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'managers-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row contratoForm">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
        
	<div class="row contratoForm">
		<?php echo $form->labelEx($model,'lastname'); ?>
		<?php echo $form->textField($model,'lastname',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'lastname'); ?>
	</div>

	<div class="row contratoForm">
		<?php echo $form->labelEx($model,'record_date'); ?>
		<?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $model,
                        'attribute' => 'record_date',
                        'options' => array('dateFormat' => 'yy-mm-dd'),
                        'htmlOptions' => array(
                            'size' => '10', // textField size
                            'maxlength' => '10', // textField maxlength
                            )));
                    ?>
		<?php echo $form->error($model,'record_date'); ?>
	</div>

	<div class="row contratoForm">
		<?php echo $form->labelEx($model,'position'); ?>
		<?php echo $form->textField($model,'position',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'position'); ?>
	</div>

        <div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textArea($model,'address',array('rows'=>2, 'cols'=>20)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row buttons G_ManagerNuevo">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
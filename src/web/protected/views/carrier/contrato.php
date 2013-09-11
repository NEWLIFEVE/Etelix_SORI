<?php
/* @var $this CarrierController */
/* @var $model Carrier */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'carrier-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>


    
    
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

<div class='divCarrierContrato'>
	<?php echo $form->labelEx($model,'Carrier'); ?>
	<?php echo $form->dropDownList($model,'name',
               CHtml::listData(Carrier::model()->findAll(),'id','name')
//                ,
//                array(
//                    'ajax'=>array(
//                        'type'=>'POST',
//                        'url'=>CController::createUrl('DynamicAsignados'),
//                        'update'=>'#select_left',
//                    ),'prompt'=>'Seleccione'
//                     )
                ); ?>
	<?php echo $form->error($model,'id_Carrier'); ?>
</div>
    

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textArea($model,'address',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
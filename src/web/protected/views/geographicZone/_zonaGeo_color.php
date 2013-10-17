
<?php
/* @var $this GeographicZoneController */
/* @var $model GeographicZone */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'geographic-zone-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name_zona'); ?>
		<?php echo $form->textField($model,'name_zona',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'name_zona'); ?>
	</div>

	<div class="row seleColor">
		<?php echo $form->labelEx($model,'color_zona'); ?>
		<?php echo $form->textField($model,'color_zona',array('size'=>50,'maxlength'=>50)); ?>
                <?php echo $form->error($model,'color_zona'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div> 

    
        <div class='paletaColores'>
          <button class='color1' id='red'>Rojo</button>
          <button class='color2' id='red'>azul</button>
          <button class='color3' id='red'>gris</button>
          <button class='color4' id='red'>verde</button>
          <button class='color5' id='red'>morado</button>
          <button class='color6' id='red'>amarillo</button>
          <button class='color7' id='red'>red</button>
          <button class='color8' id='red'>azul</button>
          <button class='color9' id='red'>gris</button>
          <button class='color10' id='red'>verde</button>
          <button class='color11' id='red'>morado</button>
          <button class='color12' id='red'>amarillo</button>
          <button class='color13' id='red'>gris</button>
          <button class='color14' id='red'>verde</button>
          <button class='color15' id='red'>morado</button>
          <button class='color16' id='red'>amarillo</button>
         
        </div>

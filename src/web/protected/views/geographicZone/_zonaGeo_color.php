
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

	<div class="row colorpicker">
		<?php echo $form->labelEx($model,'color_zona'); ?>
		<?php echo $form->textField($model,'color_zona',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'color_zona'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
var colorpickerOptions = {
    parts: ['map', 'bar', 'hex', 'hsv', 'rgb', 'alpha', 'preview', 'swatches', 'footer'],
    altProperties: 'background-color',
    altField: '.colorpicker',
    color: 'fe9810',
    select: function (event, color) {
        var color_in_hex_format = color.formatted;
        console.log(color_in_hex_format);
    }
    
    ,inline: false
};

$('.colorpicker').colorpicker(colorpickerOptions);
</script>
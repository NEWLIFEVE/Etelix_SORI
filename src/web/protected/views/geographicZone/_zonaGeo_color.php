
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
    <h1>ZONA GEOGRÁFICA</h1>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

        <div class='row'>
            <label class='acciones'>Elija una Acción para comenzar *</label>
                <?php echo $form->dropDownList($model,'acciones', 
                array(
                    ' '=>'Seleccione',
                    '1'=>'Nueva Zona Geográfica',
                    '2'=>'Editar Zona Geográfica',
                     )
                ); ?>
        </div>
   <div class="formularioDocumento">
        <div class="valoresDocumento">
	<div class="row">
		<?php echo $form->labelEx($model,'name_zona'); ?>
		<?php echo $form->textField($model,'name_zona',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->dropDownList($model,'name_zona', GeographicZone::getListGeo(),array('prompt'=>'Seleccione')); ?>
		<?php echo $form->error($model,'name_zona'); ?>
	</div>

	<div class="row seleColor">
		<?php echo $form->labelEx($model,'color_zona'); ?>
		<?php // echo $form->textField($model,'color_zona',array('size'=>50,'maxlength'=>50)); ?>
                <?php // echo $form->error($model,'color_zona'); ?>
            <div class="pruebacolor">
                <input type="hidden"name="color_zona_hidden" id="color_zona_hidden" value=""/>
                <input type="color"name="color_zona" id="color_zona" value=""/>
            </div>   
	</div>

	<div class="row buttons botGuardarZonaColor">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar Zona Geografica' : 'Save'); ?>
	</div>
<?php $this->endWidget(); ?>
        </div> 
   </div> 
</div> 

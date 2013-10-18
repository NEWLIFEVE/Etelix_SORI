
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
    <h1>NUEVA ZONA GEOGRÁFICA</h1>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

        <div class='row'>
            <label class='acciones'>Elija una Accion para comenzar *</label>
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
		<?php echo $form->textField($model,'color_zona',array('size'=>50,'maxlength'=>50)); ?>
                <?php echo $form->error($model,'color_zona'); ?>
	</div>

	<div class="row buttons botGuardarZonaColor">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar Zona Geografica' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

        </div> 
   </div> 
</div> 

    
        <div class='paletaColores'>
          <button class='color1' id='2ECCFA'>Rojo</button>
          <button class='color2' id='00FF80'>azul</button>
          <button class='color3' id='F4FA58'>gris</button>
          <button class='color4' id='FE9A2E'>verde</button>
          <button class='color5' id='FF0000'>morado</button>
          <button class='color6' id='D7DF01'>amarillo</button>
          <button class='color7' id='BDBDBD'>red</button>
          <button class='color8' id='2E2E2E'>Ivory</button>
          <button class='color9' id='F5BCA9'>Melon</button>
          <button class='color10' id='F6D8CE'>Verde Pantano</button>
          <button class='color11' id='4B610B'>Verde Turquesa</button>
          <button class='color12' id='0B3B2E'>Verde Oscuro</button>
          <button class='color13' id='0A2A1B'>Verde Grama</button>
          <button class='color14' id='0B610B'>Verde Claro</button>
          <button class='color15' id='2EFE9A'>Azul claro</button>
          <button class='color16' id='81BEF7'>Azul Rey</button>
         
        </div>

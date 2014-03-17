<?php
/* @var $this CarrierController */
/* @var $model Carrier */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'carrier-form',
        'htmlOptions'=>array('name'=>'carrier-form'),
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
<h1 class="group_title">ADMIN GRUPO</h1>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row SelectGrupo">
            <label class="labelGrupos">Grupos</label>
                <?php echo $form->dropDownList($model,'id',CarrierGroups::getListGroups(),
                    array( 'prompt'=>'Seleccione' )
                ); ?>
            <?php echo $form->textField($model,'new_groups'); ?>
            <?php echo $form->error($model,'id'); ?>
            <label class="NoteGrupos">Ingrese el nombre del nuevo grupo, asigne los carriers correspondientes y haga click en "Guardar Grupo".</label>
            <div class="newGroup">
                    <label>+</label>
            </div>
            <div class="cancelarnewGroup">
                    <label><</label>
            </div>
	</div>

        <div class="row divCarrier" id="carriers">
            <?php
            $this->widget('ext.widgets.multiselects.XMultiSelects', array(
                'leftTitle' => 'Carriers Asignados',
                'leftName' => 'Asignados[]',
                'leftList' => array(),
                'rightTitle' => 'Carriers No Asignados',
                'rightName' => 'No_Asignados[]',
                'rightList' =>Carrier::model()->getListCarriersSinGrupo(),
                'size' => 15,  
        //        'width' => '400px',
            ));
            ?>
                        <?php echo $form->error($model,'lastname'); ?>  
        </div>

	<div class="row buttons AsignarCarrierGroup">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar Grupo' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
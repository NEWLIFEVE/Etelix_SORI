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
        <div class="valoresDestination">
        <div class='destinationform'>
                <?php echo $form->labelEx($model,'id_destination'); ?>
                <?php echo $form->dropDownList($model,'id_destination', 
                array(
                    ''=>'Seleccione',
                    'id_destination'=>'Destinos Externos',
                    'id_destination_int'=>'Destinos Internos',
                     ),
                   array(
//                    'ajax'=>array(
//                        'type'=>'POST',
//                        'url'=>CController::createUrl('DynamicAsignados'),
//                        'update'=>'#select_right',
//                   )
                        )
                ); ?>
                <?php echo $form->error($model,'id_destination'); ?>
        </div>
        <div class='destinationform'>
                <?php echo $form->labelEx($model,'id'); ?>
                <?php echo $form->dropDownList($model,'id',
                        CHtml::listData(GeographicZone::model()->findAll(array('order'=>'name_zona')),'id','name_zona'),
                 array(
                    'ajax'=>array(
                        'type'=>'POST',
                        'url'=>CController::createUrl('DynamicAsignados'),
                        'update'=>'#select_right',
                    ),'prompt'=>'Seleccione'
                     )
                ); ?>
                <?php echo $form->error($model,'id'); ?>
        </div>
        </div>
        <div class="row divCarrier" id="carriers">
                <?php
                $this->widget('ext.widgets.multiselects.XMultiSelects', array(
                    'leftTitle' => 'Destinos No Asignados',
                    'leftName' => 'No_Asignados[]',
                    'leftList' =>  Destination::model()->getListDestinationNOAsignados(),
                    'rightTitle' => 'Destinos  Asignados',
                    'rightName' => 'Asignados[]',
                    'rightList' =>array(),
                    'size' => 15,  
                    'width' => '400px',
                ));
                ?>
                <?php echo $form->error($model,'lastname'); ?>  
        </div>

	<div class="row buttons botAsignarDestination">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>
<?php
/* @var $this GeographicZoneController */
/* @var $controllerDestination DestinationController */
/* @var $controllerDestinationInt DestinationIntController */
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
                <?php echo $form->labelEx($model,'Destinos'); ?>
                <?php echo $form->dropDownList($model,'id_destination', 
                array(
                    ' '=>'Seleccione',
                    '1'=>'Destinos Externos',
                    '2'=>'Destinos Internos',
                     ),
                   array(
                    'ajax'=>array(
                        'type'=>'POST',
                        'url'=>CController::createUrl('ElijeTipoDestination'),
                        'update'=>'#select_left',
                   )
                        )
                ); ?>
                <?php echo $form->error($model,'id_destination'); ?>
        </div>
        <div class='destinationform'>
                <?php echo $form->labelEx($model,'id'); ?>
                <?php echo $form->dropDownList($model,'id',GeographicZone::getListGeo(),
                 array(
//                    'ajax'=>array(
//                        'type'=>'POST',
//                        'url'=>CController::createUrl('DynamicAsignados'),
//                        'update'=>'#select_right',
//                    ),
                    'prompt'=>'Seleccione'
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
//                    'leftList' =>  Destination::model()->getListDestinationNOAsignados(),
                    'leftList' => array(),
                    'rightTitle' => 'Destinos  Asignados',
                    'rightName' => 'Asignados[]',
                    'rightList' =>array(),
                    'size' => 15,  
//                    'width' => '400px',
                ));
                ?>
                <?php echo $form->error($model,'lastname'); ?>  
        </div>
              <?php $this->endWidget(); ?>
	<div class="row buttons botAsignarDestination">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Save'); ?>
	</div>



</div><!-- form -->

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>

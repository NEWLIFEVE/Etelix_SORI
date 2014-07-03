<?php
/* @var $this AccountingDocumentController */
/* @var $model AccountingDocument */
/* @var $form CActiveForm */
?>

<h1>Admin. Disputas</h1>

<h3>Seleccione un Carrier para comenzar</h3>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'accounting-document-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_carrier'); ?>
		<?php echo $form->dropDownList($model,'id_carrier',Carrier::getListCarrierNoUNKNOWN(),array('prompt'=>'Seleccione')); ?>
		<?php echo $form->error($model,'id_carrier'); ?>
                <img id="filterForPeriod" src='/images/filterPeriod.png' title="Filtrar por periodo">
	</div>
        <div class="filterForPeriod">
            <div class="contratoForm fechaIniFact">
                <?php echo $form->labelEx($model,'from_date'); ?>
                <?php 
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model'=>$model,
                        'attribute'=>'from_date',
                        'options'=>array(
                            'dateFormat'=>'yy-mm-dd',
                            'maxDate'=> "-0D", //fecha maxima
                            ),
                        'htmlOptions'=>array(
                            'size'=>'10', // textField size
                            'maxlength'=>'10', // textField maxlength
                            'readonly'=>'readonly'
                            )
                        )
                    ); 
                ?>
                <?php echo $form->error($model,'from_date'); ?>
            </div>
            <div class="contratoForm fechaFinFact">
                <?php echo $form->labelEx($model,'to_date'); ?>
                <?php 
                    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                        'model'=>$model,
                        'attribute'=>'to_date',
                        'options'=>array(
                            'dateFormat'=>'yy-mm-dd',
                            'maxDate'=> "-0D", //fecha maxima
                            ),
                        'htmlOptions'=>array(
                            'size'=>'10', // textField size
                            'maxlength'=>'10', // textField maxlength
                            'readonly'=>'readonly'
                            )
                        )
                    ); 
                ?>
                <?php echo $form->error($model,'to_date'); ?>
            </div>
            <img id="updateGetDispute" src='/images/update.png' title="Buscar por periodo y actualizar">
        </div>
        <div id="adminDispute"> </div>

<?php $this->endWidget(); ?>

</div><!-- form -->
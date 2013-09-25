<?php
/* @var $this AccountingDocumentTempController */
/* @var $model AccountingDocumentTemp */
/* @var $form CActiveForm */
?>

<div class="form">
        <div class="instruccion">
            <h3>Para comenzar, Seleccione un tipo de documento</h3>
        </div>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'accounting-document-temp-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
));?>

         <?php echo $form->errorSummary($model); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>
        
        <div class="AccountingDocumentTemp_id_type_accounting_document">
            <?php echo $form->labelEx($model,'id_type_accounting_document'); ?>
                    <?php echo $form->dropDownList($model,'id_type_accounting_document', 
                            TypeAccountingDocument::getNameList(),array('empty'=>'Seleccionar..'));?>
		<?php echo $form->error($model,'id_type_accounting_document'); ?>
        </div>

   <div class="formularioDocumento">
     <div class="valoresDocumento">

        <div class="contratoForm">
		<?php echo $form->labelEx($model,'issue_date'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                   'model' => $model,
                                   'attribute' => 'issue_date',
                           'options'=>array('dateFormat'=>'yy-mm-dd'),
                                   'htmlOptions' => array(
                                       'size' => '10', // textField size
                                       'maxlength' => '10', // textField maxlength
                    ),)); ?>
		<?php echo $form->error($model,'issue_date'); ?>
	</div>

	<div class="contratoForm">
		<?php echo $form->labelEx($model,'from_date'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                   'model' => $model,
                                   'attribute' => 'from_date',
                           'options'=>array('dateFormat'=>'yy-mm-dd'),
                                   'htmlOptions' => array(
                                       'size' => '10', // textField size
                                       'maxlength' => '10', // textField maxlength
                ))); ?>
		<?php echo $form->error($model,'from_date'); ?>
	</div>

	<div class="contratoForm">
		<?php echo $form->labelEx($model,'to_date'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                   'model' => $model,
                                   'attribute' => 'to_date',
                          'options'=>array('dateFormat'=>'yy-mm-dd'),
                                   'htmlOptions' => array(
                                       'size' => '10', // textField size
                                       'maxlength' => '10', // textField maxlength
                ))); ?>
		<?php echo $form->error($model,'to_date'); ?>
	</div>

	<div class="contratoForm">
		<?php echo $form->labelEx($model,'received_date'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                   'model' => $model,
                                   'attribute' => 'received_date',
                           'options'=>array('dateFormat'=>'yy-mm-dd'),
                                   'htmlOptions' => array(
                                       'size' => '10', // textField size
                                       'maxlength' => '10', // textField maxlength
                ))); ?>
		<?php echo $form->error($model,'received_date'); ?>
	</div>

	<div class="contratoForm">
		<?php echo $form->labelEx($model,'sent_date'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                   'model' => $model,
                                   'attribute' => 'sent_date',
                         'options'=>array('dateFormat'=>'yy-mm-dd'),
                                   'htmlOptions' => array(
                                       'size' => '10', // textField size
                                       'maxlength' => '10', // textField maxlength
                ))); ?>
		<?php echo $form->error($model,'sent_date'); ?>
	</div>

	<div class="contratoForm">
		<?php echo $form->labelEx($model,'doc_number'); ?>
		<?php echo $form->textField($model,'doc_number',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'doc_number'); ?>
	</div>

	<div class="contratoForm">
		<?php echo $form->labelEx($model,'minutes'); ?>
		<?php echo $form->textField($model,'minutes'); ?>
		<?php echo $form->error($model,'minutes'); ?>
	</div>

	<div class="contratoForm">
		<?php echo $form->labelEx($model,'amount'); ?>
		<?php echo $form->textField($model,'amount'); ?>
		<?php echo $form->error($model,'amount'); ?>
	</div>
                            <div class="hacerUnaNota">
                                <br>
                                <label>Nota (+)</label> 
                            </div>           
	<div class="contratoFormTextArea">
                <br>     
		 <label class="quitaNota"><b>Nota (-)</b></label> 
		<?php echo $form->textArea($model,'note',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'note'); ?>
	</div>
              <br>
      
	<div id="botAgregarDatosContable" class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Agregar' : 'Save'); ?>
	</div>
      </div>
    </div>
  <?php $this->endWidget(); ?>
</div><!-- form -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>
<script>
  
//  $('#botAgregarDatosContable').click('on',function(e)
//    { 
//        var exito = $("<div class='cargando'></div><div class='mensaje'><h3>El documento fue guardado con exito</h3><p><p><p><p><p><p><p><p><img src='/images/si.png'width='95px' height='95px'/></div>").hide();
//                                $("body").append(exito)
//                                exito.fadeIn('fast');
//                                setTimeout(function()
//                             {
//                                 exito.fadeOut('fast');
//                             }, 30000);
       
//       var selecTipoDoc=$('#AccountingDocumentTemp_id_type_accounting_document');
//       var fechaEmision=$('#AccountingDocumentTemp_issue_date');
//       var desdeFecha=$('#AccountingDocumentTemp_from_date');
//       var hastaFecha=$('#AccountingDocumentTemp_to_date');
//       var fechaRecepcion=$('#AccountingDocumentTemp_received_date');
//       var fechaEnvio=$('#AccountingDocumentTemp_sent_date');
//       var numDocumento=$('#AccountingDocumentTemp_doc_number');
//       var minutos=$('#AccountingDocumentTemp_minutes');
//       var cantidad=$('#AccountingDocumentTemp_amount');
//       var nota=$('#AccountingDocumentTemp_note');
//      
//        $.ajax({ 
//              type: "GET",
//              url: "Create",
//              data: "fechaEmision="+fechaEmision+"&desdeFecha="+desdeFecha+"&hastaFecha="+hastaFecha+"&fechaRecepcion="+fechaRecepcion+"\
//                    &fechaEnvio="+fechaEnvio+"&numDocumento="+numDocumento+"&minutos="+minutos+"&cantidad="+cantidad+"&nota="+nota+"&selecTipoDoc="+selecTipoDoc,
//
//              success: function(data) 
//                      {
//
//                      }          
//        }); 
//    }); 
</script>
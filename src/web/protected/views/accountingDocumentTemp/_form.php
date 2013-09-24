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

	<p class="note">Fields with <span class="required">*</span> are required.</p>
        
        <div class="selectTypeDocument">
            <select id="selectTypeDocument">
                <option> Seleccione</option>
                <option value="1"> Facturas</option>
                <option value="2"> Depositos</option>
                <option value="3"> otros</option>
                <option value="4"> ootros</option>
            </select>
        </div>

   <div class="formularioDocumento">
     <div class="valoresDocumento">
         <?php echo $form->errorSummary($model); ?>
        <div class="contratoForm">
		<?php echo $form->labelEx($model,'issue_date'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                   'model' => $model,
                                   'attribute' => 'issue_date',
                                   'htmlOptions' => array(
                                       'size' => '10', // textField size
                                       'maxlength' => '10', // textField maxlength
                ))); ?>
		<?php echo $form->error($model,'issue_date'); ?>
	</div>

	<div class="contratoForm">
		<?php echo $form->labelEx($model,'from_date'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                                   'model' => $model,
                                   'attribute' => 'from_date',
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

	<div class="contratoForm">
		<?php echo $form->labelEx($model,'note'); ?>
		<?php echo $form->textField($model,'note',array('size'=>60,'maxlength'=>250)); ?>
		<?php echo $form->error($model,'note'); ?>
	</div>

	<div class="contratoForm">
		<?php echo $form->labelEx($model,'id_type_accounting_document'); ?>
		<?php echo $form->textField($model,'id_type_accounting_document'); ?>
		<?php echo $form->error($model,'id_type_accounting_document'); ?>
	</div>
              <br>
              <br>
        <?php $this->endWidget(); ?>
	<div id="botAgregarDatosContable" class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Agregar' : 'Save'); ?>
	</div>
      </div>
    </div>

</div><!-- form -->

<script>
  $('#selectTypeDocument').change(function()
    {
        $('.instruccion').slideUp('slow');
        $('.valoresDocumento').slideDown('slow');
    });
    
  $('#botAgregarDatosContable').click('on',function(e)
    { 
       var selecTipoDoc=$('#selectTypeDocument');
       var fechaEmision=$('#AccountingDocumentTemp_issue_date');
       var desdeFecha=$('#AccountingDocumentTemp_from_date');
       var hastaFecha=$('#AccountingDocumentTemp_to_date');
       var fechaRecepcion=$('#AccountingDocumentTemp_received_date');
       var fechaEnvio=$('#AccountingDocumentTemp_sent_date');
       var numDocumento=$('#AccountingDocumentTemp_doc_number');
       var minutos=$('#AccountingDocumentTemp_minutes');
       var cantidad=$('#AccountingDocumentTemp_amount');
       var nota=$('#AccountingDocumentTemp_note');
       var tipoDoc=$('#AccountingDocumentTemp_id_type_accounting_document');
       if()
           {
        $.ajax({ 
              type: "GET",
              url: "agregarDocumentoTemporal",
              data: "fechaEmision="+fechaEmision+"&desdeFecha="+desdeFecha+"&hastaFecha="+hastaFecha+"&fechaRecepcion="+fechaRecepcion+"\
                    &fechaEnvio="+fechaEnvio+"&numDocumento="+numDocumento+"&minutos="+minutos+"&cantidad="+cantidad+"&nota="+nota+"&tipoDoc="+tipoDoc,

              success: function(data) 
                      {

                      }          
        }); 
    }); 
</script>
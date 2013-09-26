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
            <?php echo $form->dropDownList($model,'id_type_accounting_document',TypeAccountingDocument::getListTypeAccountingDocument(),array('prompt'=>'Seleccione')); ?>
            <?php echo $form->error($model,'id_type_accounting_document'); ?>
        </div>

   <div class="formularioDocumento">
     <div class="valoresDocumento">
         
        <div class="AccountingDocumentTemp_id_type_accounting_document  contratoForm">
            <?php echo $form->labelEx($model,'id_carrier'); ?>
            <?php echo $form->dropDownList($model,'id_carrier',Carrier::getListCarrierNoUNKNOWN(),array('prompt'=>'Seleccione')); ?>
            <?php echo $form->error($model,'id_carrier'); ?>
        </div>
         
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
        <div class="VistDocTemporales">
            <table border="1" class="tablaVistDocTemporales">
                <tr> <td> Tipo de Doc </td> <td> Carrier </td> <td> Fecha de Emisión </td> <td> Fecha de Inicio </td>
                    <td> Fecha de Culminación </td> <td> Fecha Recepción </td> <td> Fecha Envio </td> <td> N°Documento </td> <td> Minutos </td> <td> Cantidad </td>
            </table>
        </div>
              <br> 
        <div id="botAgregarDatosContableFinal" class="row buttons">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Save'); ?>
        </div>  
              
      </div>
    </div> 
  <?php $this->endWidget(); ?>
</div><!-- form -->


<!--<script src="<?php // echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>-->
<script>
  $('#AccountingDocumentTemp_id_type_accounting_document').change(function()
    {
        $('div.instruccion').slideUp('slow');
        $('div.valoresDocumento').slideDown('slow');
    });
    
    $('div.hacerUnaNota').click('on',function()
      {
          $('div.hacerUnaNota').hide('slow');
          $('div.contratoFormTextArea').fadeIn('slow');
          $('textarea#AccountingDocumentTemp_note').fadeIn('slow');
      });

    $('.quitaNota').click('on',function()
      {
          $('div.hacerUnaNota').slideDown('slow');
          $('div.contratoFormTextArea').hide('slow');
          $('textarea#AccountingDocumentTemp_note').hide('slow');
      });
      
      
  $('#botAgregarDatosContable').click('on',function(e)
    { 
        e.preventDefault();


       var selecTipoDoc=$('#AccountingDocumentTemp_id_type_accounting_document').val();
       var idCarrier=$('#AccountingDocumentTemp_id_carrier').val();
       var fechaEmision=$('#AccountingDocumentTemp_issue_date').val();
       var desdeFecha=$('#AccountingDocumentTemp_from_date').val();
       var hastaFecha=$('#AccountingDocumentTemp_to_date').val();
       var fechaRecepcion=$('#AccountingDocumentTemp_received_date').val();
       var fechaEnvio=$('#AccountingDocumentTemp_sent_date').val();
       var numDocumento=$('#AccountingDocumentTemp_doc_number').val();
       var minutos=$('#AccountingDocumentTemp_minutes').val();
       var cantidad=$('#AccountingDocumentTemp_amount').val();
       var nota=$('#AccountingDocumentTemp_note').val();
                    
       if (idCarrier==''||fechaEmision==''||desdeFecha==''||hastaFecha==''||fechaRecepcion==''||fechaEnvio==''||numDocumento==''||minutos==''||cantidad==''){
           var msjIndicador = $("<div class='cargando'></div><div class='mensaje'><h3>Faltan datos por seleccionar</h3><p><p><p><p><p><p><p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
                             $("body").append(msjIndicador)
                                msjIndicador.fadeIn('fast');
                                setTimeout(function(){
                                    msjIndicador.fadeOut('fast');
                                }, 3000); 
         }else{             
//      -------------------------------
        $.ajax({ 
              type: "GET",
              url: "guardarListaTemp",
              data: "&fechaEmision="+fechaEmision+"&idCarrier="+idCarrier+"&desdeFecha="+desdeFecha+"&hastaFecha="+hastaFecha+"&fechaRecepcion="+fechaRecepcion+"\
                    &fechaEnvio="+fechaEnvio+"&numDocumento="+numDocumento+"&minutos="+minutos+"&cantidad="+cantidad+"&nota="+nota+"&selecTipoDoc="+selecTipoDoc,

              success: function(data) 
                      { 
                       msjIndicador = $("<div class='cargando'></div><div class='mensaje'><h3>El documento contable fue guardado con exito</h3><p><p><p><p><p><p><p><p><img src='/images/si.png'width='95px' height='95px'/></div>").hide();
                             $("body").append(msjIndicador)
                                msjIndicador.fadeIn('fast');
                                setTimeout(function()
                             {
                                 msjIndicador.fadeOut('fast');
                             }, 3000); 
                      
                            obj = JSON.parse(data);
                            var idCarrierNameTemp=obj.idCarrierNameTemp;
                            var selecTipoDocNameTemp=obj.selecTipoDocNameTemp;
                            var fechaEmisionTemp=obj.fechaEmisionTemp;
                            var desdeFechaTemp=obj.desdeFechaTemp;
                            var hastaFechaTemp=obj.hastaFechaTemp;
                            var fechaRecepcionTemp=obj.fechaRecepcionTemp;
                            var fechaEnvioTemp=obj.fechaEnvioTemp;
                            var numDocumentoTemp=obj.numDocumentoTemp;
                            var minutosTemp=obj.minutosTemp;
                            var cantidadTemp=obj.cantidadTemp;

//                            alert(idCarrierNameTemp);
//                            alert(selecTipoDocNameTemp);
//                            alert(fechaEmisionTemp);
//                            alert(desdeFechaTemp);
//                            alert(hastaFechaTemp);
//                            alert(fechaRecepcionTemp);
//                            alert(fechaEnvioTemp);
//                            alert(numDocumentoTemp);
//                            alert(minutosTemp);
//                            alert(cantidadTemp);

                                $(".tablaVistDocTemporales").append("<tr class='vistaTemp'><td> "+selecTipoDocNameTemp+" </td> <td> "+idCarrierNameTemp+" </td> <td> "+fechaEmisionTemp+" </td> \n\
                                                  <td> "+desdeFechaTemp+" </td><td> "+hastaFechaTemp+" </td> <td> "+fechaRecepcionTemp+" </td>\n\
                                                  <td> "+fechaEnvioTemp+" </td> <td> "+numDocumentoTemp+" </td> <td> "+minutosTemp+" </td> <td> "+cantidadTemp+" </td></tr>");

                                                        $('.tablaVistDocTemporales').fadeIn('slow');
                                                        $('#botAgregarDatosContableFinal').fadeIn('slow');
                                
                                $("#AccountingDocumentTemp_doc_number").val('');
                                $("#AccountingDocumentTemp_minutes").val('');
                                $("#AccountingDocumentTemp_amount").val('');
                                $("#AccountingDocumentTemp_note").val('');
                      }          
        }); 
//       ------------------------------
         }
    }); 
    
      $('#botAgregarDatosContableFinal').click('on',function(e)
    { 
        e.preventDefault();
         $.ajax({ 
              type: "GET",
              url: "guardarListaFinal",
//              data: "&selecTipoDocNameTemp="+selecTipoDocNameTemp+"&idCarrierNameTemp="+idCarrierNameTemp+"&fechaEmisionTemp="+fechaEmisionTemp+"&desdeFechaTemp="+desdeFechaTemp+"&hastaFechaTemp="+hastaFechaTemp+"\
//                    &fechaRecepcionTemp="+fechaRecepcionTemp+"&fechaEnvioTemp="+fechaEnvioTemp+"&numDocumentoTemp="+numDocumentoTemp+"&minutosTemp="+minutosTemp+"&cantidadTemp="+cantidadTemp,

              success: function(data) 
                      { 
                          alert(data);
                      }
                      
               });
    });
</script>
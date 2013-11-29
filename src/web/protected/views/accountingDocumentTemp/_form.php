<?php
/**
 * @var $this AccountingDocumentTempController
 * @var $model AccountingDocumentTemp
 * @var $form CActiveForm 
 */
?>

<div class="form">
    <div class="instruccion">
        <h3>Para comenzar, Seleccione un tipo de documento</h3>
    </div>
    <?php 
 echo CHtml::beginForm(Yii::app()->createUrl('AccountingDocumentTemp/enviarEmail/'),'post',array('name'=>'FormularioCorreo','id'=>'FormularioCorreo','style'=>'display:none'));
  echo CHtml::textField('html','Hay Efectivo',array('id'=>'html','style'=>'display:none'));
  echo CHtml::textField('vista','create',array('id'=>'vista','style'=>'display:none'));
  echo CHtml::textField('asunto','Documentos Contables Temporales',array('id'=>'asunto','style'=>'display:none'));
  echo CHtml::endForm(); 

        $form=$this->beginWidget('CActiveForm',array(
            'id'=>'accounting-document-temp-form',
            'enableAjaxValidation'=>false,
            )
        );
    ?>
    <?php echo $form->errorSummary($model); ?>
    <p class="note">Fields with <span class="required">*</span> are required.</p>
    <div class="input_largos">
        <?php echo $form->labelEx($model,'id_type_accounting_document'); ?>
        <?php echo $form->dropDownList($model,'id_type_accounting_document',TypeAccountingDocument::getListTypeAccountingDocument(),array('prompt'=>'Seleccione')); ?>
        <?php echo $form->error($model,'id_type_accounting_document'); ?>
    </div>
    
    <div class="CarrierDocument input_largos">
        <?php echo $form->labelEx($model,'id_carrier'); ?>
        <?php echo $form->dropDownList($model,'id_carrier',Carrier::getListCarrierNoUNKNOWN(),
               array(
                    'ajax'=>array(
                        'type'=>'POST',
                        'url'=>CController::createUrl('DestinosSuppAsignados'),
                        'update'=>'select#AccountingDocumentTemp_select_dest_supplier',
                    ),'prompt'=>'Seleccione'
                     )
                ); ?>
        <?php echo $form->error($model,'id_carrier'); ?>
    </div>
    <div class="GrupoDocument input_largos ">
        <?php  echo $form->labelEx($model,'carrier_groups'); ?>
        <?php echo $form->dropDownList($model,'carrier_groups',  CarrierGroups::getListGroups(),array('prompt'=>'Seleccione')); ?>
        <?php echo $form->error($model,'carrier_groups'); ?>
    </div>

    <div class="formularioDocumento">
        <div class="valoresDocumento">
            <div class="contratoForm fechaDeEmision">
                <?php echo $form->labelEx($model,'issue_date'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                    'model'=>$model,
                    'attribute'=>'issue_date',
                    'options'=>array(
                        'dateFormat'=>'yy-mm-dd',
                        'maxDate'=> "-0D", //fecha maxima
                        ),
                    'htmlOptions'=>array(
                        'size'=>'10', // textField size
                        'maxlength'=>'10', // textField maxlength
                        'readonly'=>'readonly'
                        ),
                    )
                    ); 
                ?>
                <?php echo $form->error($model,'issue_date'); ?>
            </div>
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

            <div class="contratoForm emailReceivedDate">
                <label class='emailRecDate'>Fecha de recepción de Email</label>
                <?php // echo $form->labelEx($model,'email_received_date'); ?>
                <?php 
                    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                        'model'=>$model,
                        'attribute'=>'email_received_date',
                        'options'=>array(
                            'dateFormat'=>'yy-mm-dd',
                            'maxDate'=> "-0D", //fecha maxima
                            ),
                        'htmlOptions'=>array(
                            'size'=>'10', // textField size
                            'maxlength'=>'10', // textField maxlength
                            'maxDate'=> "-1D", //fecha maxima
                            'readonly'=>'readonly'
                            ),
                        )
                    ); 
                ?>
                <?php echo $form->error($model,'email_received_date'); ?>
            </div>
            <div class="contratoForm validReceivedDate">
                
                <?php  echo $form->labelEx($model,'valid_received_date'); ?>
                <?php 
                    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                        'model'=>$model,
                        'attribute'=>'valid_received_date',
                        'options'=>array(
                            'dateFormat'=>'yy-mm-dd',
                            'maxDate'=> "-0D", //fecha maxima
                            ),
                        'htmlOptions'=>array(
                            'size'=>'10', // textField size
                            'maxlength'=>'10', // textField maxlength
                            )
                        )
                    ); 
                ?>
                <?php echo $form->error($model,'valid_received_date'); ?>
            </div>
            
            <div class="contratoForm emailReceivedTime">
                <?php echo $form->labelEx($model,'email_received_hour'); ?>
                <?php
                    $this->widget('webroot.protected.extensions.clockpick.EClockpick', array(
                        'model'=>$model,
                        'attribute'=>'email_received_hour',
                        'options'=>array(

                            'starthour'=>00,
                            'endhour'=>23,
                            'showminutes'=>TRUE,
                            'minutedivisions'=>12,
                            'military'=>TRUE,
                            'event'=>'focus',
                            'layout'=>'horizontal'
                            ),
                        'htmlOptions'=>array(
                            'size'=>20,
                            'maxlength'=>10,
                            'readonly'=>'readonly'
                            )
                        )
                    );
                ?>
                <?php echo $form->error($model,'email_received_hour'); ?>
            </div>

            <div class="contratoForm numFactura">
                <?php echo $form->labelEx($model,'id_accounting_document'); ?>
                <?php echo $form->dropDownList($model,'id_accounting_document',array('prompt'=>'')); ?>
                <?php echo $form->error($model,'id_accounting_document'); ?>
            </div>
            
              <div class="contratoForm DestinoEtx">
                <?php echo $form->labelEx($model,'id_destination'); ?>
                <?php echo $form->dropDownList($model,'id_destination',Destination::getDesList(),array('prompt'=>'Seleccione')); ?>
                <?php echo $form->error($model,'id_destination'); ?>
              </div>
            
            <div class="contratoForm DestinoProv">
                <?php echo $form->labelEx($model,'id_destination_supplier'); ?>
                <?php echo $form->textField($model,'input_dest_supplier',array('size'=>50,'maxlength'=>50)); ?>
                <?php echo $form->dropDownList($model,'select_dest_supplier',array('prompt'=>'Seleccione')); ?>
                <?php echo $form->error($model,'id_destination_supplier'); ?>
                <div class="nuevoDestProv">
                    <label>+</label>
                </div>
                <div class="cancelarDestProv">
                    <label><</label>
                </div>
            </div>
             
            <div class="contratoForm minutosDoc">
                <?php echo $form->labelEx($model,'minutes'); ?>
                <?php echo $form->textField($model,'minutes'); ?>
                <?php echo $form->error($model,'minutes'); ?>
            </div>
            <div class="contratoForm minutosEtx">
                <?php echo $form->labelEx($model,'min_etx'); ?>
                <?php echo $form->textField($model,'min_etx'); ?>
                <?php echo $form->error($model,'min_etx'); ?>
            </div>
            <div class="contratoForm minutosProveedor">
                <?php echo $form->labelEx($model,'min_carrier'); ?>
                <?php echo $form->textField($model,'min_carrier'); ?>
                <?php echo $form->error($model,'min_carrier'); ?>
            </div>
                     <div class="contratoForm numDocument">
                <?php echo $form->labelEx($model,'doc_number'); ?>
                <?php echo $form->textField($model,'doc_number',array('size'=>50,'maxlength'=>50)); ?>
                <?php echo $form->error($model,'doc_number'); ?>
            </div>
            <div class="contratoForm montoDoc">
                <?php echo $form->labelEx($model,'amount'); ?>
                <?php echo $form->textField($model,'amount'); ?>
                <?php echo $form->error($model,'amount'); ?>
            </div>

            <div class="contratoForm tabla_N_C">
            <label class="label_Disp_NotaCEnv">Disputas recibidas:</label>
            <table border="1" class="tablaVistDocTemporales lista_Disp_NotaCEnv">
                   <tr>
                      <td> Destino </td>
                      <td> Min Etx </td>
                      <td> Min Prov </td>
                      <td> Tarifa Etx </td>
                      <td> Tarifa Prov </td>
                      <td> Monto Etx</td>
                      <td> Monto Prov</td>
                      <td> Disputa</td>
                      <td> Monto de nota </td>
                   </tr>
            </table>
            </div>
            <div class="contratoForm rateEtx">
                <?php echo $form->labelEx($model,'rate_etx'); ?>
                <?php echo $form->textField($model,'rate_etx'); ?>
                <?php echo $form->error($model,'rate_etx'); ?>
            </div>
            <div class="contratoForm rateProveedor">
                <?php echo $form->labelEx($model,'rate_carrier'); ?>
                <?php echo $form->textField($model,'rate_carrier'); ?>
                <?php echo $form->error($model,'rate_carrier'); ?>
            </div>
                
            <div class="contratoForm Moneda">
                <?php echo $form->labelEx($model,'id_currency'); ?>
                <?php echo $form->dropDownList($model,'id_currency',  Currency::getListCurrency()); ?>
                <?php echo $form->error($model,'id_currency'); ?>
            </div>
            
            <div class="hacerUnaNota">
                <br>
                <label>Nota (+)</label>
            </div>
            <div class="quitaNota">
                <br>
                <label>Nota (-)</label>
            </div>
            <div class="contratoFormTextArea">
                <?php echo $form->textArea($model,'note',array('size'=>60,'maxlength'=>250)); ?>
                <?php echo $form->error($model,'note'); ?>
            </div>
            <br>
            <div id="botAgregarDatosContable" class="row buttons">
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Agregar a Temporales' : 'Save'); ?>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
    <div class="VistDocTemporales">
        <br>
        <div class="botonesParaExportar" <?php if($lista_FacEnv!=null||$lista_FacRec!=null||$lista_Pagos!=null||$lista_Cobros!=null||$lista_NotCredEnv!=null||$lista_NotCredRec!=null||$lista_DispRec!=null||$lista_DispEnv!=null){echo "style='display:block;'";}?>>
           <div class="botonImprimir contratoForm"><img src='/images/print-icon.png'/></div>
           <div class="botonCorreo contratoForm"><img src='/images/mail.png'/></div> 
        </div>
        
        <div id="botAgregarDatosContableFinal" class="row buttons" <?php if($lista_FacEnv!=null||$lista_FacRec!=null||$lista_Pagos!=null||$lista_Cobros!=null||$lista_NotCredEnv!=null||$lista_NotCredRec!=null||$lista_DispRec!=null||$lista_DispEnv!=null){echo "style='display:block;'";}?>>
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardado Definitivo' : 'Save'); ?>
        </div>
            
               <label class="Label_F_Env" <?php if($lista_FacEnv==null){echo "style='display:none;'";}?>>Facturas Enviadas:</label>
        <table border="1" class="tablaVistDocTemporales lista_FacEnv" <?php if($lista_FacEnv==null){echo "style='display:none;'";}?>>
                <tr>
                    <td> Carrier </td>
                    <td> Fecha de Emisión </td>
                    <td> Inicio Periodo a Facturar </td>
                    <td> Fin Periodo a Facturar </td>
                    <td> Fecha Envio </td>
                    <td> N°Documento </td>
                    <td> Minutos </td>
                    <td> Cantidad </td>
                    <td> Moneda </td>
                    <td> Acciones </td>
                </tr>
                <?php
                    if($lista_FacEnv!=null)
                    {
                        foreach ($lista_FacEnv as $key => $value)
                        { 
                            echo "<tr class='vistaTemp' id='".$value->id."'>
                                    <td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
                                    <td id='AccountingDocumentTemp[issue_date]'>".$value->issue_date."</td>
                                    <td id='AccountingDocumentTemp[from_date]'>".$value->from_date."</td>
                                    <td id='AccountingDocumentTemp[to_date]'>".$value->to_date."</td>
                                    <td id='AccountingDocumentTemp[sent_date]'>".$value->sent_date."</td>
                                    <td id='AccountingDocumentTemp[doc_number]'>".$value->doc_number."</td>
                                    <td id='AccountingDocumentTemp[minutes]'>".$value->minutes."</td>
                                    <td id='AccountingDocumentTemp[amount]'>".$value->amount."</td>
                                    <td id='AccountingDocumentTemp[id_currency]'>".$value->id_currency."</td>
                                    <td><img class='edit' name='edit_Fac_Env' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td>
                                  </tr>";  
                        }
                    }
                    ?>
         </table>
          
         <br>
        <label class="Label_F_Rec" <?php if($lista_FacRec==null){echo "style='display:none;'";}?>>Facturas Recibidas:</label>
        <table border="1" class="tablaVistDocTemporales lista_FacRec" <?php if($lista_FacRec==null){echo "style='display:none;'";}?>>
                <tr>
                    <td> Carrier </td>
                    <td> Fecha de Emisión </td>
                    <td> Inicio Periodo a Facturar </td>
                    <td> Fin Periodo a Facturar </td>
                    <td> Fecha Recep(Email)</td>
                    <td> Fecha Recep Valida</td>
                    <td> Hora Recep (Email)</td>
                    <td> Hora Recep Valida</td>
                    <td> N°Doc </td>
                    <td> Minutos </td>
                    <td> Cantidad </td>
                    <td> Moneda </td>
                    <td> Acciones </td>
                </tr>
                <?php
                    if($lista_FacRec!=null)
                    {
                        foreach ($lista_FacRec as $key => $value)
                        { 
                            echo "<tr class='vistaTemp' id='".$value->id."'>
                                    <td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
                                    <td id='AccountingDocumentTemp[issue_date]'>".$value->issue_date."</td>
                                    <td id='AccountingDocumentTemp[from_date]'>".$value->from_date."</td>
                                    <td id='AccountingDocumentTemp[to_date]'>".$value->to_date."</td>
                                    <td id='AccountingDocumentTemp[email_received_date]'>".$value->email_received_date."</td>
                                    <td id='AccountingDocumentTemp[valid_received_date]'>".$value->valid_received_date."</td>
                                    <td id='AccountingDocumentTemp[email_received_hour]'>".$value->email_received_hour."</td>
                                    <td id='AccountingDocumentTemp[valid_received_hour]'>".$value->valid_received_hour."</td>
                                    <td id='AccountingDocumentTemp[doc_number]'>".$value->doc_number."</td>
                                    <td id='AccountingDocumentTemp[minutes]'>".$value->minutes."</td>
                                    <td id='AccountingDocumentTemp[amount]'>".$value->amount."</td>
                                    <td id='AccountingDocumentTemp[id_currency]'>".$value->id_currency."</td>
                                    <td><img class='edit' name='edit_Fac_Rec' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td>
                                  </tr>";  
                        }
                    }
                    ?>
        </table>
        
        <br>

         <label class='LabelCobros' <?php if($lista_Cobros==null){echo "style='display:none;'";}?>>Cobros:</label>
         <table border="1" class="tablaVistDocTemporales lista_Cobros" <?php if($lista_Cobros==null){echo "style='display:none;'";}?>>
                <tr>
                    <td> Grupo </td>
                    <td> Fecha Recep Valida</td>
                    <td> N°Documento </td>
                    <td> Cantidad </td>
                    <td> Moneda </td>
                    <td> Acciones </td>
                </tr>
                <?php
                    if($lista_Cobros!=null)
                    {
                        foreach ($lista_Cobros as $key => $value)
                        { 
                            echo "<tr class='vistaTemp' id='".$value->id."'>
                                    <td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
                                    <td id='AccountingDocumentTemp[valid_received_date]'>".$value->valid_received_date."</td>
                                    <td id='AccountingDocumentTemp[doc_number]'>".$value->doc_number."</td>
                                    <td id='AccountingDocumentTemp[amount]'>".$value->amount."</td>
                                    <td id='AccountingDocumentTemp[id_currency]'>".$value->id_currency."</td>
                                    <td><img class='edit' name='edit_Cobros' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td>
                                  </tr>";  
                        }
                    }
                    ?>
         </table>  
         
         <br>
         <label class="LabelPagos" <?php if($lista_Pagos==null){echo "style='display:none;'";}?>>Pagos:</label>
         <table border="1" class="tablaVistDocTemporales lista_Pagos" <?php if($lista_Pagos==null){echo "style='display:none;'";}?>>
                <tr>
                    <td> Grupo </td>
                    <td> Fecha de Emisión </td>
                    <td> N°Documento </td>
                    <td> Cantidad </td>
                    <td> Moneda </td>
                    <td> Acciones </td>
                </tr>
                <?php
                    if($lista_Pagos!=null)
                    {
                        foreach ($lista_Pagos as $key => $value)
                        { 
                            echo "<tr class='vistaTemp' id='".$value->id."'>
                                    <td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
                                    <td id='AccountingDocumentTemp[issue_date]'>".$value->issue_date."</td>
                                    <td id='AccountingDocumentTemp[doc_number]'>".$value->doc_number."</td>
                                    <td id='AccountingDocumentTemp[amount]'>".$value->amount."</td>
                                    <td id='AccountingDocumentTemp[id_currency]'>".$value->id_currency."</td>
                                    <td><img class='edit' name='edit_Pagos' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td>
                                  </tr>";  
                        }
                    }
                    ?>
         </table>
         
         <br>
         <label class="Label_DispRec" <?php if($lista_DispRec==null){echo "style='display:none;'";}?>>Disputas Recibidas:</label>
         <table border="1" class="tablaVistDocTemporales lista_DispRec" <?php if($lista_DispRec==null){echo "style='display:none;'";}?>>
                <tr>
                   <td> Carrier </td>
                   <td> Destino </td>
                   <td> Num. Factura </td>
                   <td> Min Etx </td>
                   <td> Min Prov </td>
                   <td> Tarifa Etx </td>
                   <td> Tarifa Prov </td>
                   <td> Monto Etx</td>
                   <td> Monto Prov</td>
                   <td> Disputa</td>
                   <td> Acciones </td>
                </tr>
                <?php
                    if($lista_DispRec!=null)
                    {
                        foreach ($lista_DispRec as $key => $value)
                        { 
                            echo "<tr class='vistaTemp' id='".$value->id."'>
                                    <td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
                                    <td id='AccountingDocumentTemp[id_destination]'>".$value->id_destination."</td>
                                    <td id='AccountingDocumentTemp[id_accounting_document]'>".$value->id_accounting_document."</td>
                                    <td id='AccountingDocumentTemp[min_etx]'>".$value->min_etx."</td>
                                    <td id='AccountingDocumentTemp[min_carrier]'>".$value->min_carrier."</td>
                                    <td id='AccountingDocumentTemp[rate_etx]'>".$value->rate_etx."</td>
                                    <td id='AccountingDocumentTemp[rate_carrier]'>".$value->rate_carrier."</td>
                                    <td id='AccountingDocumentTemp[amount_etx]'>".$value->amount_etx."</td>
                                    <td id='AccountingDocumentTemp[amount]'>".$value->amount_carrier."</td>
                                    <td id='AccountingDocumentTemp[dispute]'>".Utility::format_decimal($value->dispute)."</td>
                                    <td><img class='edit' name='edit_DispRec' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td>
                                  </tr>";     
                        }
                    }
                    ?>
         </table>
         
         <br>
         <label class="Label_DispEnv"<?php if($lista_DispEnv==null){echo "style='display:none;'";}?>>Disputas Enviadas:</label>
         <table border="1" class="tablaVistDocTemporales lista_DispEnv"<?php if($lista_DispEnv==null){echo "style='display:none;'";}?>>
                <tr>
                   <td> Carrier </td>
                   <td> Destino Supp </td>
                   <td> Num. Factura </td>
                   <td> Min Etx </td>
                   <td> Min Prov </td>
                   <td> Tarifa Etx </td>
                   <td> Tarifa Prov </td>
                   <td> Monto Etx</td>
                   <td> Monto Prov</td>
                   <td> Disputa</td>
                   <td> Acciones </td>
                </tr>
                <?php
                    if($lista_DispEnv!=null)
                    {
                        foreach ($lista_DispEnv as $key => $value)
                        { 
                            echo "<tr class='vistaTemp' id='".$value->id."'>
                                    <td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
                                    <td id='AccountingDocumentTemp[id_destination]'>".$value->id_destination_supplier."</td>
                                    <td id='AccountingDocumentTemp[id_accounting_document]'>".$value->id_accounting_document."</td>
                                    <td id='AccountingDocumentTemp[min_etx]'>".$value->min_etx."</td>
                                    <td id='AccountingDocumentTemp[min_carrier]'>".$value->min_carrier."</td>
                                    <td id='AccountingDocumentTemp[rate_etx]'>".$value->rate_etx."</td>
                                    <td id='AccountingDocumentTemp[rate_carrier]'>".$value->rate_carrier."</td>
                                    <td id='AccountingDocumentTemp[amount_etx]'>".$value->amount_etx."</td>
                                    <td id='AccountingDocumentTemp[amount]'>".$value->amount_carrier."</td>
                                    <td id='AccountingDocumentTemp[dispute]'>".Utility::format_decimal($value->dispute)."</td>
                                    <td><img class='edit' name='edit_DispEnv' alt='editar' src='/images/icon_lapiz.png'><img class='delete' name='delete' alt='borrar' src='/images/icon_x.gif'></td>
                                  </tr>";     
                        }
                    }
                    ?>
         </table>
         <br>
         <label class="Label_NotCredEnv"<?php if($lista_NotCredEnv==null){echo "style='display:none;'";}?>>Notas de Crédito Enviadas:</label>
         <table border="1" class="tablaVistDocTemporales lista_NotCredEnv"<?php if($lista_NotCredEnv==null){echo "style='display:none;'";}?>>
                <tr> 
                   <td> Carrier </td>
                   <td> Num. Factura</td>
                   <td> Fecha de Emisión</td>
                   <td> Numero de Nota</td>
                   <td> Monto de Nota</td>
                   <td> Acciones </td>
                </tr>
                <?php
                    if($lista_NotCredEnv!=null)
                    {
                        foreach ($lista_NotCredEnv as $key => $value)
                        { 
                            echo "<tr class='vistaTemp' id='".$value->id."'>
                                    <td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
                                    <td id='AccountingDocumentTemp[id_accounting_document]'>".$value->id_accounting_document."</td>
                                    <td id='AccountingDocumentTemp[issue_date]'>".$value->issue_date."</td> 
                                    <td id='AccountingDocumentTemp[doc_number]'>".$value->doc_number."</td>
                                    <td id='AccountingDocumentTemp[amount]'>".$value->amount."</td>    
                                    <td><img class='edit' name='edit_Nota_cred' alt='editar' src='/images/icon_lapiz.png'><img class='delete' name='delete' alt='borrar' src='/images/icon_x.gif'></td>
                                  </tr>";     
                        }
                    }
                    ?>
         </table>
         <br>
         <label class="Label_NotCredRec"<?php if($lista_NotCredRec==null){echo "style='display:none;'";}?>>Notas de Crédito Recibidas:</label>
         <table border="1" class="tablaVistDocTemporales lista_NotCredRec"<?php if($lista_NotCredRec==null){echo "style='display:none;'";}?>>
                <tr>
                   <td> Carrier </td>
                   <td> Num. Factura</td>
                   <td> Fecha de Emisión</td>
                   <td> Numero de Nota</td>
                   <td> Monto de Nota</td>
                   <td> Acciones </td>
                </tr>
                <?php
                    if($lista_NotCredRec!=null)
                    {
                        foreach ($lista_NotCredRec as $key => $value)
                        { 
                            echo "<tr class='vistaTemp' id='".$value->id."'>
                                    <td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
                                    <td id='AccountingDocumentTemp[id_accounting_document]'>".$value->id_accounting_document."</td>
                                    <td id='AccountingDocumentTemp[issue_date]'>".$value->issue_date."</td> 
                                    <td id='AccountingDocumentTemp[doc_number]'>".$value->doc_number."</td>
                                    <td id='AccountingDocumentTemp[amount]'>".$value->amount."</td>    
                                    <td><img class='edit' name='edit_Nota_cred' alt='editar' src='/images/icon_lapiz.png'><img class='delete' name='delete' alt='borrar' src='/images/icon_x.gif'></td>
                                  </tr>";     
                        }
                    }
                    ?>
         </table>
     </div>
</div><!-- form -->

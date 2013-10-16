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
        $form=$this->beginWidget('CActiveForm',array(
            'id'=>'accounting-document-temp-form',
            'enableAjaxValidation'=>false,
            )
        );
    ?>
    <?php echo $form->errorSummary($model); ?>
    <p class="note">Fields with <span class="required">*</span> are required.</p>
    <div class="AccountingDocumentTemp_id_type_accounting_document">
        <?php echo $form->labelEx($model,'id_type_accounting_document'); ?>
        <?php echo $form->dropDownList($model,'id_type_accounting_document',TypeAccountingDocument::getListTypeAccountingDocument(),array('prompt'=>'Seleccione')); ?>
        <?php echo $form->error($model,'id_type_accounting_document'); ?>
    </div>
    
    <div class="CarrierDocument AccountingDocumentTemp_id_type_accounting_document">
        <?php echo $form->labelEx($model,'id_carrier'); ?>
        <?php echo $form->dropDownList($model,'id_carrier',Carrier::getListCarrierNoUNKNOWN(),array('prompt'=>'Seleccione')); ?>
        <?php echo $form->error($model,'id_carrier'); ?>
    </div>

    <div class="formularioDocumento">
        <div class="valoresDocumento">
            
<!--            <div class="GrupoDocument contratoForm">
                <label>Grupo *</label>-->
<!--                <select class='GrupoOperadores'>
                    <option>Selecione</option>
                    <option>BSG</option>
                    <option>Otro</option>
                </select>-->
                <?php // echo $form->dropDownList($model,'id_carrier',Carrier::getListCarrierNoUNKNOWN(),array('prompt'=>'Seleccione')); ?>
                <?php // echo $form->error($model,'id_carrier'); ?>
            <!--</div>-->
            
            <div class="contratoForm fechaDeEmision">
                <?php echo $form->labelEx($model,'issue_date'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                    'model'=>$model,
                    'attribute'=>'issue_date',
                    'options'=>array(
                        'dateFormat'=>'yy-mm-dd'
                        ),
                    'htmlOptions'=>array(
                        'size'=>'10', // textField size
                        'maxlength'=>'10', // textField maxlength
                        ),
                    )
                    ); 
                ?>
                <?php echo $form->error($model,'issue_date'); ?>
            </div>
            <div class="contratoForm fechaDeInicio">
                <?php echo $form->labelEx($model,'from_date'); ?>
                <?php 
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model'=>$model,
                        'attribute'=>'from_date',
                        'options'=>array(
                            'dateFormat'=>'yy-mm-dd'
                            ),
                        'htmlOptions'=>array(
                            'size'=>'10', // textField size
                            'maxlength'=>'10', // textField maxlength
                            )
                        )
                    ); 
                ?>
                <?php echo $form->error($model,'from_date'); ?>
            </div>
            <div class="contratoForm fechaFinal">
                <?php echo $form->labelEx($model,'to_date'); ?>
                <?php 
                    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                        'model'=>$model,
                        'attribute'=>'to_date',
                        'options'=>array(
                            'dateFormat'=>'yy-mm-dd'
                            ),
                        'htmlOptions'=>array(
                            'size'=>'10', // textField size
                            'maxlength'=>'10', // textField maxlength
                            )
                        )
                    ); 
                ?>
                <?php echo $form->error($model,'to_date'); ?>
            </div>

            <div class="contratoForm emailReceivedDate">
                <label class='emailRecDate'>Fecha de recepción de Email</label>
                <?php 
                    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                        'model'=>$model,
                        'attribute'=>'email_received_date',
                        'options'=>array(
                            'dateFormat'=>'yy-mm-dd'
                            ),
                        'htmlOptions'=>array(
                            'size'=>'10', // textField size
                            'maxlength'=>'10', // textField maxlength
                            )
                        )
                    ); 
                ?>
                <?php echo $form->error($model,'email_received_date'); ?>
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
            
            <div class="contratoForm numDocument">
                <?php echo $form->labelEx($model,'doc_number'); ?>
                <?php echo $form->textField($model,'doc_number',array('size'=>50,'maxlength'=>50)); ?>
                <?php echo $form->error($model,'doc_number'); ?>
            </div>
            <div class="contratoForm minutosDoc">
                <?php echo $form->labelEx($model,'minutes'); ?>
                <?php echo $form->textField($model,'minutes'); ?>
                <?php echo $form->error($model,'minutes'); ?>
            </div>
            <div class="contratoForm montoDoc">
                <?php echo $form->labelEx($model,'amount'); ?>
                <?php echo $form->textField($model,'amount'); ?>
                <?php echo $form->error($model,'amount'); ?>
            </div>
            
            <!--<div class='contratoForm'>-->
                <?php // echo $form->labelEx($model,'moneda'); ?>
                <?php // echo $form->dropDownList($model,'id',GeographicZone::getListGeo(),
//                 array(
//                    'prompt'=>'Seleccione'
//                     )
//                ); ?>
                <?php // echo $form->error($model,'id'); ?>
            <!--</div>-->
            
            <div class="hacerUnaNota">
                <br>
                <label>Nota (+)</label>
            </div>
            <div class="contratoFormTextArea">
                <br>
                <label class="quitaNota">
                    <b>Nota (-)</b>
                </label>
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
        <div id="botAgregarDatosContableFinal" class="row buttons" <?php if($lista==null){echo "style='display:none;'";}?>>
            <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardado Definitivo' : 'Save'); ?>
        </div>
            <table border="1" class="tablaVistDocTemporales" <?php if($lista==null){echo "style='display:none;'";}?>>
                <tr>
                    <td> Tipo de Doc </td>
                    <td> Carrier </td>
                    <td> Fecha de Emisión </td>
                    <td> Inicio Periodo a Facturar </td>
                    <td> Fin Periodo a Facturar </td>
                    <td> Fecha Envio </td>
                    <td> Fecha Recep(Email)</td>
                    <td> Fecha Recep Valida</td>
                    <td> Hora Recep (Email)</td>
                    <td> Hora Recep Valida</td>
                    <td> N°Documento </td>
                    <td> Minutos </td>
                    <td> Cantidad </td>
                    <td> Moneda </td>
                    <td> Acciones </td>
                </tr>
                <?php
                    if($lista!=null)
                    {
                        foreach ($lista as $key => $value)
                        { 
                            echo "<tr class='vistaTemp' id='".$value->id."'>
                                    <td id='AccountingDocumentTemp[id_type_accounting_document]'>".$value->id_type_accounting_document."</td>
                                    <td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
                                    <td id='AccountingDocumentTemp[issue_date]'>".$value->issue_date."</td>
                                    <td id='AccountingDocumentTemp[from_date]'>".$value->from_date."</td>
                                    <td id='AccountingDocumentTemp[to_date]'>".$value->to_date."</td>
                                    <td id='AccountingDocumentTemp[sent_date]'>".$value->sent_date."</td>
                                    <td id='AccountingDocumentTemp[email_received_date]'>".$value->email_received_date."</td>
                                    <td id='AccountingDocumentTemp[valid_received_date]'>".$value->valid_received_date."</td>
                                    <td id='AccountingDocumentTemp[email_received_hour]'>".$value->email_received_hour."</td>
                                    <td id='AccountingDocumentTemp[valid_received_hour]'>".$value->valid_received_hour."</td>
                                    <td id='AccountingDocumentTemp[doc_number]'>".$value->doc_number."</td>
                                    <td id='AccountingDocumentTemp[minutes]'>".$value->minutes."</td>
                                    <td id='AccountingDocumentTemp[amount]'>".$value->amount."</td>
                                    <td id='AccountingDocumentTemp[moneda]'>???</td>
                                    <td><img class='edit' name='edit' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td>
                                  </tr>";  
                        }
                    }
                    ?>
                </table>
            </div>
        </div><!-- form -->
   <div class='mensajeFinal'>
         <h3>El documento contable fue guardado con exito</h3>
         <table border="4" class='tablamensaje'>
            <tr>
                <td> Tipo de Doc </td>
                <td> Carrier </td>
                <td> Fecha de Emisión </td>
                <td> Monto </td>
            </tr>
        </table>
        <p><img src='/images/si.png'width='95px' height='95px'/></p>
   </div>
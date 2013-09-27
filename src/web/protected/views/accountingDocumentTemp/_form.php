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
    <div class="formularioDocumento">
        <div class="valoresDocumento">
            <div class="AccountingDocumentTemp_id_type_accounting_document  contratoForm">
                <?php echo $form->labelEx($model,'id_carrier'); ?>
                <?php echo $form->dropDownList($model,'id_carrier',Carrier::getListCarrierNoUNKNOWN(),array('prompt'=>'Seleccione')); ?>
                <?php echo $form->error($model,'id_carrier'); ?>
            </div>
            <div class="contratoForm">
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
            <div class="contratoForm">
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
            <div class="contratoForm">
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
            <div class="contratoForm">
                <?php echo $form->labelEx($model,'received_date'); ?>
                <?php 
                    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                        'model'=>$model,
                        'attribute'=>'received_date',
                        'options'=>array(
                            'dateFormat'=>'yy-mm-dd'
                            ),
                        'htmlOptions'=>array(
                            'size'=>'10', // textField size
                            'maxlength' => '10', // textField maxlength
                            )
                        )
                    ); 
                ?>
                <?php echo $form->error($model,'received_date'); ?>
            </div>
            <div class="contratoForm">
                <?php echo $form->labelEx($model,'sent_date'); ?>
                <?php 
                    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                        'model'=>$model,
                        'attribute'=>'sent_date',
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
                <label class="quitaNota">
                    <b>Nota (-)</b>
                </label>
                <?php echo $form->textArea($model,'note',array('size'=>60,'maxlength'=>250)); ?>
                <?php echo $form->error($model,'note'); ?>
            </div>
            <br>
            <div id="botAgregarDatosContable" class="row buttons">
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Agregar' : 'Save'); ?>
            </div>
            <div class="VistDocTemporales">
                <table border="1" class="tablaVistDocTemporales">
                    <tr>
                        <td> Tipo de Doc </td>
                        <td> Carrier </td>
                        <td> Fecha de Emisión </td>
                        <td> Fecha de Inicio </td>
                        <td> Fecha de Culminación </td>
                        <td> Fecha Recepción </td>
                        <td> Fecha Envio </td>
                        <td> N°Documento </td>
                        <td> Minutos </td>
                        <td> Cantidad </td>
                        <td> Acciones </td>
                    </table>
                </div>
                <br>
                <div id="botAgregarDatosContableFinal" class="row buttons">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Save'); ?>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
        </div>
        <!-- form -->
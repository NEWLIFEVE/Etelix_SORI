<?php
/* @var $this AccountingDocumentController */
/* @var $model AccountingDocument */
/* @var $form CActiveForm */
?>

<div class="form">
    <div class="instruccion">
        <h3>Confirme o Modifique las facturas Enviadas</h3>
    </div>
    <?php 
        $form=$this->beginWidget('CActiveForm',array(
            'id'=>'accounting-document-form',
            'enableAjaxValidation'=>false,
            )
        );
    ?>
    <?php echo $form->errorSummary($model); ?>
    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php $this->endWidget(); ?>
    <div class="VistDocTemporales">
        <!--<br>-->
<!--        <div id="botAgregarDatosContableFinal" class="row buttons" <?php // if($lista==null){echo "style='display:none;'";}?>>
            <?php // echo CHtml::submitButton($model->isNewRecord ? 'Guardado Definitivo' : 'Save'); ?>
        </div>-->
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
                    <td> Confirm <input type="checkbox"  id="todos" class="custom-checkbox" name="lista[todos]" onClick="marcar(this);"> </td>
                </tr>
                <?php
                    if($lista!=null)
                    {
                        foreach ($lista as $key => $value)
                        { 
                            echo "<tr class='vistaTemp' id='".$value->id."'>
                                    <td id='AccountingDocument[id_type_accounting_document]'>".$value->id_type_accounting_document."</td>
                                    <td id='AccountingDocument[id_carrier]'>".$value->id_carrier."</td>
                                    <td id='AccountingDocument[issue_date]'>".$value->issue_date."</td>
                                    <td id='AccountingDocument[from_date]'>".$value->from_date."</td>
                                    <td id='AccountingDocument[to_date]'>".$value->to_date."</td>
                                    <td id='AccountingDocument[sent_date]'>".$value->sent_date."</td>
                                    <td id='AccountingDocument[email_received_date]'>".$value->email_received_date."</td>
                                    <td id='AccountingDocument[valid_received_date]'>".$value->valid_received_date."</td>
                                    <td id='AccountingDocument[email_received_hour]'>".$value->email_received_hour."</td>
                                    <td id='AccountingDocument[valid_received_hour]'>".$value->valid_received_hour."</td>
                                    <td id='AccountingDocument[doc_number]'>".$value->doc_number."</td>
                                    <td id='AccountingDocument[minutes]'>".$value->minutes."</td>
                                    <td id='AccountingDocument[amount]'>".$value->amount."</td>
                                    <td id='AccountingDocument[id_currency]'>".$value->id_currency."</td>
                                    <td><img class='edit' name='edit' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td>
                                    <td id='AccountingDocument[confirma]'><input type='checkbox' value='".$value->id."' class='custom-checkbox' name='confirma'></td>
                                  </tr>";  
                        }
                    }
                    ?>
                </table>
            </div>
    
                        <br><div id="botConfirmarDatosContableFinal" class="row buttons" <?php if($lista==null){echo "style='display:none;'";}?>>
                            <?php echo CHtml::submitButton($model->isNewRecord ? 'Confirmar facturas enviadas' : 'Save'); ?>
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

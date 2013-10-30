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
    <div class="formularioDocumento">
        
            <div class="clockhide">
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
        
            <div class="clockhide">
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
    </div>
     
    <?php $this->endWidget(); ?>
     
    <div class="VistDocTemporales">

            <table border="1" class="tablaVistDocTemporales lista_FacEnv" <?php if($lista==null){echo "style='display:none;'";}?>>
                <tr>
                    
                    <td> Carrier </td>
                    <td> Fecha de Emisión </td>
                    <td> Inicio Periodo a Facturar </td>
                    <td> Fin Periodo a Facturar </td>
                    <td> Fecha Envio </td>
<!--                    <td> Fecha Recep(Email)</td>
                    <td> Fecha Recep Valida</td>
                    <td> Hora Recep (Email)</td>
                    <td> Hora Recep Valida</td>-->
                    <td> N°Documento </td>
                    <td> Minutos </td>
                    <td> Cantidad </td>
                    <td> Moneda </td>
              <!--  <td> Acciones </td> 
                      se comentaron las acciones, si hay que volver a colocar, aqui esta el php...-->
                     <!--<td><img class='edit' name='edit' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td>-->
                    <td> Confirm <input type="checkbox"  id="todos" class="custom-checkbox" name="lista[todos]" onClick="marcar(this);"> </td>
                </tr>
                <?php
                    if($lista!=null)
                    {
                        foreach ($lista as $key => $value)
                        { 
                            echo "<tr class='vistaTemp' id='".$value->id."'>
                                   
                                    <td id='AccountingDocument[id_carrier]'>".$value->id_carrier."</td>
                                    <td id='AccountingDocument[issue_date]'>".$value->issue_date."</td>
                                    <td id='AccountingDocument[from_date]'>".$value->from_date."</td>
                                    <td id='AccountingDocument[to_date]'>".$value->to_date."</td>
                                    <td id='AccountingDocument[sent_date]'>".$value->sent_date."</td>

                                    <td id='AccountingDocument[doc_number]'>".$value->doc_number."</td>
                                    <td id='AccountingDocument[minutes]'>".$value->minutes."</td>
                                    <td id='AccountingDocument[amount]'>".$value->amount."</td>
                                    <td id='AccountingDocument[id_currency]'>".$value->id_currency."</td>
                                   
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

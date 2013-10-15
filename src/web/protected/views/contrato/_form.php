<?php
/* @var $this ContratoController */
/* @var $model Contrato */
/* @var $form CActiveForm */
?>
<h3>Seleccione un Carrier para comenzar</h3>
<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'contrato-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => true,
            ));
    ?>

    <p class="note">Los Campos con <span class="required">*</span> son obligatorios.</p>

    <div class="CarrierActual">
    </div>
        <?php echo $form->errorSummary($model); ?>

    <div class="row carrierSelect">
        <?php echo $form->labelEx($model, 'id_carrier'); ?>  
        <?php echo $form->dropDownList($model, 'id_carrier', Carrier::getListCarrierNoUNKNOWN(), array('prompt' => 'Seleccione')); ?> 
        <?php echo $form->error($model, 'id_carrier'); ?>
    </div>
    <div class="formularioContrato">
        <div class="pManager"><p><b>Account Manager</b></p></div>
        <div class="divOculto1">
            <div class="manageractual"> </div>
        </div>

        <div class="divOculto">
            <div class="valores">
                <div class="contratoForm">

                    <?php echo $form->labelEx($model, 'id_company'); ?>
                    <?php echo $form->dropDownList($model, 'id_company', Company::getListCompany(), array('prompt' => 'Seleccione')); ?> 
                    <?php echo $form->error($model, 'id_company'); ?>
                </div>

                <div class="contratoForm">
                    <?php echo $form->labelEx($model, 'sign_date'); ?>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $model,
                        'attribute' => 'sign_date',
                        'options' => array('dateFormat' => 'yy-mm-dd'),
                        'htmlOptions' => array(
                            'size' => '10', // textField size
                            'maxlength' => '10', // textField maxlength
                            )));
                    ?>
                    <?php echo $form->error($model, 'sign_date'); ?>
                </div>

                <div class="contratoForm">
                    <?php echo $form->labelEx($model, 'production_date'); ?>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $model,
                        'attribute' => 'production_date',
                        'options' => array('dateFormat' => 'yy-mm-dd'),
                        'htmlOptions' => array(
                            'dateFormat' => 'yy-mm-dd',
                            'size' => '10', // textField size
                            'maxlength' => '10', // textField maxlength
                            )));
                    ?>
                    <?php echo $form->error($model, 'production_date'); ?>
                </div>

                <div class="contratoForm">
                    <?php echo $form->labelEx($model, 'end_date'); ?>
                    <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $model,
                        'attribute' => 'end_date',
                        'options' => array('dateFormat' => 'yy-mm-dd'),
                        'htmlOptions' => array(
                            'dateFormat' => 'yy-mm-dd',
                            'size' => '10', // textField size
                            'maxlength' => '10', // textField maxlength
                            )));
                    ?>
                    <?php echo $form->error($model, 'end_date'); ?>

                </div>
                <!--<div class="SegundoNivel">-->
                <div class="contratoForm">

                     <?php echo $form->labelEx($model,'id_termino_pago'); ?>
                     <?php echo $form->dropDownList($model,'id_termino_pago',TerminoPago::getListTermPago(),
                     array('prompt'=>'Seleccione')
                     ); ?> 
                     <?php echo $form->error($model,'id_termino_pago'); ?>
                </div>
                
                <div class="contratoForm">
                     <?php echo $form->labelEx($model,'id_monetizable'); ?>
                     <?php echo $form->dropDownList($model,'id_monetizable',Monetizable::getListMonetizable(),
                     array('prompt'=>'Seleccione')
                     ); ?> 
                     <?php echo $form->error($model,'id_monetizable'); ?>
                </div>
                
                <div class="contratoForm">
                     <?php echo $form->labelEx($model, 'id_disputa'); ?>
                     <?php echo $form->textField($model, 'id_disputa'); ?>
                     <?php echo $form->error($model, 'id_disputa'); ?>
                </div>
                
                <div class="contratoForm">
                     <?php echo $form->labelEx($model, 'id_limite_credito'); ?>
                     <?php echo $form->textField($model, 'id_limite_credito'); ?> 
                     <?php echo $form->error($model, 'id_limite_credito'); ?>
                </div>
                
                <div class="contratoForm">
                     <?php echo $form->labelEx($model, 'id_limite_compra'); ?>
                     <?php echo $form->textField($model, 'id_limite_compra'); ?> 
                     <?php echo $form->error($model, 'id_limite_compra'); ?>
                </div>
                <div class="contratoForm">
                    <?php echo $form->labelEx($model, 'up'); ?>
                    <?php echo $form->dropDownList($model, 'up',array( 0=>"Ventas", 1=>"Presidencia"),array('prompt'=>'Seleccione')); ?> 
                    <?php echo $form->error($model, 'up'); ?>
                </div>
                     <input type="hidden" id="dias_disputa_Oculto"  value="">
                     <input type="hidden" id="credito_Oculto"  value="">
                     <input type="hidden" id="compra_Oculto"  value="">
                     <input type="hidden" id="monetizable_Oculto"  value="">
                     <input type="hidden" id="TerminoP_Oculto"  value="">
                     <input type="hidden" id="F_Firma_Contrato_Oculto"  value="">
                     <input type="hidden" id="F_P_produccion_Oculto"  value="">
                     <input type="hidden" id="Contrato_upOculto"  value="">
            </div>

            <br>
<?php $this->endWidget(); ?>

            <div id="botAsignarContrato" class="row buttons">
<?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Save'); ?>
            </div>
        </div>
    </div>
</div><!-- form -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>

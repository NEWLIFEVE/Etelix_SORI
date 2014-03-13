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
            <h1 class="hCarrierA"></h1>
    </div>
        <?php echo $form->errorSummary($model); ?>

    <div class="row carrierSelect">
        <?php echo $form->labelEx($model, 'id_carrier'); ?>  
        <?php echo $form->dropDownList($model, 'id_carrier', Carrier::getListCarrierNoUNKNOWN(), array('prompt' => 'Seleccione')); ?>
        <?php echo $form->error($model, 'id_carrier'); ?>
    </div>
    <div class="formularioContrato">
        <div class="pManager"><p><b>Account Manager</b></p></div>
        <div class="manageractual"> 
             <h3 class="hManagerA"></h3>
        </div>
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
                <div class="contratoForm">
                     <?php echo $form->labelEx($model,'id_termino_pago'); ?>
                     <?php echo $form->dropDownList($model,'id_termino_pago',TerminoPago::getListTermPago(),
                     array('prompt'=>'Seleccione')
                     ); ?> 
                     <?php echo $form->error($model,'id_termino_pago'); ?>
                </div>
                
                <div class="contratoForm">
                    <?php echo $form->labelEx($model,'id_termino_pago_supplier'); ?>
                     <?php echo $form->dropDownList($model,'id_termino_pago_supplier',TerminoPago::getListTermPago(),
                     array('prompt'=>'Seleccione')
                     ); ?> 
                     <?php echo $form->error($model,'id_termino_pago_supplier'); ?>
                </div>
                
                <div class="contratoForm periodo_fact">
                    <label>Tipo de Ciclo de Fact</label>
                     <?php echo $form->dropDownList($model,'id_fact_period',  FactPeriod::getListFact_Period(),
                     array('prompt'=>'Seleccione')
                     ); ?> 
                     <?php echo $form->error($model,'id_fact_period'); ?>
                </div>
                
                <div class="contratoForm divide_fact">
                    <label>Divide Fact por Mes</label>
                    <select name="divide_fact" id="divide_fact">
                        <option value=""> Seleccione </option>
                        <option value="1"> Si </option>
                        <option value="0"> No </option>
                    </select>
                </div>
                
                <div class="contratoForm dia_ini_fact">
                    <label>Dia de Inicio de Ciclo</label>
                    <select name="dia_ini_fact" id="dia_ini_fact">
                        <option value=""> Seleccione </option>
                        <option value="1"> Lunes </option>
                        <option value="2"> Martes </option>
                        <option value="3"> Miercoles </option>
                        <option value="4"> Jueves </option>
                        <option value="5"> Viernes </option>
                        <option value="6"> Sabado </option>
                        <option value="7"> Domingo </option>
                    </select>
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
                     <?php echo $form->labelEx($model, 'id_disputa_solved'); ?>
                     <?php echo $form->textField($model, 'id_disputa_solved'); ?>
                     <?php echo $form->error($model, 'id_disputa_solved'); ?>
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
                
                <div class="contratoForm">
                    <?php echo $form->labelEx($model, 'status'); ?>
                    <?php echo $form->dropDownList($model, 'status',array( 0=>"Inactivo", 1=>"Activo"),array('prompt'=>'Seleccione')); ?> 
                    <?php echo $form->error($model, 'status'); ?>
                </div>
                
                <div class="contratoForm Bank Fee">
                    <?php echo $form->labelEx($model, 'bank_fee'); ?>
                    <?php echo $form->dropDownList($model, 'bank_fee',array( 0=>"No", 1=>"Si"),array('prompt'=>'Seleccione')); ?> 
                    <?php echo $form->error($model, 'bank_fee'); ?>
                </div>
                     <input type="hidden" name="dias_disputa_Oculto"     id="dias_disputa_Oculto"  value="">
                     <input type="hidden" name="dias_disputa_solved_Oculto"id="dias_disputa_solved_Oculto"  value="">
                     <input type="hidden" name="credito_Oculto"          id="credito_Oculto"  value="">
                     <input type="hidden" name="compra_Oculto"           id="compra_Oculto"  value="">
                     <input type="hidden" name="monetizable_Oculto"      id="monetizable_Oculto"  value="">
                     <input type="hidden" name="TerminoP_Oculto"         id="TerminoP_Oculto"  value="">
                     <input type="hidden" name="TerminoP_supplier_Oculto"id="TerminoP_supplier_Oculto"  value="">
                     <input type="hidden" name="divide_fact_Oculto"      id="divide_fact_Oculto"  value="">
                     <input type="hidden" name="id_fact_period_Oculto"   id="id_fact_period_Oculto"  value="">
                     <input type="hidden" name="dia_ini_fact_Oculto"     id="dia_ini_fact_Oculto"  value="">
                     <input type="hidden" name="F_Firma_Contrato_Oculto" id="F_Firma_Contrato_Oculto"  value="">
                     <input type="hidden" name="F_P_produccion_Oculto"   id="F_P_produccion_Oculto"  value="">
                     <input type="hidden" name="Contrato_upOculto"       id="Contrato_upOculto"  value="">
                     <input type="hidden" name="Contrato_statusOculto"   id="Contrato_statusOculto"  value="">
                     <input type="hidden" name="bank_feeOculto"          id="bank_feeOculto"  value="">
            </div>

            <br>
<?php $this->endWidget(); ?>

            <div id="botAsignarContrato" class="row buttons">
                 <?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Save'); ?>
            </div>
    </div>
</div><!-- form -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>

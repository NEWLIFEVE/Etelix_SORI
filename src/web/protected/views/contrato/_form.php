<?php
/* @var $this ContratoController */
/* @var $model Contrato */
/* @var $form CActiveForm */
?>
<h2>Seleccione un Carrier para comenzar</h2>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'contrato-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Los Campos con <span class="required">*</span> son obligatorios.</p>
        <div class="CarrierActual">
   
		<?php echo $form->textField($model,'id_carrier1'); ?>
	</div>
	<?php echo $form->errorSummary($model); ?>

        <div class="row">
		<?php echo $form->labelEx($model,'id_carrier'); ?>  
                <?php echo $form->dropDownList($model,'id_carrier',
                CHtml::listData(Carrier::model()->findAll(array('order'=>'name')),'id','name'),
                array('prompt'=>'Seleccione')
                ); ?> 
		<?php echo $form->error($model,'id_carrier'); ?>
	</div>
        
   <div class="pManager"><p><b>Account Manager</b></p></div>
   <div class="divOculto1">
        <div class="row">
		<?php // echo $form->labelEx($model,'id_managers'); ?>
		<?php echo $form->textField($model,'id_managers'); ?>
		<?php echo $form->error($model,'id_managers'); ?>
	</div>
   </div>
   <div class="divOculto">
       	<div class="company">
		<?php echo $form->labelEx($model,'id_company'); ?>
                <?php echo $form->dropDownList($model,'id_company',
                CHtml::listData(company::model()->findAll(array('order'=>'name')),'id','name'),
                array('prompt'=>'Seleccione')
                ); ?>
		<?php echo $form->error($model,'id_company'); ?>
	</div>
       
	<div class="fechaFirma">
		<?php echo $form->labelEx($model,'sign_date'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                           'model' => $model,
                           'attribute' => 'sign_date',
                           'htmlOptions' => array(
                               'size' => '10', // textField size
                               'maxlength' => '10', // textField maxlength
                 ))); ?>
                 <?php echo $form->error($model,'sign_date'); ?>
	</div>

	<div class="fechaProduccion">
		<?php echo $form->labelEx($model,'production_date'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                           'model' => $model,
                           'attribute' => 'production_date',
                           'htmlOptions' => array(
                               'size' => '10', // textField size
                               'maxlength' => '10', // textField maxlength
                 ))); ?>
		<?php echo $form->error($model,'production_date'); ?>
	</div>

	<div class="fechaFin">
		<?php echo $form->labelEx($model,'end_date'); ?>
                <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                           'model' => $model,
                           'attribute' => 'end_date',
                           'htmlOptions' => array(
                               'size' => '10', // textField size
                               'maxlength' => '10', // textField maxlength
                 ))); ?>
		<?php echo $form->error($model,'end_date'); ?>
	</div>
        
        <div class="TPago">
		<?php echo $form->labelEx($model,'id_termino_pago'); ?>
                <?php echo $form->dropDownList($model,'id_termino_pago',
                CHtml::listData(TerminoPago::model()->findAll(array('order'=>'id')),'id','name'),
                array('prompt'=>'Seleccione')
                ); ?> 
		<?php echo $form->error($model,'id_termino_pago'); ?>
	</div>
        <div class="monetizable">
		<?php echo $form->labelEx($model,'id_monetizable'); ?>
                <?php echo $form->dropDownList($model,'id_monetizable',
                CHtml::listData(Monetizable::model()->findAll(array('order'=>'id')),'id','name'),
                array('prompt'=>'Seleccione')
                ); ?> 
		<?php echo $form->error($model,'id_monetizable'); ?>
	</div>
       <div class="disputa">
		<?php echo $form->labelEx($model,'id_disputa'); ?>
                <?php echo $form->textField($model,'id_disputa');?>
		<?php echo $form->error($model,'id_disputa'); ?>
	</div>
        <div class="limites">
		<?php echo $form->labelEx($model,'id_limites'); ?>
                <?php echo $form->dropDownList($model,'id_limites',
                CHtml::listData(Limites::model()->findAll(array('order'=>'name')),'id','name'),
                array('prompt'=>'Seleccione')
                ); ?> 
		<?php echo $form->error($model,'id_limites'); ?>
	</div>
        <br>
	<div id="botAsignar" class="row b">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Save'); ?>
	</div>
   </div>
<?php $this->endWidget(); ?>

</div><!-- form -->

<script>

  $('#Contrato_id_carrier').change(function()
    {
        var muestraDiv1= $('.divOculto');
        var muestraDiv2= $('.divOculto1');
        var pManager= $('.pManager');
        var NombreCarrier= $('.CarrierActual');
        var idCarrier = $("#Contrato_id_carrier").val();
        $("#Contrato_id_company").val('');
        $("#Contrato_sign_date").val('');
        $("#Contrato_production_date").val('');
        $("#Contrato_id_termino_pago").val('');
        $("#Contrato_id_monetizable").val('');
        $("#Contrato_id_disputa").val('');
        
            $.ajax({           
                type: "GET",
                url: "DynamicDatosContrato",
                data: "idCarrier="+idCarrier,

                success: function(data) 
                        {
                            obj = JSON.parse(data);
                            $("#Contrato_id_company").val(obj.company);
                            if (obj.company!=''){
                                $("#Contrato_id_company").prop("disabled", true);
                                $("#Contrato_end_date").prop("disabled", false);
                                $("#Contrato_sign_date").prop("disabled", true);
                            }else{
                                $("#Contrato_id_company").prop("disabled", false);
                                $("#Contrato_end_date").prop("disabled", true);
                                $("#Contrato_sign_date").prop("disabled", false);
                            }
                            
                            $("#Contrato_sign_date").val(obj.sign_date);
                            $("#Contrato_production_date").val(obj.production_date);
                            $("#Contrato_id_termino_pago").val(obj.termino_pago);
                            $("#Contrato_id_monetizable").val(obj.monetizable);
                            $("#Contrato_id_managers").val(obj.manager);
                            $("#Contrato_id_disputa").val(obj.dias_disputa);
                        }       
            }); 
           muestraDiv2.toggle("slow");
           pManager.toggle("slow");
           muestraDiv1.slideDown("slow");
           NombreCarrier.toggle("slow");

    });
    
$('#botAsignar').click('on',function()
{
    var end_date = $("#Contrato_end_date").val();
    alert(end_date);
});

</script>
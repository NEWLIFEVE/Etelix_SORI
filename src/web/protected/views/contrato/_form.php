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
           
	</div>
	<?php echo $form->errorSummary($model); ?>

        <div class="row carrierSelect">
		<?php echo $form->labelEx($model,'id_carrier'); ?>  
                <?php echo $form->dropDownList($model,'id_carrier',
                CHtml::listData(Carrier::model()->findAll(array('order'=>'name')),'id','name'),
                array('prompt'=>'Seleccione')
                ); ?> 
		<?php echo $form->error($model,'id_carrier'); ?>
	</div>
        
   <div class="pManager"><p><b>Account Manager</b></p></div>
   <div class="divOculto1">
        <div class="manageractual"> </div>
   </div>
   <div class="divOculto">
       <div class="valores">
       	<div class="contratoForm">
		<?php echo $form->labelEx($model,'id_company'); ?>
                <?php echo $form->dropDownList($model,'id_company',
                CHtml::listData(Company::model()->findAll(array('order'=>'name')),'id','name'),
                array('prompt'=>'Seleccione')
                ); ?>
		<?php echo $form->error($model,'id_company'); ?>
	</div>
       
	<div class="contratoForm">
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

	<div class="contratoForm">
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

	<div class="contratoForm">
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
       <!--<div class="SegundoNivel">-->
        <div class="contratoForm">
		<?php echo $form->labelEx($model,'id_termino_pago'); ?>
                <?php echo $form->dropDownList($model,'id_termino_pago',
                CHtml::listData(TerminoPago::model()->findAll(array('order'=>'id')),'id','name'),
                array('prompt'=>'Seleccione')
                ); ?> 
		<?php echo $form->error($model,'id_termino_pago'); ?>
	</div>
        <div class="contratoForm">
		<?php echo $form->labelEx($model,'id_monetizable'); ?>
                <?php echo $form->dropDownList($model,'id_monetizable',
                CHtml::listData(Monetizable::model()->findAll(array('order'=>'id')),'id','name'),
                array('prompt'=>'Seleccione')
                ); ?> 
		<?php echo $form->error($model,'id_monetizable'); ?>
	</div>
       <div class="contratoForm">
		<?php echo $form->labelEx($model,'id_disputa'); ?>
                <?php echo $form->textField($model,'id_disputa');?>
		<?php echo $form->error($model,'id_disputa'); ?>
	</div>
        <div class="contratoForm">
		<?php echo $form->labelEx($model,'id_limite_credito'); ?>
                <?php echo $form->textField($model,'id_limite_credito',
                CHtml::listData(Limites::model()->findAll(array('order'=>'name')),'id','name'),
                array('prompt'=>'Seleccione')
                ); ?> 
		<?php echo $form->error($model,'id_limite_credito'); ?>
	</div>
        <div class="contratoForm">
		<?php echo $form->labelEx($model,'id_limite_compra'); ?>
                <?php echo $form->textField($model,'id_limite_compra',
                CHtml::listData(Limites::model()->findAll(array('order'=>'name')),'id','name'),
                array('prompt'=>'Seleccione')
                ); ?> 
		<?php echo $form->error($model,'id_limite_compra'); ?>
	</div>
       </div>
        <br>
        <?php $this->endWidget(); ?>
	<div id="botAsignar" class="row buttons">
                
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Save'); ?>
	</div>
       <!--</div>-->
       <div class="lineabajo">
        <!--<img src="/images/lineabajo.png" width="10px" height="45px" >-->
   </div>

   
   </div>
</div><!-- form -->

<script>

  $('#Contrato_id_carrier').change(function()
    {
        var muestraDiv1= $('.divOculto');
        var muestraDiv2= $('.divOculto1');
        var pManager= $('.pManager');
        var NombreCarrier= $('.CarrierActual');
        var idCarrier = $("#Contrato_id_carrier").val();
        var end_date = $("#Contrato_end_date").val();
        
        $("#Contrato_id_company").val('');
        $("#Contrato_sign_date").val('');
        $("#Contrato_production_date").val('');
        $("#Contrato_id_termino_pago").val('');
        $("#Contrato_id_monetizable").val('');
        $("#Contrato_id_disputa").val('');

        $(".manageractual").empty();
        $(".CarrierActual").empty();
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

                            var manageractual = (obj.manager);
                            var carrierenlabel = (obj.carrier);
                            var fechaManagerCarrier = (obj.fechaManager);
                            
                           var managerA = $("<label><h3 style='margin-left: -66px; margin-top: \n\
                                             105px; color:rgba(111,204,187,1)'>"+manageractual+" / " +fechaManagerCarrier+"</h3></label><label><h6 style='margin-left: -66px; margin-top: \n\
                                             -10px; '>         </h6></label>");
                            var carrierA = $("<label id='labelCarrier'><h1 align='right' style='margin-left: 8px; margin-top: \n\
                                             -106px; color:rgba(111,204,187,1)'>"+carrierenlabel+"</h1></label>");
                                $('.manageractual').append(managerA);
                                $('.CarrierActual').append(carrierA);
                                managerA.slideDown('slow');    
                                carrierA.slideDown('slow'); 
                  
         
                  }       

            }); 
           muestraDiv2.slideDown("slow");
           pManager.slideDown("slow");
           muestraDiv1.slideDown("slow");
           NombreCarrier.slideDown("slow");

    });
    
$('#botAsignar').click('on',function(e)
{   
    e.preventDefault();
    $("#Contrato_id_company").prop("disabled", false);
    $("#Contrato_end_date").prop("disabled", false);
    $("#Contrato_sign_date").prop("disabled", false);
    var carrier = $("#Contrato_id_carrier").val();
    var sign_date = $("#Contrato_sign_date").val();
    var production_date = $("#Contrato_production_date").val();
    var company = $("#Contrato_id_company").val();
    var termino_pago = $("#Contrato_id_termino_pago").val();
    var monetizable = $("#Contrato_id_monetizable").val();
    var dias_disputa = $("#Contrato_id_disputa").val();
    var carrier = $("#Contrato_id_carrier").val();
    var end_date = $("#Contrato_end_date").val();          
                
               $.ajax({ 
                type: "GET",
                url: "Contrato",
                data: "sign_date="+sign_date+"&production_date="+production_date+"&end_date="+end_date+"&id_carrier="+carrier+"&id_company="+company+"&id_termino_pago="+termino_pago+"&id_monetizable="+monetizable+"&id_disputa="+dias_disputa,

                success: function(data) 
                        {
                            alert('voy');
                            alert(data);
                        }});
});

</script>
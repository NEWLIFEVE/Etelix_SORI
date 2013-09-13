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
       <input type="hidden" id="dias_disputa_Oculto"  value="">
       <input type="hidden" id="monetizable_Oculto"  value="">
       <input type="hidden" id="TerminoP_Oculto"  value="">
       <input type="hidden" id="F_Firma_Contrato_Oculto"  value="">
       <input type="hidden" id="F_P_produccion_Oculto"  value="">
       
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
   
                            $("#F_Firma_Contrato_Oculto").val(obj.sign_date);
                            $("#F_P_produccion_Oculto").val(obj.production_date);
                            $("#TerminoP_Oculto").val(obj.termino_pago);
                            $("#monetizable_Oculto").val(obj.monetizable);
                            $("#dias_disputa_Oculto").val(obj.dias_disputa);
                
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
    var diasDisputaOculto = $("#dias_disputa_Oculto").val();
    var F_Firma_Contrato_Oculto = $("#F_Firma_Contrato_Oculto").val();
    var F_P_produccion_Oculto = $("#F_P_produccion_Oculto").val();

    var monetizaOculto = $("#monetizable_Oculto").val();      
    var TPOculto = $("#TerminoP_Oculto").val();  
               
                $.ajax({ 
                type: "GET",
                url: "ContratoConfirma",
                data: "sign_date="+sign_date+"&production_date="+production_date+"&end_date="+end_date+"&id_carrier="+carrier+"&id_company="+company+"&id_termino_pago="+termino_pago+"&id_monetizable="+monetizable+"&id_disputa="+dias_disputa+"&id_M_Oculto="+monetizaOculto+"&id_TP_Oculto="+TPOculto,

                success: function(data) 
                        {
                          var contrato=data.split("|");
                          var carierNames=contrato[0].split(",");
                          var companyName=contrato[1].split(",");
                          var termino_pName=contrato[2].split(",");
                          var monetizableName=contrato[3].split(",");
                          var dias_disputa=contrato[4].split(",");
                          var sign_date=contrato[5].split(",");
                          var production_date=contrato[6].split(",");
                          var end_date=contrato[7].split(",");
                          var monetizaNameO=contrato[8].split(",");
                          var termino_pNameO=contrato[9].split(",");

                          var revisa = $("<div class='cargando'></div><div class='mensaje'><h4>Esta a punto de realizar los siguientes cambios en el Contrato \n\
                                      \n\: <br><b>("+carierNames+" / "+companyName+")</b></h4>\n\<p><h6>Terminos de pago de: "+termino_pNameO+" a "+termino_pName+"<p><p>Monetizable de: "+monetizaNameO+" a "+monetizableName+"<p>Dias de Disputa de: "+diasDisputaOculto+" a "+dias_disputa+"<p>\n\
                                      <p>Fecha de firma de contrato: "+F_Firma_Contrato_Oculto+" a  "+sign_date+"<p>Fecha de Puesta en Produccion: "+F_P_produccion_Oculto+" a "+production_date+"<p> "+end_date+"<p></h6><p><p>Si esta seguro de realizar los cambios, presione Aceptar, de lo contrario Cancelar<p><p><div id='cancelar' class='cancelar'>\n\
                                      <img src='/images/cancelar.png'width='90px' height='50px'/>\n\&nbsp;</div><div id='confirma' class='confirma'><img src='/images/aceptar.png'\n\
                                      width='85px' height='45px'/></div></div>").hide();
                                  $("body").append(revisa);
                                  revisa.fadeIn('fast');
                    
        $('#confirma,#cancelar').on('click', function()
    {
        var tipo=$(this).attr('id');
        if(tipo=="confirma")
          {             
               $.ajax({ 
                type: "GET",
                url: "Contrato",
                data: "sign_date="+sign_date+"&production_date="+production_date+"&end_date="+end_date+"&id_carrier="+carrier+"&id_company="+company+"&id_termino_pago="+termino_pago+"&id_monetizable="+monetizable+"&id_disputa="+dias_disputa,

                success: function(data) 
                        {                          
                         var exito = $('.mensaje').html("<h4>Se realizaron los siguientes cambios en el Contrato \n\
                                      \n\: <br><b>("+carierNames+" / "+companyName+")</b></h4>\n\<p><h6>Terminos de Pago:"+termino_pName+"<p>Monetizable: "+monetizableName+"<p>Dias de disputa:"+dias_disputa+"<p>Fecha de firma de contrato: "+sign_date+"<p>Fecha de puesta en Produccion:"+production_date+"<p>"+end_date+"<p><p>\n\
                                      </h6><p>\n\
                                      <img src='/images/si.png'width='90px' height='50px'/>").hide().fadeIn('fast');
                                setTimeout(function()
                                {
                                    exito.fadeOut('fast');
                                }, 4000);

                                setTimeout(function()
                                {
                                    $('.cargando').fadeOut('fast');
                                }, 4000);
                        }});  
          }
          else
              {
                revisa.fadeOut('fast');  
              }
    }); 
        }});
});

</script>
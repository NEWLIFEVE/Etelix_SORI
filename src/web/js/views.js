$(document).on('ready',function()
{
    $SORI.AJAX.init();
    $SORI.UI.init();
    /**
     *
     */
    var msj=new mensajes();
    /**
	* Deshabilita el boton de "Grabar en base de datos" si todos los archivos de diario estan cargados,
	* ademas de mostrar un mensaje con el nombre de los archivos
	*/
    $('input[value="dia"]').on('click',function()
    {
    	var control=0;
        $("div.diario").fadeIn("slow").css({
            'display':'block'
        });
        $("div.horas").fadeOut("slow");
        $("div.rerate").fadeOut("slow");

      //valida qsi estan cargados todos los diarios
    	var f = new Date();
    	fecha=( f.getFullYear()+ "-" + (f.getMonth() +1) + "-" +f.getDate() );
    	$.ajax({
            type: "POST",
            url: "/balance/disableddaily",
            data: ({fecha:fecha}),
            success: function(data)
            {
                var obj=JSON.parse(data);
              
                if(obj.error=="si")
                {
                	control=1;
				    var html="<p>Ya se cargaron todos los archivos diarios</p>";
		            var estilo={};
		            msj.interna="error";
		            msj.lightbox(html,estilo,2000);
		            $('input[type="file"], input[type="submit"]').filter(function(){return $(this).attr('name')!='grabartemp'}).attr('disabled','disabled');
		        }
            }
        });


        });
        $("div.horas").fadeOut("slow");
        $("div.rerate").fadeOut("slow");
        console.log(msj.acumulador);
        if(msj.acumulador>=2)
        {
        	$("div.horas").fadeIn("slow").css({
                'display':'block'
            });
            $("div.diario").fadeOut("slow");
            $("div.rerate").fadeOut("slow");
            $('input[type="file"], input[type="submit"]').removeAttr('disabled');
        });
        //Muestra mensaje con el nombre de los archivos rerates guardados
        $('input[value="rerate"]').on('click',function()
        {
            $('input[type="file"], input[type="submit"]').removeAttr('disabled');
        }
    });
    //Muestra mensaje con el nombre de los archivos por hora guardados en el dia
    $('input[value="hora"]').on('click',function()
    {
        $("div.horas").fadeIn("slow").css({
            'display':'block'
        });
        $("div.diario").fadeOut("slow");
        $("div.rerate").fadeOut("slow");
        $('input[type="file"], input[type="submit"]').removeAttr('disabled');
    });
    //Muestra mensaje con el nombre de los archivos rerates guardados
    $('input[value="rerate"]').on('click',function()
    {
        $("div.rerate").fadeIn("slow").css({
            'display':'block'
        });
        $("div.diario").fadeOut("slow");
        $("div.horas").fadeOut('slow');
        $('input[type="file"], input[type="submit"]').removeAttr('disabled');
    });
    valForm(msj);
});

/**
 *
 */
function valForm(objeto)
{
    $('input[name="grabar"],input[name="grabartemp"]').on('click',function(e)
    {
    	
        e.preventDefault();
        if($("input:checked").val()==undefined)
        {
            var html="<p>Debe seleccionar una opcion</p>";
            var estilo={};
            objeto.interna="error";
            objeto.lightbox(html,estilo,2000);
        }
        else
        {
        	var html="<p>Este proceso es irreversible </br> ¿Esta seguro de los Archivos a Cargar?</p><button name='aceptar'><b>Aceptar</b></button><button name='cancelar'>Cancelar</button>";
            var estilo={};
            objeto.interna="confirm";
            objeto.lightbox(html,estilo);
            $('button').on('click',function()
            {
                if($(this).attr('name')=="aceptar")
                {
                    if($('div.'+objeto.interna).remove())
                    {
                        $('div.transparente').fadeOut('slow');
                        $('div.transparente2').fadeIn('slow');
                        $('form[name="monto"]').submit();
                    }
                }
                else
                {
                    objeto.elimina();
                }
            });
            
        }
    });
}
/**
 *
 */
$("#botAsignar").on("click",function asignadosAnoasignados()
{
    $("#carriers select option").prop("selected",true);
    var manager=$("#CarrierManagers_id_managers").val(),
    asignados=$("#select_left").val(),
    noasignados=$("#select_right").val();

    if(manager=="")
    {
         $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>Debe seleccionar un manager </h3>","aguanta.png","1500","width:40px; height:90px;"); 
    }
    else
    {
        $.ajax({
            type: "GET",
            url: "BuscaNombres",
            data: "asignados="+asignados+"&noasignados="+noasignados+"&manager="+manager,
            success: function(data)
            {
                var obj=JSON.parse(data);
                console.log(obj.asigNames.length);
                if(obj.asigNames.length=="0" && obj.noasigNames.length=="0")
                {
                    $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>No hay carriers preselccionados <br> para asignar o desasignar</h3>","aguanta.png","1500","width:40px; height:90px;"); 
                }
                else
                {
                    $SORI.UI.msj_confirm("Esta a punto de realizar los siguientes cambios en la Distribucion Comercial para el manager: <br><b>"+obj.managerName+"</b></h4><p><h6>"+$SORI.UI.resultadoContrato(obj.asigNames,"","Asignarle: ","")+"<p>"+obj.asigNames+"</h6><p><h6>"+$SORI.UI.resultadoContrato(obj.noasigNames,"","Dsasignarle: ","")+"<p>"+obj.noasigNames+"</h6>");
                }
                $('#confirma,#cancelar').on('click', function()
                {
                    var tipo=$(this).attr('id');
                    if(tipo=="confirma"&& manager!="")
                    {
                        $.ajax({
                            type: "GET",
                            url: "UpdateDistComercial",
                            data: "asignados="+asignados+"&noasignados="+noasignados+"&manager="+manager,
                            success: function(data)
                            {
                                var obj=JSON.parse(data);
                                $SORI.UI.msj_change("<h5>Al manager <b>" + obj.managerName + "</b><br>" + $SORI.UI.resultadoContrato(obj.asigNames,"","Le fue asignado: ","") + "<br><b>" + obj.asigNames + "</b></h5><p><h5>" + $SORI.UI.resultadoContrato(obj.noasigNames,"","Desasignado: ","")  + "<br><b>" + obj.noasigNames + "</b></h5>","si.png","2000","width:95px; height:95px;"); 
                            }
                        });
                    }
                    else
                    {
                        $('.cargando,.mensaje').fadeOut('fast');
                    }
                });
            }
        });
    }
    $("#carriers select option").prop("selected",false);
});
   /**
    * asigna y desasigna
    */       
$("#options_right, #options_left").on( "click",function ()
{
    switch ($(this).attr('id'))
    {
        case "options_right":
            $('#select_left :selected').each(function(i,selected)
            {
                $("#select_left option[value='"+$(selected).val()+"']").remove();
                $('#select_right').append("<option value='"+$(selected).val()+"'>"+$(selected).text()+"</option>");
            });
            break;
        case "options_left":
            $('#select_right :selected').each(function(i,selected)
            {
                $("#select_right option[value='"+$(selected).val()+"']").remove();
                $('#select_left').append("<option value='"+$(selected).val()+"'>"+$(selected).text()+"</option>");
            });
            break;
    }
});
/**
 * 
 */
$("#CarrierManagers_id_managers").change(function()
{
    $.ajax({
        type:'POST',
        url: "DynamicNoAsignados",
        success: function(data)
        {
            $("#select_right").empty();
            $("#select_right").append(""+data+"");
        }
    });
});
//fin de cambio en dist comercial
//INICIO DE CONTRATO//////////////
$('#Contrato_id_carrier').change(function()
{ 
    $("#Contrato_id_company,#Contrato_sign_date,#Contrato_production_date,#Contrato_id_termino_pago,#Contrato_start_date_TP_customer,#Contrato_id_monetizable,#Contrato_up,#Contrato_status,#Contrato_bank_fee").val('');
    $("#Contrato_id_disputa,#F_Firma_Contrato_Oculto,#F_P_produccion_Oculto,#TerminoP_Oculto,#dias_disputa_Oculto,#dia_ini_fact,#divide_fact,#Contrato_id_fact_period,#Contrato_idTerminoPagoSupplier, #Contrato_start_date_TP_supplier, #start_date_TP_cus_Oculto, #start_date_TP_sup_Oculto").val('');
    $(".hManagerA,.hCarrierA").empty();
    $(".divide_fact,.periodo_fact,.dia_ini_fact").hide("slow");
    $(".formularioContrato").fadeOut("fast");
    $SORI.UI.formChange('#Contrato_id_carrier');
});

$("#Contrato_id_termino_pago,#Contrato_id_termino_pago_supplier,#Contrato_id_fact_period").change(function()
{
    $("#TerminoPViews").val($("#Contrato_id_termino_pago  option:selected").html());
    if($(this).attr('id')!="Contrato_id_fact_period")
    {       
        if($(this).attr('id')=="Contrato_id_termino_pago" && $("#Contrato_id_termino_pago_supplier").val()=="")
        {
            $("#Contrato_id_termino_pago_supplier").val($("#Contrato_id_termino_pago").val());
            $SORI.UI.resuelveTP($("#Contrato_id_termino_pago_supplier").val()); 
            $(".startDateTpSupp").hide("fast");$("#Contrato_start_date_TP_supplier").val("");
        }
        if($(this).attr('id')=="Contrato_id_termino_pago" && $("#Contrato_id_termino_pago_customer").val()!="")
        {
            $(".startDateTpCust").hide("fast");$("#Contrato_start_date_TP_customer").val("");
        }
        if($(this).attr('id')=="Contrato_id_termino_pago_supplier")
        {
            $("#Contrato_id_fact_period").val("");
            $SORI.UI.resuelveTP($("#Contrato_id_termino_pago_supplier").val()); 
            $(".startDateTpSupp").hide("fast");$("#Contrato_start_date_TP_supplier").val("");
        }       
    }
    else
    {
        $SORI.UI.resuelvePeriodo($(this).val());
    }
});

$("input#Contrato_start_date_TP_customer, input#Contrato_start_date_TP_supplier").focusout(function() 
{  
     switch ($(this).attr("id"))
     {
       case "Contrato_start_date_TP_customer":
            setTimeout(function() { $SORI.AJAX.send("GET","/Contrato/UpdateStartDateCTP",$("#contrato-form").serialize(), "date"); }, 2000);
           break;
       case "Contrato_start_date_TP_supplier":
            setTimeout(function() { $SORI.AJAX.send("GET","/Contrato/UpdateStartDateCTPS",$("#contrato-form").serialize(), "date"); }, 2000);
           break;
     }
});

$('#paymentTermnS, #paymentTermnC').on("click", function()
{
    $(".emergingBackground").remove();
    var msj=$("<div class='emergingBackground'></div>").hide(); 
    $("body").append(msj);
    msj.slideDown('slow');
    if($(this).attr("id")=="paymentTermnC")
    {
          $(".formPaymentTermnsCustomer").slideDown("slow");
          $SORI.AJAX.send("GET","/ContratoTerminoPago/PaymentTermHistory",$("#Contrato_id_carrier").serialize(), false); 
          if($("#Contrato_start_date_TP_customer").val()=="")$(".startDateTpCust").hide("fast");
          else  $(".startDateTpCust").show("fast"); 
    }
    else
    {
        $(".formPaymentTermnsSupplier").slideDown("slow");
        $SORI.AJAX.send("GET","/ContratoTerminoPagoSupplier/PaymentTermHistory",$("#Contrato_id_carrier").serialize(), true); 
        if($("#Contrato_start_date_TP_supplier").val()=="") $(".startDateTpSupp").hide("fast");
        else $(".startDateTpSupp").show("fast");
    }
    $(".emergingBackground").click(function(){  $(".formPaymentTermnsSupplier, .formPaymentTermnsCustomer,.emergingBackground").fadeOut("slow");});
});

$('#botAsignarContrato').click('on',function(e)
{
    e.preventDefault();
    $("#Contrato_id_company,#Contrato_end_date,#Contrato_sign_date").prop("disabled", false);
    var str = $('#contrato-form').serialize(),
    valid_input=$SORI.UI.seleccionaCampos('general'),
    valid_tp_supp=$SORI.UI.seleccionaCampos('tp_supplier'); 
      
    if(valid_input==1 && valid_tp_supp==1)
    {
        $.ajax({
            type: "GET",
            url: "ContratoConfirma",
            data: str,
            success: function(data) 
            {
                var obj=JSON.parse(data);
                if(obj.termino_pNameO == "" && obj.monetizableNameO == "")
                {
                    var guardoEdito=" Se guardo con exito el Contrato";
                    var finalidad="<h4>Esta a punto de crear un nuevo Contrato: <br><b>( "+obj.carrierName+" / "+obj.companyName+" )</b></h4><h6>Con las siguientes condiciones comerciales:</h6>";
                }
                else
                {   
                    guardoEdito=" Todos los cambios fueron efectuados con exito en el Contrato"; 
                    finalidad="<h4>Esta a punto de realizar los siguientes cambios en el Contrato :<br><b>("+obj.carrierName+" / "+obj.companyName+")</b></h4>";
                }  
                    var cambio_bank_fee="";
                if(obj.bank_feeName != obj.bank_feeNameO)
                {
                    cambio_bank_fee="<p class='bank_fee_note'><b>Nota:</b> Esta a punto de definir que <b>"+obj.companyName+"</b> "+obj.bank_feeName+" asumira el <b>bank fee</b> para este carrier, recuerde que esta opcion se aplicara para los demas carrier pertenecientes al mismo grupo</p>";
                }
                if($("#Contrato_end_date").val()!="")
                {
                    var advertencia="<h4>Esta a punto de finalizar el Contrato<br><b>("+obj.carrierName+" / "+obj.companyName+")</b></h4>";
                }
                else
                {
                    advertencia=""+finalidad+"<h6><div><table class='table_contrato'>\n\
                                    <tr class='tr_contrato'>\n\
                                      <td>"+$SORI.UI.resultadoContrato($("#F_Firma_Contrato_Oculto").val(),$("#Contrato_sign_date").val(),"Fecha de firma de contrato de: "+$("#F_Firma_Contrato_Oculto").val()+" a ","Fecha de firma de contrato: ")+" </td><td class='sign_date td_basic_contr'>"+$("#Contrato_sign_date").val()+"</td> \n\
                                      <td>"+$SORI.UI.resultadoContrato($("#F_P_produccion_Oculto").val(),$("#Contrato_production_date").val(),"Fecha de Puesta en Produccion de: "+$("#F_P_produccion_Oculto").val()+" a ","Fecha de Puesta en Produccion: ")+" </td><td class='production_date td_basic_contr'>"+$("#Contrato_production_date").val()+"</td> \n\
                                    </tr>\n\
                                    <tr class='tr_contrato'>\n\
                                      <td>"+$SORI.UI.resultadoContrato(obj.termino_pNameO,obj.termino_pName,"Terminos de pago client de: "+obj.termino_pNameO+" a ","Terminos de pago client: ")+" </td><td class='termino_pName td_basic_contr'>"+obj.termino_pName+"</td> \n\
                                      <td>"+$SORI.UI.resultadoContrato(obj.termino_p_supp_NameO,obj.termino_p_supp_Name,"Terminos de pago prov de: "+obj.termino_p_supp_NameO+" a ","Terminos de pago prov: ")+" </td><td class='termino_p_supp_Name td_basic_contr'>"+obj.termino_p_supp_Name+"</td> \n\
                                    </tr>\n\
                                    <tr class='tr_contrato'>\n\
                                      <td>"+$SORI.UI.resultadoContrato(obj.fact_period_NameO,$SORI.UI.defineNull(obj.fact_period_Name,'No Aplica'),"Tipo de Ciclo de Fact de: "+obj.fact_period_NameO+" a ","Tipo de Ciclo de Fact: ")+" </td><td class='fact_period_Name td_basic_contr'>"+$SORI.UI.defineNull(obj.fact_period_Name,'No Aplica')+"</td> \n\
                                      <td>"+$SORI.UI.resultadoContrato(obj.dia_ini_fact_NameO,obj.dia_ini_fact_Name,"Dia de Inicio de Ciclo de: "+obj.dia_ini_fact_NameO+" a ","Dia de Inicio de Ciclo: ")+" </td><td class='dia_ini_fact_Name td_basic_contr'>"+$SORI.UI.defineNull(obj.dia_ini_fact_Name,'No Aplica')+"</td> \n\
                                    </tr>\n\
                                    <tr class='tr_contrato'>\n\
                                      <td>"+$SORI.UI.resultadoContrato($("#divide_fact_Oculto").val(),$("#divide_fact").val(),"Divide Fact por Mes de: "+obj.divide_fact_NameO+" a ","Divide Fact por Mes: ")+" </td><td class='divide_fact_Name td_basic_contr'>"+$SORI.UI.defineNull(obj.divide_fact_Name,'No Aplica')+"</td> \n\
                                      <td>"+$SORI.UI.resultadoContrato(obj.monetizableNameO,obj.monetizableName,"Monetizable de: "+obj.monetizableNameO+" a ","Monetizable: ")+" </td><td class='monetizableName td_basic_contr'>"+obj.monetizableName+"</td> \n\
                                    </tr>\n\
                                    <tr class='tr_contrato'>\n\
                                      <td>"+$SORI.UI.resultadoContrato($("#dias_disputa_Oculto").val(),$("#Contrato_id_disputa").val(),"Dias max para disputar de: "+$("#dias_disputa_Oculto").val()+" a ","Dias max para disputar: ")+" </td><td class='dias_disputa td_basic_contr'>"+$("#Contrato_id_disputa").val()+"</td> \n\
                                      <td>"+$SORI.UI.resultadoContrato($("#dias_disputa_solved_Oculto").val(),$("#Contrato_id_disputa_solved").val(),"Dias para solventar disputas de: "+$("#dias_disputa_solved_Oculto").val()+" a ","Dias para solventar disputas: ")+" </td><td class='dias_disputa_solved td_basic_contr'>"+$("#Contrato_id_disputa_solved").val()+"</td> \n\
                                    </tr>\n\
                                    <tr class='tr_contrato'>\n\
                                      <td>"+$SORI.UI.resultadoContrato($("#credito_Oculto").val(),$("#Contrato_id_limite_credito").val(),"Limite de Credito de: "+$("#credito_Oculto").val()+" a ","Limite de Credito: ")+" </td><td class='credito td_basic_contr'>"+$("#Contrato_id_limite_credito").val()+"</td> \n\
                                      <td>"+$SORI.UI.resultadoContrato($("#compra_Oculto").val(),$("#Contrato_id_limite_compra").val(),"Limite de Compra de: "+$("#compra_Oculto").val()+" a ","Limite de Compra: ")+" </td><td class='compra td_basic_contr'>"+$("#Contrato_id_limite_compra").val()+"</td> \n\
                                    </tr>\n\
                                    <tr class='tr_contrato'>\n\
                                      <td>"+$SORI.UI.resultadoContrato( obj.Contrato_upConfirmaO, obj.Contrato_upConfirma,"Unidad de producción de: "+ obj.Contrato_upConfirmaO+" a ","Unidad de producción: ")+" </td><td class='Contrato_upC td_basic_contr'>"+obj.Contrato_upConfirma+"</td> \n\
                                      <td>"+$SORI.UI.resultadoContrato(obj.Contrato_statusConfirmaO,obj.Contrato_statusConfirma,"Unidad de producción de: "+obj.Contrato_statusConfirmaO+" a ","Unidad de producción: ")+" </td><td class='Contrato_StatusC td_basic_contr'>"+obj.Contrato_statusConfirma+"</td> \n\
                                    </tr>\n\
                                    <tr class='tr_contrato'>\n\
                                      <td>"+$SORI.UI.resultadoContrato(obj.bank_feeNameO,obj.bank_feeName,"Se Asume Bank Fee de: "+obj.bank_feeNameO+" a ","Se Asume Bank Fee: ")+" </td><td class='bank_feeName td_basic_contr'>"+obj.bank_feeName+"</td> \n\
                                      <td></td><td></td>\n\
                                    </tr>\n\
                                   </table></div></h6>"+cambio_bank_fee+"<p>";
                }
                $SORI.UI.msj_confirm(advertencia);
                $('.mensaje').css('width','654px').css('margin-left','25%');$('.cancelar').css('margin-left','236px');$('.confirma').css('margin-left','334px');
                $SORI.UI.coloursIfChange();
                if(cambio_bank_fee!="") $SORI.UI.changeCss($('.mensaje'),'top','6%!important');
                
                $('#confirma,#cancelar').on('click',function()
                {
                    var tipo=$(this).attr('id');
                    if(tipo=="confirma")
                    {  
                        $.ajax({
                            type: "GET",
                            url: "Contrato",
                            data: str,
                            success: function(data) 
                            {  
                                $SORI.UI.formChange('#Contrato_id_carrier');
                                console.log(data);$('.mensaje').css('width','490px').css('margin-left','30%');
                                if($("#Contrato_end_date").val()!="") $SORI.UI.msj_change("<h4>El Contrato: <br><b>("+obj.carrierName+" / "+obj.companyName+")</b></h4><h6><p>Fue Finalizado con exito en la fecha: "+$("#Contrato_end_date").val()+"</h6>","si.png","1000","width:90px; height:90px;");
                                else $SORI.UI.msj_change("<h4>"+guardoEdito+": <br><b>("+obj.carrierName+" / "+obj.companyName+")</b></h4>","si.png","1500","width:90px; height:90px;");

                                $("#monetizable_Oculto").val($("#Contrato_id_monetizable").val());$("#TerminoP_Oculto").val($("#Contrato_id_termino_pago").val()); $("#bank_feeOculto").val($("#Contrato_bank_fee").val());
                            }
                        });
                        $("#Contrato_id_company").prop("disabled", true);
                        $("#Contrato_end_date,#Contrato_sign_date").prop("disabled", false);
                    }
                    else
                    {
                        $('.cargando,.mensaje').fadeOut('slow');  
                    }
                });
            }
        });
    }
});
//FIIN contrato, add and update
/**
 * FIIN administra las zonas geograficas con los destinos externos e internos
 */
$(".botAsignarDestination").on( "click",function DestinosAsignadosNoasignados()
{
    $("#carriers select option").prop("selected",true);
    var GeographicZone = $("#GeographicZone_id").val(), asignados = $("#select_right").val(), noasignados = $("#select_left").val(),  destinos = $('#GeographicZone_id_destination').val();

    if(GeographicZone=="")
    {
         $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>Debe seleccionar un destino y una zona geografica </h3>","aguanta.png","1500","width:40px; height:90px;"); 
    }
    else
    {
        if(destinos==1)
        {
            var tipoDestino="Destinos Externos",
            elijeDestinationBuscaName="../Destination/BuscaNombresDes",
            destination_update="../Destination/UpdateZonaDestination";
        }
        if(destinos==2)
        {
            tipoDestino="Destinos Internos";
            elijeDestinationBuscaName="../DestinationInt/BuscaNombresDesInt";
            destination_update="../DestinationInt/UpdateZonaDestinationInt";
        }
        $.ajax({
            type: "GET",
            url: elijeDestinationBuscaName,
            data: "asignados="+asignados+"&noasignados="+noasignados+"&GeographicZone="+GeographicZone,
            success: function(data)
            {
                var obj = JSON.parse(data);
               
                if(obj.asigNames<1 && obj.noasigNames<1)
                {
                    $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>No hay datos que cambiar</h3>","aguanta.png","1500","width:40px; height:90px;"); 
                }
                else
                {
                    $SORI.UI.msj_confirm("<h4>Esta a punto de actualizar "+tipoDestino+" la Zona Geográfica: <br><b> "+obj.GeographicZoneName+"</h4><h4></b>"+$SORI.UI.resultadoContrato(obj.asigNames,"","Asignarle: ","")+"</h4><p><h6><p>"+obj.asigNames+"</h6><p><br><h4>"+$SORI.UI.resultadoContrato(obj.noasigNames,"","Dsasignarle: ","")+"</h4><p><h6><p><p>"+obj.noasigNames+"</h6>");
                    $('#confirma,#cancelar').on('click',function()
                    {
                        var tipo=$(this).attr('id');
                        if(tipo=="confirma")
                        {   
                            $SORI.UI.msj_cargando("<h2>Espere un momento por favor</h2>","image_464753.gif"); 
                            $.ajax({
                                type: "GET",
                                url: destination_update,
                                data: "asignados="+asignados+"&noasignados="+noasignados+"&GeographicZone="+GeographicZone,
                                success: function(data)
                                {
                                     $SORI.UI.msj_change("<h4>Se han actualizado los "+tipoDestino+" con la Zona Geográfica: <br><b> "+obj.GeographicZoneName+"</h4><h4></b>"+$SORI.UI.resultadoContrato(obj.asigNames,"","Asignarle: ","")+"</h4>\n\<p> <h6><p>"+obj.asigNames+"</h6><p><br><h4>"+$SORI.UI.resultadoContrato(obj.noasigNames,"","Dsasignarle: ","")+"</h4><p><h6>"+obj.noasigNames+"</h6>","si.png","3000","width:95px; height:95px;");      
                                }
                            });
                        }
                        else
                        {
                            $(".cargando,.mensaje").fadeOut('fast');
                        }
                    });
                }
            }
        });
        $("#carriers select option").prop("selected",false);
    }
});

/**
 * carga los datos en los select  multiple zona geografica y destination... 
 */
$('#GeographicZone_id_destination').change(function()
{
    if($("#GeographicZone_id").val()!="")
    {
        $.ajax({
            type: "POST",
            url: "DynamicAsignados",
            data: 'GeographicZone='+$("#GeographicZone_id").val()+'&destinos='+$('#GeographicZone_id_destination').val(),
            success: function(data)
            {
                $('#select_right').empty().append(data);
            }
        });
    }
});
$('#GeographicZone_id').change(function()
{
    var GeographicZone=$("#GeographicZone_id").val(), destinos=$('#GeographicZone_id_destination').val();
    if(destinos<1)
    {
        $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>Debe seleccionar un destino antes de seleccionar la zona geográfica</h3>","aguanta.png","1000","width:40px; height:90px;"); 
    }
    else
    {
        $.ajax({
            type: "POST",
            url: "DynamicAsignados",
            data:   'GeographicZone='+GeographicZone+'&destinos='+destinos,
            success: function(data)
            {
                $('#select_right').empty().append(data);   
            }
        });
    }
});
/**
 *   fin de adm de zonas y destinos
 */

/**
 * admin de documentos contables
 */
$('#AccountingDocumentTemp_id_type_accounting_document').change(function()
{   
    $('#AccountingDocumentTemp_amount').removeAttr('readonly');
    $SORI.UI.changeCss('#AccountingDocumentTemp_id_accounting_document','color','#777');
    $SORI.UI.elijeOpciones($('#AccountingDocumentTemp_id_type_accounting_document').val());   
    $('div.instruccion').slideUp('fast');
    $('div.valoresDocumento').fadeIn('slow');
});
$('#AccountingDocumentTemp_id_carrier').change(function()
{  
    var tipoDocument= $('#AccountingDocumentTemp_id_type_accounting_document').val();
    if(tipoDocument=="7" || tipoDocument=="8")
    {
       $('.fechaDeEmision').fadeOut("fast");$('.listaDisputas').remove();
       $('.fechaIniFact,.fechaFinFact').fadeIn('fast');
       $SORI.UI.changeCss('.numFactura','width','51%');
       $('#AccountingDocumentTemp_amount').removeAttr('readonly');
    }
});
$('#AccountingDocumentTemp_carrier_groups').change(function()
{  
    $.ajax({
            type: "GET",
            url: "../Contrato/Comprueba_BankFee",
            data: 'id_group='+$(this).val(),
            success: function(data) 
            {
                if($('#AccountingDocumentTemp_id_type_accounting_document').val()=="4" || $('#AccountingDocumentTemp_id_type_accounting_document').val()=="3")
                {
                    if(data==1) $(".bank_fee").show("slow");
                    else $(".bank_fee").hide("slow");
                }
            }
    });
});

$('div.hacerUnaNota, div.quitaNota').click('on',function()
{
    $('div.hacerUnaNota, div.quitaNota, div.contratoFormTextArea').toggle('fast');
});
/*GUARDAR EN LISTA TEMPORAL*/
$('#botAgregarDatosContable').click('on',function(e)
{
    e.preventDefault();
    var str = $('#accounting-document-temp-form').serialize();
    console.dir(str);
    var respuesta=$SORI.UI.seleccionaCampos($('#AccountingDocumentTemp_id_type_accounting_document').val()); 
    if(respuesta==1)
    {
        $.ajax({
            type: "GET",
            url: "GuardarDoc_ContTemp",
            data: str,
            success: function(data) 
            {
                obj = JSON.parse(data);
                if((obj.valid==1))
                {
                    $SORI.UTILS.updateMontoAprobadoDisp();
                    $SORI.UI.llenarTabla(obj);
                    $SORI.UI.init();
                    $SORI.UI.emptyFields();
                }
                else if((obj.valid==0))
                {
                    $SORI.UI.MensajeYaExiste(obj);
                }
                else
                {
                    $SORI.UI.sesionCerrada();
                }
            }          
        }); 
    }
}); 
$('#botAgregarDatosContableFinal').click('on',function(e)
{
    e.preventDefault();
    $SORI.UI.msj_confirm("<h4>Esta a punto de guardar todos los documentos contables de forma definitiva</h4>");          
    $('#confirma,#cancelar').on('click',function()
    {
        var tipo=$(this).attr('id');
        if(tipo=="confirma")
        {
            $.ajax({ 
                type: "GET",
                url: "guardarListaFinal",
                success: function(data) 
                {
                    console.log(data);
                    if(data=="" || data==null)
                    {
                        $SORI.UI.sesionCerrada();
                    }
                    else
                    {
                        $('.tablaVistDocTemporales, #botAgregarDatosContableFinal, .Label_F_Env, .Label_F_Rec, .LabelPagos, .LabelCobros, .Label_DispRec, .Label_DispEnv,.Label_NotCredEnv,.Label_NotCredRec,.botonesParaExportar').fadeOut('fast');
                        $('.vistaTemp').remove();
                        $SORI.UI.msj_change("<h4>Se almacenaron <b> "+data+"</b>  documentos contables de forma definitiva</h4>","si.png","1000","width:95px; height:95px;"); 
                    }
                }  
            });
        }
        else
        {
            $(".cargando, .mensaje").fadeOut('fast');
        }
    });
});
      
$('#botConfirmarDatosContableFinal').click('on',function()
{    
    var dato=$('input[type="checkbox"]').filter(function()
    {
        return $(this).is(':checked');
    });
    if(dato.length<=0)
    {
        $SORI.UI.msj_cargando("","");
        $SORI.UI.msj_change("<h3>No ha seleccionado ninguna factura para confirmar</h3>","aguanta.png","1000","width:40px; height:90px;"); 
    }
    else
    {
        $SORI.UI.msj_confirm("<h4>Esta a punto de confirmar las siguientes facturas enviadas</h4>");  
        $('#confirma,#cancelar').on('click',function()
        {
            var tipo=$(this).attr('id');
            if(tipo=="confirma")
            {             
                var array=$("input[type=checkbox]:checked").each(function()
                {
                    var id=($(this).val()),
                    paraBorrar=$('#'+id);
                    
                    $.ajax({ 
                        type: "GET",
                        url: "../AccountingDocument/Confirmar/"+id,
                        success: function(data) 
                        {
                            paraBorrar.empty(); 
                        }
                    });
                });
                var cuantos=array.length,
                id=($("input[type=checkbox]:checked").val());
                if(cuantos>0)
                {
                    if(id=='on')
                    {
                        cuantos=array.length-1;
                    }
                    $SORI.UI.msj_change("<h4>Se confirmaron <b>"+cuantos+"</b> facturas enviadas</h4>","si.png","2000","width:95px; height:95px;");   
                }
            }
            else
            {
                $(".cargando, .mensaje").fadeOut('slow'); 
            }
        });   
    }      
});
/**Vista Uploads*/
$(function() 
{
    $(".datepicker").datepicker();
    $(".datepicker").datepicker( "option", "dateFormat", "mm-dd-yy" );
    $(".datepicker").datepicker( "option", "showAnim", "drop" );
});
$(function($)
{
    $.datepicker.regional['es']={
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 0,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);
});

//**
//modulo de colores por zona geografica
//*
$('#GeographicZone_acciones, input#color_zona, select#GeographicZone_name_zona').change(function()
{
    switch($(this).attr('id'))
    {
        case "GeographicZone_acciones":
            $('.acciones').html('Acciones');
            if($(this).val()==1)
            {
                $('div.valoresDocumento, input#GeographicZone_name_zona').slideDown('slow');
                $('select#GeographicZone_name_zona').hide('slow');
            }
            if($(this).val()==2)
            {
                $('div.valoresDocumento, select#GeographicZone_name_zona').slideDown('slow');
                $('input#GeographicZone_name_zona').hide('slow');
            } 
            break;
        case "color_zona":
            $("#color_zona_hidden").val($(this).val());
            $("#color_zona").css("background",$(this).val());
            break;
        case "GeographicZone_name_zona":
            $.ajax({
                type: "GET",
                url: "buscaColor",
                data: "&id_zonaSelect="+$( "select#GeographicZone_name_zona" ).val(),

                success: function(data) 
                {
                    $( "input#color_zona_hidden" ).val( data );
                    var color=("#"+data+"");
                    $( "input#color_zona" ).css( "background-color",color ); 
                }
            });
            break;
        }
});
$('.botGuardarZonaColor').click('on',function(e)
{
    e.preventDefault();
    var acciones=$('#GeographicZone_acciones').val(), color=$("input#color_zona_hidden").val();
    if(acciones=='' || color=='')
    {  
        $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>Faltan datos por agregar</h3>","aguanta.png","1000","width:40px; height:90px;"); 
    }
    else
    {
        if(acciones==1)
        {
            var Action="GuardarZoneColor";
        }
        if(acciones==2)
        {
            Action="UpdateZoneColor";
        }
        $.ajax({
            type: "GET",
            url: Action,
            data: "name_zona="+$('input#GeographicZone_name_zona').val()+"&color_zona="+color.replace('#','')+"&name_zonaSelect="+$('select#GeographicZone_name_zona').val(),
            success: function(data) 
            {
                obj = JSON.parse(data);
                $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>La zona geografica <p><b>"+obj.name_zonaG+"</b></h3><p>Fue guardada con exito y se le asigno el color<div style='background-color: #"+obj.color_zonaG+";width:25%;margin-left: 36%;'>"+obj.color_zonaG+"</div>","si.png","3000","width:95px; height:95px;"); 
                $("select#GeographicZone_acciones,input#GeographicZone_name_zona,select#GeographicZone_name_zona,input#color_zona_hidden").val('');
                $( "input#color_zona" ).css( "background-color","white" );
            }
        });
    }
});
/**
 * fin modulo de colores por zona geografica
 */

/**
 * modulo de grupo carriers
 */

$('div.newGroup, div.cancelarnewGroup').click('on',function()
{
    switch($(this).attr('class'))
    {
        case "newGroup":
            $(this).hide('fast');
            $('div.cancelarnewGroup').show('fast');
            $('#Carrier_id').hide('fast'); 
            $('#Carrier_new_groups').show('fast');
            $('#Carrier_id').val('');
            $('#select_left').empty();
            $('.group_title').html("NUEVO GRUPO");
            $('.labelGrupos').html("Nuevo Grupo");
            $('.NoteGrupos').show("fast");
            break;
        case "cancelarnewGroup":
            $(this).hide('fast');
            $('div.newGroup').show('fast');
            $('#Carrier_id').show('fast'); 
            $('#Carrier_new_groups').hide('fast');
            $('#Carrier_new_groups').val('');
            $('.group_title').html("ADMIN GRUPO");
            $('.labelGrupos').html("Grupos");
            $('.NoteGrupos').hide("fast");
            break;
    } 
});
$('#Carrier_id').change(function()
{
    var grupo=$('#Carrier_id').val();
    $.ajax({
        type: "POST",
        url: "../CarrierGroups/DynamicCarrierAsignados",
        data:   'grupo='+grupo,
        success: function(data)
        {
            $('#select_left').empty().append(data);   
        }
    });
});

$(".AsignarCarrierGroup").on( "click",function(e)
{
    e.preventDefault();
    $("#carriers select option").prop("selected",true);
    if($('#Carrier_id').val()<1 && $("#Carrier_new_groups").val()=="")
    {
        $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>No ha seleccionado ningun grupo</h3>","aguanta.png","1000","width:40px; height:90px;"); 
        $("#carriers select option").prop("selected",false);
    }
    else 
    {
        $.ajax({
            type: "GET",
            url: "../Carrier/BuscaNombres",
            data: "asignados="+$('#select_left').val()+"&noasignados="+$('#select_right').val()+"&grupo="+$('#Carrier_id').val()+"&new_grupo="+$("#Carrier_new_groups").val(),
            success: function(data)
            {
                obj = JSON.parse(data);
                if(obj.asignados<="1" && obj.noasignados<="1")
                {
                    $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>No hay carriers preselccionados <br> para asignar o  desasignar</h3>","aguanta.png","2000","width:40px; height:90px;"); 
                    $("#carriers select option").prop("selected",false);
                }
                else
                {
                    $SORI.UI.msj_confirm(""+$SORI.UI.resultadoContrato($("#Carrier_new_groups").val(),"","<h4>Esta a punto de crear el nuevo grupo<br><b>"+obj.grupo+"</b></h4>", "<h4>Esta a punto de realizar los siguientes cambios en el grupo<br><b>"+obj.grupo+"</b></h4>")+"<p><h6>"+$SORI.UI.resultadoContrato(obj.asignados,"","Asignarle: ","")+"<p>"+obj.asignados+"</h6><h6>"+$SORI.UI.resultadoContrato(obj.noasignados,"","Dsasignarle: ","")+"<p>"+obj.noasignados+"</h6>");  
                    $('#confirma,#cancelar').on('click', function()
                    {
                        var tipo=$(this).attr('id');
                        if(tipo=="confirma")
                        {
                            $.ajax({
                                type: "GET",
                                url: "../Carrier/SaveCarrierGroup",
                                data:   '&grupo='+$('#Carrier_id').val()+'&asignados='+$('#select_left').val()+'&noasignados='+$('#select_right').val()+"&new_grupo="+$("#Carrier_new_groups").val(),
                                success: function(data)
                                {
                                    $SORI.UI.msj_change("<h2>El grupo <b>" + obj.grupo + "</b><br><br><p>Fue "+$SORI.UI.resultadoContrato($("#Carrier_new_groups").val(),"","Creado", "Modificado")+" con exito</h2>","si.png","4000","width:95px; height:95px;"); 
                                    $('#Carrier_id').val('');
                                    $("#carriers select option").prop("selected",false);
                                    $SORI.AJAX.UpdateIdCarrier();
//                                    if(obj.newGroup!=null)  $("#Carrier_id").append("<option value="+obj.newGroup+">"+$("#Carrier_new_groups").val().toUpperCase()+"</option>");
                                }
                            });
                        }
                        else
                        {
                            $(".cargando,.mensaje").fadeOut();
                        }
                    });
                }
            }
        });
    }
});
/**
 * fin modulo de grupo carriers
 */
/**
 * agregar managers
 */
$(".G_ManagerNuevo").on( "click",function(e)
{
    e.preventDefault();
    $.ajax({
        type: "GET",
        url: "../Managers/GuardarManager",
        data:   'name='+$('#Managers_name').val()+'&lastname='+$('#Managers_lastname').val()+'&record_date='+$('#Managers_record_date').val()+'&address='+$('#Managers_address').val()+'&position='+$('#Managers_position').val(),
        success: function(data)
        {
            var obj = JSON.parse(data);
            $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>El Manager <p><b>"+obj.nameSave+" "+obj.lastnameSave+"</b></h3><p>Fue almacenado con exito con los siguientes datos:<p><h5>Fecha de Ingreso: <b>"+obj.record_dateSave+"</b><p>Posición: <b>"+obj.positionSave+"</b><p>Dirección: <b>"+obj.addressSave+"</b></h5>","si.png","2000","width:95px; height:95px;"); 
            $('#Managers_name,#Managers_lastname,#Managers_record_date,#Managers_position,#Managers_address').val(''); 
        }
    });
});
/**
 * fin agregar managers
 */

/**
 * check marcar 
 */
function marcar(source)
{
    checkboxes = document.getElementsByTagName('input'); //obtenemos todos los controles del tipo Input
    for (i = 0; i < checkboxes.length; i++) //recoremos todos los controles
    {
        if (checkboxes[i].type == "checkbox") //solo si es un checkbox entramos
        {
            checkboxes[i].checked = source.checked; //si es un checkbox le damos el valor del checkbox que lo llamó (Marcar/Desmarcar Todos)
        }
    }
};

$("#AccountingDocument_id_carrier").change(function()
{
    switch($(this).attr("id"))
    {
        case "AccountingDocument_id_carrier":
            $("img#filterForPeriod").css("display","none");
            $("input#AccountingDocument_from_date,input#AccountingDocument_to_date").val("");$("div.filterForPeriod").hide("slow");
            $SORI.AJAX.send("GET", "/AccountingDocument/getDispute",$("#accounting-document-form").serialize(), null);
            break;
    }
    
});

$("img#filterForPeriod,img#updateGetDispute").click(function()
{
    switch ($(this).attr("id")) 
    {
        case "filterForPeriod":
            if($("div.filterForPeriod").css("display")=="none")
            {
                $("div.filterForPeriod").show("slow");$("img#filterForPeriod").css("background","rgba(226, 168, 140, 1");
            }
            else
            {
                $("div.filterForPeriod").hide("slow");
                $("img#filterForPeriod").css("background","rgba(111,204,187,1");
                $("input#AccountingDocument_from_date,input#AccountingDocument_to_date").val("");
                $SORI.AJAX.send("GET", "/AccountingDocument/getDispute",$("#accounting-document-form").serialize(), null);
            }
            break;
        case "updateGetDispute":
            if($("input#AccountingDocument_from_date,input#AccountingDocument_to_date").val()=="")
            {
                $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>Debe seleccionar las dos fechas que conforman el periodo</h3>","aguanta.png","2000","width:40px; height:90px;"); 
            }
            else
            {
                $SORI.AJAX.send("GET", "/AccountingDocument/getDispute",$("#accounting-document-form").serialize(), null);
            }
            break;
    }
});

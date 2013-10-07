$(document).on('ready',function()
{
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
		//Cuento cuantos archivos ya se han cargado
		msj.contar('li[name="diario"]');
		$("div.diario").fadeIn("slow").css({'display':'block'});
		$("div.horas").fadeOut("slow");
		$("div.rerate").fadeOut("slow");
		console.log(msj.acumulador);
		if(msj.acumulador>=2)
		{
			$('input[type="file"], input[type="submit"]').attr('disabled','disabled');
		}
		else
		{
			$('input[type="file"], input[type="submit"]').removeAttr('disabled');
		}
	});
	//Muestra mensaje con el nombre de los archivos por hora guardados en el dia
	$('input[value="hora"]').on('click',function()
	{
		$("div.horas").fadeIn("slow").css({'display':'block'});
		$("div.diario").fadeOut("slow");
		$("div.rerate").fadeOut("slow");
		$('input[type="file"], input[type="submit"]').removeAttr('disabled');
	});
	//Muestra mensaje con el nombre de los archivos rerates guardados
	$('input[value="rerate"]').on('click',function()
	{
		$("div.rerate").fadeIn("slow").css({'display':'block'});
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
    $('input[name="grabar"]').on('click',function(e)
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
			var html="<p>Este este proceso es irreversible </br> ¿Esta seguro de los Archivos a Cargar?</p><button name='aceptar'><b>Aceptar</b></button><button name='cancelar'>Cancelar</button>";
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
    var manager=$("#CarrierManagers_id_managers").val();
    var asignados=$("#select_left").val();
    var noasignados=$("#select_right").val();

    if(manager=="")
    {
        var aguanta=$("<div class='cargando'></div><div class='mensaje'><h3>Debe seleccionar un manager</h3><p><p><p><p><p><p><p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
        $("body").append(aguanta)
            aguanta.fadeIn('fast');
            setTimeout(function()
            {
                aguanta.fadeOut('fast');
            }, 3000);
    }
    else
    {
        $.ajax({
            type: "GET",
            url: "BuscaNombres",
            data: "asignados="+asignados+"&noasignados="+noasignados+"&manager="+manager,
            success: function(data)
            {
                var carriers=data.split("/");
                var managerName=carriers[0].split(",");
                var asigname=carriers[2].split(",");
                var noasigname=carriers[1].split(",");
                                  
                if(asigname<="1" && noasigname<="1")
                {
                    var nohaynada=$("<div class='cargando'></div><div class='mensaje'><h3>No hay carriers preselccionados <br> para asignar o \n\
                                                 desasignar</h3><p><p><p><p><p><p><p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
                    $("body").append(nohaynada)
                        nohaynada.fadeIn('fast');
                        setTimeout(function()
                        {
                            nohaynada.fadeOut('fast');
                        }, 4000);
                    }
                    else
                    {
                        if(asigname=="")
                        {
                            var asig="";
                        }
                        else
                        {
                            var asig='Asignarle: ';
                        }
                        if(noasigname=="")
                        {
                            var desA="";
                        }
                        else
                        {
                            var desA='Dsasignarle:';
                        }
                        var revisa=$("<div class='cargando'></div><div class='mensaje'><h4>Esta a punto de realizar los siguientes cambios en la Distribucion \n\
                                      \n\Comercial para el manager: <br><b>"+managerName+"</b></h4>\n\<p><h6>"+asig+"<p>"+asigname+"</h6><p><p><h6>"+desA+"<p>\n\
                                      "+noasigname+"</h6><p>Si esta seguro presione Aceptar, de lo contrario Cancelar<p><p><p><p><p><p><p><div id='cancelar'\n\
                                      class='cancelar'><p><label><b>Cancelar</b></label></div>&nbsp;<div id='confirma' class='confirma'><p><label><b>Aceptar</b></label></div></div>").hide();
                        $("body").append(revisa);
                            revisa.fadeIn('fast');
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
                                    var carriers=data.split("/");
                                    var managerName=carriers[0].split(",");
                                    var asigname=carriers[2].split(",");
                                    var noasigname=carriers[1].split(",");

                                    if(asigname=="")
                                    {
                                        var pudo="";
                                    }
                                    else
                                    {
                                        var pudo='Le fue asignado:';
                                    }
                                    if(noasigname=="")
                                    {
                                        var nopudo="";
                                    }
                                    else
                                    {
                                        var nopudo='Desasignado:';
                                    }
                                    var espere=$('.mensaje').html("<h5>Al manager <b>" + managerName + "</b><br>" + pudo + "<br><b>" + asigname + "</b></h5><p><h5>" + nopudo + "\
                                                             <br><b>" + noasigname + "</b></h5><p><p><img src='/images/si.png'width='95px' height='95px'/>").hide().fadeIn('fast');
                                    setTimeout(function()
                                    {
                                        espere.fadeOut('fast');
                                    }, 4000);
                                    setTimeout(function()
                                    {
                                        $('.cargando').fadeOut('fast');
                                    }, 4000);
                                }
                            });
                        }
                        else
                        {
                            revisa.fadeOut('fast');
                        }
                    });
                }
            });
        }
        $("#carriers select option").prop("selected",false);
    });
          
$("#options_right").on( "click",function asignadosAnoasignados()
{
    $('#select_left :selected').each(function(i,selected)
    {
        $("#select_left option[value='"+$(selected).val()+"']").remove();
        $('#select_right').append("<option value='"+$(selected).val()+"'>"+$(selected).text()+"</option>");
    });
});
$("#options_left").on( "click",  function noasignadosAasignados()
{
    $('#select_right :selected').each(function(i,selected)
    {
        $("#select_right option[value='"+$(selected).val()+"']").remove();
        $('#select_left').append("<option value='"+$(selected).val()+"'>"+$(selected).text()+"</option>");
    });
});
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

$SORI.UI.formChange('Contrato_id_carrier');

$('#botAsignarContrato').click('on',function(e)
{
    e.preventDefault();
    $("#Contrato_id_company").prop("disabled", false);
    $("#Contrato_end_date").prop("disabled", false);
    $("#Contrato_sign_date").prop("disabled", false);
    var carrier = $("#Contrato_id_carrier").val();
    var company = $("#Contrato_id_company").val();
    var termino_pago = $("#Contrato_id_termino_pago").val();
    var monetizable = $("#Contrato_id_monetizable").val();
    var diasDisputaOculto = $("#dias_disputa_Oculto").val();
    var F_Firma_Contrato_Oculto = $("#F_Firma_Contrato_Oculto").val();
    var F_P_produccion_Oculto = $("#F_P_produccion_Oculto").val();
    var monetizableOculto = $("#monetizable_Oculto").val();      
    var TPOculto = $("#TerminoP_Oculto").val();
    
    var dias_disputa=$("#Contrato_id_disputa").val();
    var credito=$("#Contrato_id_limite_credito").val();
    var compra=$("#Contrato_id_limite_compra").val();
    
    if(monetizable==''||termino_pago=='' ||company=='' ||credito<'1' ||compra<'1' ||dias_disputa<'1'){
        var stop = $("<div class='cargando'></div><div class='mensaje'><h3>Faltan campos por llenar en el formulario</h3><p><p><p><p><p><p><p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
                    $("body").append(stop)
                    stop.fadeIn('fast');
                    setTimeout(function()
                    {stop.fadeOut('fast');
                    }, 3000);
    }else{
    $.ajax({
        type: "GET",
        url: "ContratoConfirma",
        data: "id_carrier="+carrier+"&id_company="+company+"&id_monetizable="+monetizable+"&id_M_Oculto="+monetizableOculto+"&id_termino_pago="+termino_pago+"&id_TP_Oculto="+TPOculto,
        success: function(data)
        {
            obj=JSON.parse(data);
            var carrierName=obj.carrierName;
            var companyName=obj.companyName;
            var termino_pName=obj.termino_pName;
            var monetizableName=obj.monetizableName;
            var sign_date = $("#Contrato_sign_date").val();
            var production_date = $("#Contrato_production_date").val();
            var end_date=$("#Contrato_end_date").val();
            var monetizableNameO=obj.monetizableNameO;
            var termino_pNameO=obj.termino_pNameO;
            var creditoO = $("#credito_Oculto").val();
            var compraO = $("#compra_Oculto").val();

            if(TPOculto==false && monetizableOculto==false)
            {
                var guardoEdito=" Se guardo con exito el Contrato";
                var revisa = $("<div class='cargando'></div><div class='mensaje'><h4>Esta a punto de crear un nuevo Contrato: \n\
                                <br><b>( "+carrierName+" / "+companyName+" )</b></h4><p>Con las siguientes condiciones comerciales:\n\
                                <p><h6>Termino de pago: "+termino_pName+"</p><p>Monetizable: "+monetizableName+"</p>\n\
                                <p>Dias de disputa: "+dias_disputa+"</p><p>Limite de Credito: "+credito+"</p>\n\
                                <p>Limite de Compra: "+compra+"</p>\n\
                                <p>Fecha de Firma de contrato: "+sign_date+"</p>\n\
                                <p>Fecha de puesta en produccion: "+production_date+"</p>\n\
                                </h6>\n\
                                <p>Si todos los datos a almacenar son correctos, presione Aceptar, de lo contrario Cancelar<p><p><div id='cancelar'\n\
                                                   class='cancelar'><p><label><b>Cancelar</b></label></div>&nbsp;<div id='confirma' class='confirma'>\n\
                                                   <p><label><b>Aceptar</b></label></div></div>").hide();
                $("body").append(revisa);
                revisa.fadeIn('fast');
            }
            else
            {
                guardoEdito=" Se realizaron los siguientes cambios en el Contrato";  
                if(diasDisputaOculto!=dias_disputa )
                {
                    var backDiasDiasputa="Dias de Disputa de: "+diasDisputaOculto+" a ";
                }
                else
                {
                    backDiasDiasputa=" Dias de Disputa: ";
                }
                if(F_Firma_Contrato_Oculto!=sign_date)
                {
                    var backF_Firma ="Fecha de firma de contrato de: "+F_Firma_Contrato_Oculto+" a ";
                }
                else
                {
                    backF_Firma ="Fecha de firma de contrato: ";
                }       
                if(F_P_produccion_Oculto!=production_date)
                {
                    var backProduccion ="Fecha de Puesta en Produccion de: "+F_P_produccion_Oculto+" a ";
                }
                else
                {
                    backProduccion ="Fecha de Puesta en Produccion: ";
                }       
                if(TPOculto!=termino_pago)
                {
                    var backTPago="Terminos de pago de: "+termino_pNameO+" a ";
                }
                else
                {
                    backTPago= "Terminos de pago: ";
                }
                if(monetizableOculto!=monetizable)
                {
                    var backMonetizable="Monetizable de: "+monetizableNameO+" a "; 
                }
                else
                {
                    backMonetizable="Monetizable: ";  
                }
                if(creditoO != credito)
                {
                    var backCredito="Limite de Credito de: "+creditoO+" a "; 
                }
                else
                {
                    backCredito="Limite de Credito: ";  
                }
                if(compraO != compra)
                {
                    var backCompra="Limite de Compra de: "+compraO+" a "; 
                }
                else
                {
                    backCompra="Limite de Compra: ";  
                }
                if(end_date!="")
                {
                    var advertencia=" <h4>Esta a punto de finalizar el Contrato<br><b>("+carrierName+" / "+companyName+")</b></h4>";
                }
                else
                {
                    advertencia="<h4>Esta a punto de realizar los siguientes cambios en el Contrato\n\
                                              : <br><b>("+carrierName+" / "+companyName+")</b></h4>\n\<p>\n\
                                               <h6>"+backTPago+ ""+termino_pName+"<p>\n\
                                               <p>"+backMonetizable+" "+monetizableName+"<p>\n\
                                                   "+backDiasDiasputa+" "+dias_disputa+"<p>\n\
                                                   "+backCredito+" "+credito+"<p>\n\
                                                   "+backCompra+" "+compra+"<p>\n\
                                               <p>"+backF_Firma+" "+sign_date+"<p> \n\
                                                   "+backProduccion+" "+production_date+"<p>";
                }
                var revisa=$("<div class='cargando'></div><div class='mensaje'>"+advertencia+"<p></h6><p><p>Si esta seguro \n\
                                                   de realizar los cambios, presione Aceptar, de lo contrario Cancelar<p><p><div id='cancelar'\n\
                                                   class='cancelar'><p><label><b>Cancelar</b></label></div>&nbsp;<div id='confirma' class='confirma'>\n\
                                                   <p><label><b>Aceptar</b></label></div></div>").hide();
                $("body").append(revisa);
                revisa.fadeIn('fast');         
            }
            $('#confirma,#cancelar').on('click',function()
            {
                var tipo=$(this).attr('id');
                if(tipo=="confirma")
                {  
                    $.ajax({
                        type: "GET",
                        url: "Contrato",
                        data: "sign_date="+sign_date+"&production_date="+production_date+"&end_date="+end_date+"&id_carrier="+carrier+"&id_company="+company+"&id_termino_pago="+termino_pago+"&id_monetizable="+monetizable+"&dias_disputa="+dias_disputa+"&credito="+credito+"&compra="+compra,
                        success: function(data) 
                        {  
                            
                            if(end_date!="")
                            {
                                var efectivo="<h4>El Contrato: <br><b>("+carrierName+" / "+companyName+")</b></h4>\n\<p><h6><p>\n\
                                                Fue Finalizado con exito en la fecha: "+end_date+"<p></h6><p>\n\<img src='/images/si.png'width='90px' height='50px'/>";
                            }
                            else
                            {
                                efectivo="<h4>"+guardoEdito+"\n\
                                               : <br><b>("+carrierName+" / "+companyName+")</b></h4>\n\<p><h6><p>Terminos de Pago:"+termino_pName+"<p>Monetizable: "+monetizableName+"<p>Dias de disputa:"+dias_disputa+"<p>Limite de Credito:"+credito+"<p>Limite de Compra:"+compra+"<p>Fecha de firma de contrato: "+sign_date+"<p>Fecha de puesta en Produccion:"+production_date+"<p>"+end_date+"<p><p>\n\
                                               </h6><p><img src='/images/si.png'width='90px' height='50px'/>";
                            }
                            var exito=$('.mensaje').html(efectivo).hide().fadeIn('fast');
                            setTimeout(function()
                            {
                                exito.fadeOut('fast');
                                $('.cargando').fadeOut('fast');
                            }, 4000);
                        }
                    });
                    $("#Contrato_id_company").prop("disabled", true);
                    $("#Contrato_end_date").prop("disabled", false);
                    $("#Contrato_sign_date").prop("disabled", true);
                }
                else
                {
                    revisa.fadeOut('slow');  
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
    var GeographicZone = $("#GeographicZone_id").val();
    var asignados = $("#select_right").val();
    var noasignados = $("#select_left").val(); 
    var destinos = $('#GeographicZone_id_destination').val();

    if(GeographicZone=="")
    {
        var aguanta = $("<div class='cargando'></div><div class='mensaje'><h3>Debe seleccionar un destino y una zona geografica</h3><p><p><p><p><p><p><p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
        $("body").append(aguanta)
        aguanta.fadeIn('fast');
        setTimeout(function()
        {
            aguanta.fadeOut('fast');
        }, 3000);
    }
    else
    {
        if(destinos==1)
        {
            var tipoDestino="Destinos Externos";
            var elijeDestinationBuscaName="../Destination/BuscaNombresDes";
            var destination_update="../Destination/UpdateZonaDestination";
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
                obj = JSON.parse(data);
                var GeographicZoneName = (obj.GeographicZoneName);
                var AsigNames = (obj.asigNames);
                var NoAsigNames = (obj.noasigNames);
                if(AsigNames<1&&NoAsigNames<1)
                {
                    var NoHayDatos = $("<div class='cargando'></div><div class='mensaje'><h3>No hay datos que cambiar</h3><p><p><p><p><p><p><p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
                    $("body").append(NoHayDatos)
                    NoHayDatos.fadeIn('fast');
                    setTimeout(function()
                    {
                        NoHayDatos.fadeOut('fast');
                    }, 3000);   
                }
                else
                {
                    if(AsigNames=="")
                    {
                        var asig="";
                    }
                    else
                    {
                        var asig='Asignarle: ';
                    }
                    if(NoAsigNames=="")
                    {
                        var desA="";
                    }
                    else
                    {
                        var desA = 'Dsasignarle:';
                    }
                    var revisa = $("<div class='cargando'></div><div class='mensaje'><h4>\n\
                                        Esta a punto de realizar actualizar "+tipoDestino+" la Zona Geográfica: \n\
                                        <br><b> "+GeographicZoneName+"</h4><h4></b>"+asig+"</h4>\n\<p> <h6><p>\n\
                                        "+AsigNames+"</h6><p><br><h4>"+desA+"</h4><p><h6><p><p>\n\
                                        "+NoAsigNames+"</h6><p>Si esta seguro presione Aceptar, de lo contrario Cancelar\n\
                                        <p><p><p><div id='cancelar'class='cancelar'><p><label><b>\n\
                                        Cancelar</b></label></div>&nbsp;<div id='confirma' class='confirma'>\n\
                                        <p><label><b>Aceptar</b></label></div></div>").hide();
                    $("body").append(revisa);
                    revisa.fadeIn('fast');
                    $('#confirma,#cancelar').on('click',function()
                    {
                        var tipo=$(this).attr('id');
                        if(tipo=="confirma")
                        {
                            $('.mensaje').html("<h2>Espere un momento por favor</h2><p><p><p><p><p><p><p><p><p<p><p><p><img src='/images/image_464753.gif'width='95px' height='95px'/><p><p><p><p><p><p><p><p<p><p>").hide().fadeIn('fast');
                            $.ajax({
                                type: "GET",
                                url: destination_update,
                                data: "asignados="+asignados+"&noasignados="+noasignados+"&GeographicZone="+GeographicZone,
                                success: function(data)
                                {
                                    var exito=$('.mensaje').html("<h4>Se han actualizado los "+tipoDestino+" con la Zona Geográfica: \n\
                                        <br><b> "+GeographicZoneName+"</h4><h4></b>"+asig+"</h4>\n\<p> <h6><p>\n\
                                        "+AsigNames+"</h6><p><br><h4>"+desA+"</h4><p><h6><p><p>\n\
                                        "+NoAsigNames+"</h6><img src='/images/si.png'width='95px' height='95px'/>").hide().fadeIn('fast');
                                    setTimeout(function()
                                    {
                                        exito.fadeOut('fast');
                                        $('.cargando').fadeOut('fast');
                                    }, 4000);      
                                }
                            });
                        }
                        else
                        {
                            revisa.fadeOut('fast');
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
    var GeographicZone=$("#GeographicZone_id").val();
    var destinos=$('#GeographicZone_id_destination').val();
    if(GeographicZone!="")
    {
        $.ajax({
            type: "POST",
            url: "DynamicAsignados",
            data: 'GeographicZone='+GeographicZone+'&destinos='+destinos,
            success: function(data)
            {
                $('#select_right').empty().append(data);
            }
        });
    }
});
$('#GeographicZone_id').change(function()
{
    var GeographicZone=$("#GeographicZone_id").val();
    var destinos=$('#GeographicZone_id_destination').val();
    if(destinos<1)
    {
        var aguanta=$("<div class='cargando'></div><div class='mensaje'><h3>Debe seleccionar un destino antes de seleccionar la zona geográfica</h3><p><p><p><p><p><p><p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
        $("body").append(aguanta)
        aguanta.fadeIn('fast');
        setTimeout(function()
        {
            aguanta.fadeOut('fast');
        }, 1000);
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
//     fin de adm de zonas y destinos

/**
 * admin de documentos contables
 */
$('#AccountingDocumentTemp_id_type_accounting_document').change(function()
{
    $('div.instruccion').slideUp('slow');
    $('div.valoresDocumento').slideDown('slow');
});
$('div.hacerUnaNota').click('on',function()
{
    $('div.hacerUnaNota').hide('slow');
    $('div.contratoFormTextArea').fadeIn('slow');
    $('textarea#AccountingDocumentTemp_note').fadeIn('slow');
});
$('.quitaNota').click('on',function()
{
    $('div.hacerUnaNota').slideDown('slow');
    $('div.contratoFormTextArea').hide('slow');
    $('textarea#AccountingDocumentTemp_note').hide('slow');
});
$('#botAgregarDatosContable').click('on',function(e)
{
    e.preventDefault();
    var selecTipoDoc=$('#AccountingDocumentTemp_id_type_accounting_document').val();
    alert(selecTipoDoc);
    var idCarrier=$('#AccountingDocumentTemp_id_carrier').val();
    var fechaEmision=$('#AccountingDocumentTemp_issue_date').val();
    var desdeFecha=$('#AccountingDocumentTemp_from_date').val();
    var hastaFecha=$('#AccountingDocumentTemp_to_date').val();
    var fechaRecepcion=$('#AccountingDocumentTemp_received_date').val();
    var fechaEnvio=$('#AccountingDocumentTemp_sent_date').val();
    var numDocumento=$('#AccountingDocumentTemp_doc_number').val();
    var minutos=$('#AccountingDocumentTemp_minutes').val();
    var cantidad=$('#AccountingDocumentTemp_amount').val();
    var nota=$('#AccountingDocumentTemp_note').val();
                    
    if(idCarrier==''||fechaEmision==''||desdeFecha==''||hastaFecha==''||fechaRecepcion==''||fechaEnvio==''||numDocumento==''||minutos==''||cantidad=='')
    {
        var msjIndicador = $("<div class='cargando'></div><div class='mensaje'><h3>Faltan datos por agregar</h3><p><p><p><p><p><p><p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
        $("body").append(msjIndicador);
        msjIndicador.fadeIn('fast');
        setTimeout(function()
        {
            msjIndicador.fadeOut('fast');
        }, 1000);
    }
    else
    {
        $.ajax({
            type: "GET",
            url: "guardarListaTemp",
            data: "&fechaEmision="+fechaEmision+"&idCarrier="+idCarrier+"&desdeFecha="+desdeFecha+"&hastaFecha="+hastaFecha+"&fechaRecepcion="+fechaRecepcion+"\
                    &fechaEnvio="+fechaEnvio+"&numDocumento="+numDocumento+"&minutos="+minutos+"&cantidad="+cantidad+"&nota="+nota+"&selecTipoDoc="+selecTipoDoc,

              success: function(data) 
                      {
                obj = JSON.parse(data);
                var idCarrierNameTemp=obj.idCarrierNameTemp, 
                    selecTipoDocNameTemp=obj.selecTipoDocNameTemp,
                    fechaEmisionTemp=obj.fechaEmisionTemp,
                    desdeFechaTemp=obj.desdeFechaTemp,
                    hastaFechaTemp=obj.hastaFechaTemp,
                    fechaRecepcionTemp=obj.fechaRecepcionTemp,
                    fechaEnvioTemp=obj.fechaEnvioTemp,
                    numDocumentoTemp=obj.numDocumentoTemp,
                    minutosTemp=obj.minutosTemp,
                    cantidadTemp=obj.cantidadTemp;
                
                $(".tablaVistDocTemporales").find("tr:first").after("<tr class='vistaTemp' id='"+obj.idDoc+"'>\n\
                                                        <td id='AccountingDocumentTemp[id_type_accounting_document]'>"+selecTipoDocNameTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[id_carrier]'>"+idCarrierNameTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[issue_date]'>"+fechaEmisionTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[from_date]'>"+desdeFechaTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[to_date]'>"+hastaFechaTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[received_date]'>"+fechaRecepcionTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[sent_date]'>"+fechaEnvioTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[doc_number]'>"+numDocumentoTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[minutes]'>"+minutosTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[amount]'>"+cantidadTemp+"</td>\n\
                                                        <td><img class='edit' name='edit' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td>\n\
                                                    </tr>");

                $('.tablaVistDocTemporales').fadeIn('slow');
                
                $('#botAgregarDatosContableFinal').fadeIn('slow');

                                    $SORI.UI.init();
                                    $("#AccountingDocumentTemp_doc_number").val('');
                                    $("#AccountingDocumentTemp_minutes").val('');
                                    $("#AccountingDocumentTemp_amount").val('');
                                    $("#AccountingDocumentTemp_note").val('');
                      }          
        }); 
     }
}); 
    
      $('#botAgregarDatosContableFinal').click('on',function(e)
    { e.preventDefault();
                var revisa=$("<div class='cargando'></div><div class='mensaje'>Esta a punto de guardar todos los documentos contables de \n\
                             forma definitiva<p></h6><p><p>Si esta seguro, presione Aceptar, de lo contrario Cancelar<p><p><div id='cancelar'\n\
                             class='cancelar'><p><label><b>Cancelar</b></label></div>&nbsp;<div id='confirma' class='confirma'>\n\
                             <p><label><b>Aceptar</b></label></div></div>").hide();

                $("body").append(revisa);
                revisa.fadeIn('fast');         
            
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
                           revisa.fadeOut('fast');
                           var obj = JSON.parse(data);

                           var exito=$("<div class='cargando'></div>").hide();
                                     $("body").append(exito);
                                     exito.fadeIn('fast');

                                     $('.mensajeFinal').fadeIn('slow');
                                     $('.tablamensaje').fadeIn('slow');

                                     $('.filasMsjFinal').empty();

                           for (var i = 0,j=obj.length;i<=j;i++){
                            $('.tablamensaje').append("<tr class='filasMsjFinal'><td>"+(obj[i].tipo)+"</td><td>"+(obj[i].carrier)+"</td><td>"+(obj[i].fecha)+"</td><td>"+(obj[i].monto)+"</td></tr>");          

                                     setTimeout(function(){
                                     $('.mensajeFinal').fadeOut('slow');
                                     $('.cargando').fadeOut('slow');
                                     }, 4000);

                                     exito=null;
                                     $('#botAgregarDatosContableFinal').fadeOut('slow');
                                      $('.tablaVistDocTemporales').fadeOut('slow');
                                     $('.vistaTemp').empty();
                           }  
                         }  
                    });
                }else{
                     revisa.fadeOut('fast');
                }
           });
     });
/**Vista Uploads*/
$(function() 
{
    $( ".datepicker" ).datepicker();
    $( ".datepicker" ).datepicker( "option", "dateFormat", "mm-dd-yy" );
    $( ".datepicker" ).datepicker( "option", "showAnim", "drop" );
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

//
//                        $(".cargando").append("<h3>El documento contable\n\
//                                     fue guardado con exito</h3><table border='4' class='tablamensaje'><tr>\n\
//                                     <td> Tipo de Doc </td><td> Carrier </td><td> Fecha de Emisión </td><td>\n\
//                                     Monto </td></tr></table><p><img src='/images/si.png'width='95px' height='95px'/></p>");


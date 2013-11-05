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
    var manager=$("#CarrierManagers_id_managers").val(),
    asignados=$("#select_left").val(),
    noasignados=$("#select_right").val();

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
                var carriers=data.split("/"),
                managerName=carriers[0].split(","),
                asigname=carriers[2].split(","),
                noasigname=carriers[1].split(",");
                                  
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
                        $(".cargando").hide();
                        $(".mensaje").hide();
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
                                    var carriers=data.split("/"),
                                    managerName=carriers[0].split(","),
                                    asigname=carriers[2].split(","),
                                    noasigname=carriers[1].split(",");

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
    var carrier = $("#Contrato_id_carrier").val(),
    company = $("#Contrato_id_company").val(),
    termino_pago = $("#Contrato_id_termino_pago").val(),
    monetizable = $("#Contrato_id_monetizable").val(),
    Contrato_up = $("#Contrato_up").val(),
    diasDisputaOculto = $("#dias_disputa_Oculto").val(),
    diasDisputaSolvedOculto = $("#dias_disputa_solved_Oculto").val(),
    F_Firma_Contrato_Oculto = $("#F_Firma_Contrato_Oculto").val(),
    F_P_produccion_Oculto = $("#F_P_produccion_Oculto").val(),
    monetizableOculto = $("#monetizable_Oculto").val(),      
    TPOculto = $("#TerminoP_Oculto").val(),
    Contrato_upOculto = $("#Contrato_upOculto").val();
    
    var dias_disputa=$("#Contrato_id_disputa").val(),
    dias_disputa_solved=$("#Contrato_id_disputa_solved").val(),
    credito=$("#Contrato_id_limite_credito").val(),
    compra=$("#Contrato_id_limite_compra").val();
    
    if(monetizable==''||termino_pago=='' ||company=='' ||credito<'1' ||compra<'1' || Contrato_up==''){
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
        data: "id_carrier="+carrier+"&id_company="+company+"&id_monetizable="+monetizable+"&Contrato_up="+Contrato_up+"&id_M_Oculto="+monetizableOculto+"&id_termino_pago="+termino_pago+"&id_TP_Oculto="+TPOculto,
        success: function(data)
        {
            var obj=JSON.parse(data),
            carrierName=obj.carrierName,
            companyName=obj.companyName,
            termino_pName=obj.termino_pName,
            monetizableName=obj.monetizableName,
            Contrato_upC=obj.Contrato_upConfirma,
            sign_date = $("#Contrato_sign_date").val(),
            production_date = $("#Contrato_production_date").val(),
            end_date=$("#Contrato_end_date").val(),
            monetizableNameO=obj.monetizableNameO,
            termino_pNameO=obj.termino_pNameO,
            creditoO = $("#credito_Oculto").val(),
            compraO = $("#compra_Oculto").val();

            if(TPOculto==false && monetizableOculto==false)
            {
                var guardoEdito=" Se guardo con exito el Contrato";
                var revisa = $("<div class='cargando'></div><div class='mensaje'><h4>Esta a punto de crear un nuevo Contrato: \n\
                                <br><b>( "+carrierName+" / "+companyName+" )</b></h4><p>Con las siguientes condiciones comerciales:\n\
                                <p><h6>Termino de pago: "+termino_pName+"</p><p>Monetizable: "+monetizableName+"</p>\n\
                                <p>Dias max para disputar: "+dias_disputa+"</p><p>Limite de Credito: "+credito+"</p>\n\
                                <p>Dias para solventar disputas: "+dias_disputa_solved+"</p>\n\
                                <p>Limite de Compra: "+compra+"</p>\n\
                                <p>Unidad de producción: "+Contrato_upC+"</p>\n\
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
                    var backDiasDiasputa="Dias max para disputar de: "+diasDisputaOculto+" a ";
                }
                else
                {
                    backDiasDiasputa="Dias max para disputar: ";
                }
                if(diasDisputaSolvedOculto!=dias_disputa_solved )
                {
                    var backDiasDiasputaSolved="Dias para solventar disputas de: "+diasDisputaSolvedOculto+" a ";
                }
                else
                {
                    backDiasDiasputaSolved="Dias para solventar disputas: ";
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
                if(Contrato_upOculto==''){
                     backUP="Unidad de producción: ";  
                }else{
                    if(Contrato_upOculto != Contrato_up)
                    {
                        if (Contrato_upOculto==0){
                            Contrato_upOculto='Ventas';
                        }else if(Contrato_upOculto==1){
                            Contrato_upOculto='Presidencia';
                        }
                        var backUP="Unidad de producción de: "+Contrato_upOculto+" a "; 
                    }
                    else
                    {
                        backUP="Unidad de producción: ";  
                    }
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
                                                   "+backDiasDiasputaSolved+" "+dias_disputa_solved+"<p>\n\
                                                   "+backCredito+" "+credito+"<p>\n\
                                                   "+backCompra+" "+compra+"<p>\n\
                                                   "+backUP+" "+Contrato_upC+"<p>\n\
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
                        data: "sign_date="+sign_date+"&production_date="+production_date+"&end_date="+end_date+"&id_carrier="+carrier+"&id_company="+company+"&id_termino_pago="+termino_pago+"&id_monetizable="+monetizable+"&dias_disputa="+dias_disputa+"&dias_disputa_solved="+dias_disputa_solved+"&credito="+credito+"&compra="+compra+"&Contrato_up="+Contrato_up,
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
                                               : <br><b>("+carrierName+" / "+companyName+")</b></h4>\n\<p><h6><p>Terminos de Pago:"+termino_pName+"<p>Monetizable: "+monetizableName+"<p>Dias max para disputar:"+dias_disputa+"<p>Dias para solventar disputas:"+dias_disputa_solved+"<p>Limite de Credito:"+credito+"<p>Limite de Compra:"+compra+"<p>Unidad de producción: "+Contrato_upC+"<p>Fecha de firma de contrato: "+sign_date+"<p>Fecha de puesta en Produccion:"+production_date+"<p>"+end_date+"<p><p>\n\
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
                    $("#Contrato_sign_date").prop("disabled", false);
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
    var GeographicZone = $("#GeographicZone_id").val(),
    asignados = $("#select_right").val(),
    noasignados = $("#select_left").val(), 
    destinos = $('#GeographicZone_id_destination').val();

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
                var obj = JSON.parse(data),
                GeographicZoneName = (obj.GeographicZoneName),
                AsigNames = (obj.asigNames),
                NoAsigNames = (obj.noasigNames);
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
                                        Esta a punto de actualizar "+tipoDestino+" la Zona Geográfica: \n\
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
/**
 *   fin de adm de zonas y destinos
 */

/**
 * admin de documentos contables
 */
$('#AccountingDocumentTemp_id_type_accounting_document').change(function()
{
                  var tipoDocument= $('#AccountingDocumentTemp_id_type_accounting_document').val(),
                      CarrierDocument=$('.CarrierDocument'),
                      GrupoDocument=$('.GrupoDocument'),
                      emailReceivedDate=$('.emailReceivedDate'),
                      emailRecDate=$('.emailRecDate'),
                      fechaDeEmision=$('.fechaDeEmision'),
                      fechaDeInicio=$('.fechaDeInicio'),
                      fechaFinal=$('.fechaFinal'),
                      emailReceivedTime=$('.emailReceivedTime'),
                      minutosDoc=$('.minutosDoc'),
                      MinutosE=$('.MinutosE'),
                      minutosDocProveedor=$('.minutosDocProveedor'),
                      DestinoEtx=$('.DestinoEtx'),
                      DestinoProv=$('.DestinoProv'),
                      MontoE=$('.MontoE'),
                      Moneda=$('.Moneda'),
                      doc_number=$('.doc_number'),
                      Select_doc_number=$('select#AccountingDocumentTemp_doc_number'),
                      Input_doc_number=$('input#AccountingDocumentTemp_doc_number'),
                      Select_dest_prov=$('select#AccountingDocumentTemp_id_destination_supplier'),
                      Input_dest_prov=$('input#AccountingDocumentTemp_id_destination_supplier'),
                      Monto_doc=$('#AccountingDocumentTemp_amount'),
                      divNumDoc=$('.numDocument '),
                      montoDocProveedor=$('.montoDocProveedor');
                      


            if (tipoDocument=='1')//facturas enviadas
                {
                    
                      MinutosE.html('Minutos');
                      doc_number.html('Num. Documento');
                      minutosDocProveedor.hide('slow');
                      DestinoEtx.hide('slow');
                      DestinoProv.hide('slow');
                      Monto_doc.show('slow');
                      MontoE.show('slow');
                      MontoE.html('Monto');
                      montoDocProveedor.hide('slow');   
                      Moneda.show('slow'); 
                      Select_doc_number.hide('slow'); 
                      Input_doc_number.show('slow');
                      Select_dest_prov.hide('slow'); 
                      Input_dest_prov.hide('slow');
                      emailReceivedDate.hide('slow');
                      emailReceivedTime.hide('slow');
                      GrupoDocument.hide('slow');
                      emailRecDate.html('Fecha de recepción de Email');
                      fechaDeEmision.show('slow');
                      fechaDeInicio.show('slow');
                      fechaFinal.show('slow');
                      CarrierDocument.show('slow');
                      minutosDoc.show('slow');
                      divNumDoc.css('width','24%'); 
                      $("#AccountingDocumentTemp_email_received_date,#AccountingDocumentTemp_email_received_hour,#AccountingDocumentTemp_issue_date").val('');
                      $("#AccountingDocumentTemp_from_date,#AccountingDocumentTemp_to_date,#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_id_destination_supplier").val('');
                      $("#AccountingDocumentTemp_minutes, #AccountingDocumentTemp_MinutosProvee, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_MontoProvee, #AccountingDocumentTemp_id_destination").val('');
                }
            if (tipoDocument=='2')//facturas recibidas
                {
                      MinutosE.html('Minutos');
                      doc_number.html('Num. Documento');
                      minutosDocProveedor.hide('slow');
                      DestinoEtx.hide('slow');
                      DestinoProv.hide('slow');
                      Monto_doc.show('slow');
                      MontoE.show('slow');
                      MontoE.html('Monto');
                      montoDocProveedor.hide('slow');   
                      Moneda.show('slow'); 
                      Select_doc_number.hide('slow'); 
                      Input_doc_number.show('slow'); 
                      Select_dest_prov.hide('slow'); 
                      Input_dest_prov.hide('slow');
                      emailReceivedDate.show('slow'); 
                      emailReceivedTime.show('slow');
                      emailRecDate.html('Fecha de recepción de Email');
                      fechaDeEmision.show('slow');
                      fechaDeInicio.show('slow');
                      fechaFinal.show('slow');
                      CarrierDocument.show('slow');
                      minutosDoc.show('slow');
                      GrupoDocument.hide('slow');
                      divNumDoc.css('width','24%'); 
                      $("#AccountingDocumentTemp_issue_date,#AccountingDocumentTemp_from_date,#AccountingDocumentTemp_to_date,#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_id_destination_supplier").val('');
                      $("#AccountingDocumentTemp_minutes, #AccountingDocumentTemp_MinutosProvee, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_MontoProvee, #AccountingDocumentTemp_id_destination").val('');
                }
            if (tipoDocument=='3')//pago
                {
                      MinutosE.html('Minutos');
                      doc_number.html('Num. Documento');
                      minutosDocProveedor.hide('slow');
                      DestinoEtx.hide('slow');
                      DestinoProv.hide('slow');
                      Monto_doc.show('slow');
                      MontoE.show('slow');
                      MontoE.html('Monto ');
                      montoDocProveedor.hide('slow');   
                      Moneda.show('slow'); 
                      Select_doc_number.hide('slow'); 
                      Input_doc_number.show('slow');
                      Select_dest_prov.hide('slow'); 
                      Input_dest_prov.hide('slow');
                      emailReceivedDate.hide('slow');
                      emailReceivedTime.hide('slow');
                      emailRecDate.html('Fecha de recepción de Email');
                      fechaDeEmision.show('slow');
                      fechaDeInicio.hide('slow');
                      fechaFinal.hide('slow');
                      minutosDoc.hide('slow');
                      GrupoDocument.show('slow');
                      CarrierDocument.hide('slow');
                      divNumDoc.css('width','24%'); 
                      $("#AccountingDocumentTemp_email_received_date,#AccountingDocumentTemp_email_received_hour,#AccountingDocumentTemp_id_carrier,#AccountingDocumentTemp_issue_date").val('');
                      $("#AccountingDocumentTemp_from_date,#AccountingDocumentTemp_to_date,#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_id_destination_supplier").val('');
                      $("#AccountingDocumentTemp_minutes, #AccountingDocumentTemp_MinutosProvee, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_MontoProvee, #AccountingDocumentTemp_id_destination").val('');
                }
            if (tipoDocument=='4')//cobro
                {
                      MinutosE.html('Minutos');
                      doc_number.html('Num. Documento');
                      minutosDocProveedor.hide('slow');
                      DestinoEtx.hide('slow');
                      DestinoProv.hide('slow');
                      Monto_doc.show('slow');
                      MontoE.show('slow');
                      MontoE.html('Monto');
                      montoDocProveedor.hide('slow');   
                      Moneda.show('slow'); 
                      Select_doc_number.hide('slow'); 
                      Input_doc_number.show('slow'); 
                      Select_dest_prov.hide('slow'); 
                      Input_dest_prov.hide('slow');
                      emailReceivedDate.show('slow'); 
                      GrupoDocument.show('slow');
                      CarrierDocument.hide('slow');
                      emailRecDate.html('Fecha de recepción');
                      emailReceivedTime.hide('slow');
                      fechaDeEmision.hide('slow');
                      fechaDeInicio.hide('slow');
                      fechaFinal.hide('slow');
                      minutosDoc.hide('slow');
                      divNumDoc.css('width','24%'); 
                      $("#AccountingDocumentTemp_email_received_date,#AccountingDocumentTemp_email_received_hour,#AccountingDocumentTemp_issue_date").val('');
                      $("#AccountingDocumentTemp_issue_date,#AccountingDocumentTemp_from_date,#AccountingDocumentTemp_to_date,#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_id_destination_supplier").val('');
                      $("#AccountingDocumentTemp_minutes, #AccountingDocumentTemp_MinutosProvee, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_MontoProvee, #AccountingDocumentTemp_id_destination").val('');
                }
            if (tipoDocument=='5')//disputas recibidas
                {
                      emailReceivedDate.hide('slow');
                      emailReceivedTime.hide('slow');
                      GrupoDocument.hide('slow');
                      fechaDeEmision.hide('slow');
                      fechaDeInicio.show('slow');
                      fechaFinal.show('slow');
                      CarrierDocument.show('slow');
                      minutosDoc.show('slow');
                      MinutosE.html('Minutos Etelix');
                      doc_number.html('Num. Factura');
                      minutosDocProveedor.show('slow');
                      DestinoEtx.show('slow');
                      DestinoProv.hide('slow');
                      Monto_doc.show('slow');
                      MontoE.show('slow');
                      MontoE.html('Tarifa Etelix');
                      montoDocProveedor.show('slow');   
                      Moneda.hide('slow'); 
                      Select_doc_number.show('slow'); 
                      Input_doc_number.hide('slow'); 
                      Select_dest_prov.hide('slow'); 
                      Input_dest_prov.hide('slow');
                      divNumDoc.css('width','24%');
                      $("#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date, #AccountingDocumentTemp_id_destination").val('');
                       $("#AccountingDocumentTemp_minutes, #AccountingDocumentTemp_MinutosProvee, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_MontoProvee").val('');
                      $SORI.UI.buscaFactura('#AccountingDocumentTemp_id_carrier, #AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date');
                }
             if (tipoDocument=='6')//Disputa enviada
                {
                      emailReceivedDate.hide('slow');
                      emailReceivedTime.hide('slow');
                      GrupoDocument.hide('slow');
                      fechaDeEmision.hide('slow');
                      fechaDeInicio.show('slow');
                      fechaFinal.show('slow');
                      CarrierDocument.show('slow');
                      minutosDoc.show('slow');
                      MinutosE.html('Minutos Etelix');
                      doc_number.html('Num. Factura');
                      minutosDocProveedor.show('slow');
                      DestinoEtx.hide('slow');
                      DestinoProv.show('slow');
                      Monto_doc.show('slow');
                      MontoE.show('slow');
                      MontoE.html('Tarifa Etelix');
                      montoDocProveedor.show('slow');   
                      Moneda.hide('slow'); 
                      Select_doc_number.show('slow'); 
                      Input_doc_number.hide('slow'); 
                      Select_dest_prov.show('slow'); 
                      Input_dest_prov.hide('slow');
                      divNumDoc.css('width','24%');
                       $("#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date, #AccountingDocumentTemp_id_destination").val('');
                       $("#AccountingDocumentTemp_minutes, #AccountingDocumentTemp_MinutosProvee, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_MontoProvee").val('');
                      
                      $SORI.UI.buscaFactura('#AccountingDocumentTemp_id_carrier, #AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date');
                      
                      $('div.nuevoDestProv').click('on',function()

                      {
                          $(this).hide('slow');
                          $('div.cancelarDestProv').show('slow');
                            Select_dest_prov.hide('slow'); 
                            Input_dest_prov.show('slow');
                            Select_dest_prov.val('');
                      });
                      $('div.cancelarDestProv').click('on',function()
                      {
                           $(this).hide('slow');
                           $('div.nuevoDestProv').show('slow');
                            Select_dest_prov.show('slow'); 
                            Input_dest_prov.hide('slow');
                      });
                }
             if (tipoDocument=='7')//Nota de credito enviada
                {
                      emailReceivedDate.hide('slow');
                      emailReceivedTime.hide('slow');
                      GrupoDocument.hide('slow');
                      fechaDeEmision.hide('slow');
                      fechaDeInicio.show('slow');
                      fechaFinal.show('slow');
                      CarrierDocument.show('slow');
                      minutosDoc.hide('slow');
                      MinutosE.html('Minutos Etelix');
                      doc_number.html('Num. Factura');
                      minutosDocProveedor.hide('slow');
                      DestinoEtx.hide('slow');
                      DestinoProv.hide('slow');
                      Monto_doc.hide('slow');
                      MontoE.hide('slow');
                      montoDocProveedor.hide('slow');   
                      Moneda.hide('slow'); 
                      Select_doc_number.show('slow'); 
                      Input_doc_number.hide('slow'); 
                      divNumDoc.css('width','51%'); 
                      
                      $SORI.UI.buscaFactura('#AccountingDocumentTemp_id_carrier, #AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date');
                }
             if (tipoDocument=='8')//Nota de credito recibida
                {
                      emailReceivedDate.hide('slow');
                      emailReceivedTime.hide('slow');
                      GrupoDocument.hide('slow');
                      fechaDeEmision.hide('slow');
                      fechaDeInicio.show('slow');
                      fechaFinal.show('slow');
                      CarrierDocument.show('slow');
                      minutosDoc.hide('slow');
                      MinutosE.html('Minutos Etelix');
                      doc_number.html('Num. Factura');
                      minutosDocProveedor.hide('slow');
                      DestinoEtx.hide('slow');
                      DestinoProv.hide('slow');
                      Monto_doc.hide('slow');
                      MontoE.hide('slow');
                      montoDocProveedor.hide('slow');   
                      Moneda.hide('slow'); 
                      Select_doc_number.show('slow'); 
                      Input_doc_number.hide('slow'); 
                      divNumDoc.css('width','51%'); 
                      
                      $SORI.UI.buscaFactura('#AccountingDocumentTemp_id_carrier, #AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date');
                }
           
    $('div.instruccion').slideUp('slow');
    $('div.valoresDocumento').slideDown('slow');
});
$('div.hacerUnaNota').click('on',function()
{
    $('div.hacerUnaNota').hide('slow');
    $('div.contratoFormTextArea,textarea#AccountingDocumentTemp_note').fadeIn('slow');
});
$('.quitaNota').click('on',function()
{
    $('div.hacerUnaNota').slideDown('slow');
    $('div.contratoFormTextArea,textarea#AccountingDocumentTemp_note').hide('slow');
});

$('#botAgregarDatosContable').click('on',function(e)
{
    e.preventDefault();
    var selecTipoDoc=$('#AccountingDocumentTemp_id_type_accounting_document').val(),
    idCarrier=$('#AccountingDocumentTemp_id_carrier').val(),
    idGrupo=$('#AccountingDocumentTemp_carrier_groups').val(),
    fechaEmision=$('#AccountingDocumentTemp_issue_date').val(),
    desdeFecha=$('#AccountingDocumentTemp_from_date').val(),
    hastaFecha=$('#AccountingDocumentTemp_to_date').val(),
    EmailfechaRecepcion=$('#AccountingDocumentTemp_email_received_date').val(),
    EmailHoraRecepcion=$('#AccountingDocumentTemp_email_received_hour').val(),
    numDocumento=$('#AccountingDocumentTemp_doc_number').val(),
    minutos=$('#AccountingDocumentTemp_minutes').val(),
    cantidad=$('#AccountingDocumentTemp_amount').val(),
    currency=$('#AccountingDocumentTemp_id_currency').val(),
    nota=$('#AccountingDocumentTemp_note').val(),
    Select_doc_number=$('select#AccountingDocumentTemp_doc_number').val(),
    minutosDocProveedor=$('#AccountingDocumentTemp_MinutosProvee').val(),
    DestinoEtx=$('#AccountingDocumentTemp_id_destination').val(),
    Select_dest_prov=$('select#AccountingDocumentTemp_id_destination_supplier').val(),
    Input_dest_prov=$('input#AccountingDocumentTemp_id_destination_supplier').val(),
    montoDocProveedor=$('#AccountingDocumentTemp_MontoProvee').val();
    
    var msjIndicador = $("<div class='cargando'></div><div class='mensaje'><h3>Faltan datos por agregar</h3><p><p><p><p><p><p><p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
    $("body").append(msjIndicador);
//cantidad==''||numDocumento==''||fechaEmision==''||cantidad==''||numDocumento==''
    if( selecTipoDoc=='')
    {
        msjIndicador.fadeIn('fast');
        setTimeout(function()
        { msjIndicador.fadeOut('fast');
        }, 1000);
    }
//    if(selecTipoDoc==1 && idCarrier==''||fechaEmision==''||desdeFecha==''||hastaFecha==''||minutos=='')
//    { 
//        msjIndicador.fadeIn('fast');
//        setTimeout(function()
//        { msjIndicador.fadeOut('fast');
//        }, 1000);
//    }
//    else if(selecTipoDoc==2 && idCarrier==''||fechaEmision==''||desdeFecha==''||hastaFecha==''||minutos==''||EmailfechaRecepcion==''||EmailHoraRecepcion=='')
//    { 
//        msjIndicador.fadeIn('fast');
//        setTimeout(function()
//        { msjIndicador.fadeOut('fast');
//        }, 1000);
//    }
//    else if(selecTipoDoc==3 && idGrupo=='')
//    { 
//        msjIndicador.fadeIn('fast');
//        setTimeout(function()
//        { msjIndicador.fadeOut('fast');
//        }, 1000);
    
    else
     {
        if (selecTipoDoc==1){//facturas enviadas
              var action = "GuardarFac_EnvTemp";
        }
        if (selecTipoDoc==2){//facturas recibidas
              action = "GuardarFac_RecTemp";
        }
        if (selecTipoDoc==3){//pago
              action = "GuardarPagoTemp";
        }
        if (selecTipoDoc==4){//cobro
              action = "GuardarCobroTemp";
        }
        if (selecTipoDoc==5){//Disputa Recibida
              action = "GuardarDispRecibida";
              numDocumento=Select_doc_number;
        }
        if (selecTipoDoc==6){//Disputa Enviada
              action = "GuardarDispEnviada";
              numDocumento=Select_doc_number;
        }
        if (selecTipoDoc==7){//Nota de Credito Enviada
              action = "GuardarNotaDeCreditoEnviada";
              numDocumento=Select_doc_number;
        }
        $.ajax({
            type: "GET",
            url: action,
            data: "&fechaEmision="+fechaEmision+"&idCarrier="+idCarrier+"&idGrupo="+idGrupo+"&desdeFecha="+desdeFecha+"&hastaFecha="+hastaFecha+"&EmailfechaRecepcion="+EmailfechaRecepcion+"&EmailHoraRecepcion="+EmailHoraRecepcion+"&numDocumento="+numDocumento+"&minutos="+minutos+"&cantidad="+cantidad+"&nota="+nota+"&selecTipoDoc="+selecTipoDoc+"&currency="+currency+"&minutosDocProveedor="+minutosDocProveedor+"&DestinoEtx="+DestinoEtx+"&Select_dest_prov="+Select_dest_prov+"&Input_dest_prov="+Input_dest_prov+"&montoDocProveedor="+montoDocProveedor,

              success: function(data) 
                      {
                obj = JSON.parse(data);
                var idCarrierNameTemp=obj.idCarrierNameTemp, 
                    selecTipoDocNameTemp=obj.selecTipoDocNameTemp,
                    fechaEmisionTemp=obj.fechaEmisionTemp,
                    desdeFechaTemp=obj.desdeFechaTemp,
                    hastaFechaTemp=obj.hastaFechaTemp,
                    fechaEnvioTemp=obj.fechaEnvioTemp,
                    EmailfechaRecepcionTemp=obj.EmailfechaRecepcionTemp,
                    valid_received_dateTemp=obj.valid_received_dateTemp,
                    EmailHoraRecepcionTemp=obj.EmailHoraRecepcionTemp,
                    valid_received_hourTemp=obj.valid_received_hourTemp,
                    numDocumentoTemp=obj.numDocumentoTemp,
                    minutosTemp=obj.minutosTemp,
                    cantidadTemp=obj.cantidadTemp,
                    currencyTemp=obj.currencyTemp,
                    MinutosProv=obj.MinutosProv,
                    TarifaEtx=obj.TarifaEtx,
                    TarifaProv=obj.TarifaProv,
                    Destino=obj.Destino,
                    ExisteTemp=obj.ExisteTemp,
                    ExisteFin=obj.ExisteFin;
            
                var cantidadEtx=minutosTemp*TarifaEtx,
                    cantidadProv=MinutosProv*TarifaProv,
                    dispute=cantidadEtx-cantidadProv;
 
            console.dir(data);
            if(ExisteTemp==null&&ExisteFin==null||ExisteTemp==""&&ExisteFin=="")
            {
               if(selecTipoDoc=='1'){
                $(".lista_FacEnv").find("tr:first").after("<tr class='vistaTemp' id='"+obj.idDoc+"'>\n\
                                                        <td id='AccountingDocumentTemp[id_carrier]'>"+idCarrierNameTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[issue_date]'>"+fechaEmisionTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[from_date]'>"+desdeFechaTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[to_date]'>"+hastaFechaTemp+"</td>\n\\n\
                                                        <td id='AccountingDocumentTemp[sent_date]'>"+fechaEnvioTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[doc_number]'>"+numDocumentoTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[minutes]'>"+minutosTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[amount]'>"+cantidadTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[id_currency]'>"+currencyTemp+"</td>\n\
                                                        <td><img class='edit' name='edit_Fac_Env' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td>\n\
                                                    </tr>");
                $('.lista_FacEnv').fadeIn('slow');
                $('.Label_F_Env').fadeIn('slow');
               }
               if(selecTipoDoc=='2'){
                $(".lista_FacRec").find("tr:first").after("<tr class='vistaTemp' id='"+obj.idDoc+"'>\n\
                                                        <td id='AccountingDocumentTemp[id_carrier]'>"+idCarrierNameTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[issue_date]'>"+fechaEmisionTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[from_date]'>"+desdeFechaTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[to_date]'>"+hastaFechaTemp+"</td>\n\\n\
                                                        <td id='AccountingDocumentTemp[sent_date]'>"+fechaEnvioTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[email_received_date]'>"+EmailfechaRecepcionTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[valid_received_date]'>"+valid_received_dateTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[email_received_hour]'>"+EmailHoraRecepcionTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[valid_received_hour]'>"+valid_received_hourTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[doc_number]'>"+numDocumentoTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[minutes]'>"+minutosTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[amount]'>"+cantidadTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[id_currency]'>"+currencyTemp+"</td>\n\
                                                        <td><img class='edit' name='edit_Fac_Rec' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td>\n\
                                                    </tr>");
                $('.lista_FacRec').fadeIn('slow');
                $('.Label_F_Rec').fadeIn('slow');
               }
            }else{
                var YaExiste = $("<div class='cargando'></div><div class='mensaje'><h4>La <b>"+selecTipoDocNameTemp+"</b><br> que intenta registrar, <br>la cual es del carrier: <b>"+idCarrierNameTemp+"</b>, <br>en el periodo: <b>"+desdeFechaTemp+"</b> / <b>"+hastaFechaTemp+"</b><br> ya se encuantra registrada bajo <br>el N°: <b>"+numDocumentoTemp+"</b></h4>Por favor revise los datos a almacenar<p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
                $("body").append(YaExiste);
                YaExiste.fadeIn('slow');
                setTimeout(function()
                { YaExiste.fadeOut('slow');
                }, 8000);
            }
               if(selecTipoDoc=='3'){
                $(".lista_Pagos").find("tr:first").after("<tr class='vistaTemp' id='"+obj.idDoc+"'>\n\
                                                        <td id='AccountingDocumentTemp[id_carrier]'>"+idCarrierNameTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[issue_date]'>"+fechaEmisionTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[doc_number]'>"+numDocumentoTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[amount]'>"+cantidadTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[id_currency]'>"+currencyTemp+"</td>\n\
                                                        <td><img class='edit' name='edit_Pagos' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td>\n\
                                                    </tr>");
                $('.lista_Pagos').css("background", "rgba(226, 168, 140, 1)").fadeIn('slow');
                $('.LabelPagos').fadeIn('slow');
               }
               if(selecTipoDoc=='4'){
                $(".lista_Cobros").find("tr:first").after("<tr class='vistaTemp' id='"+obj.idDoc+"'>\n\
                                                        <td id='AccountingDocumentTemp[id_carrier]'>"+idCarrierNameTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[valid_received_date]'>"+valid_received_dateTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[doc_number]'>"+numDocumentoTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[amount]'>"+cantidadTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[id_currency]'>"+currencyTemp+"</td>\n\
                                                        <td><img class='edit' name='edit_Cobros' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td>\n\
                                                    </tr>");
                $('.lista_Cobros').fadeIn('slow');
                $('.LabelCobros').fadeIn('slow');
               }
               if(selecTipoDoc=='5'){
                 $(".lista_DispRec").find("tr:first").after("<tr class='vistaTemp' id='"+obj.idDoc+"'>\n\
                                                        <td id='AccountingDocumentTemp[id_carrier]'>"+idCarrierNameTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[id_destination]'>"+Destino+"</td>\n\
                                                        <td id='AccountingDocumentTemp[id_accounting_document]'>"+numDocumentoTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[min_etx]'>"+minutosTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[min_carrier]'>"+MinutosProv+"</td>\n\
                                                        <td id='AccountingDocumentTemp[rate_etx]'>"+TarifaEtx+"</td>\n\
                                                        <td id='AccountingDocumentTemp[rate_carrier]'>"+TarifaProv+"</td>\n\
                                                        <td id='AccountingDocumentTemp[amount_etx]'>"+cantidadEtx+"</td>\n\
                                                        <td id='AccountingDocumentTemp[amount]'>"+cantidadProv+"</td>\n\
                                                        <td id='AccountingDocumentTemp[dispute]'>"+dispute.toFixed(2)+"</td>\n\
                                                        <td><img class='edit' name='edit_DispRec' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td>\n\
                                                    </tr>");
                $('.lista_DispRec').fadeIn('slow');
                $('.Label_DispRec').fadeIn('slow');
               }
                if(selecTipoDoc=='6'){
                 $(".lista_DispEnv").find("tr:first").after("<tr class='vistaTemp' id='"+obj.idDoc+"'>\n\
                                                        <td id='AccountingDocumentTemp[id_carrier]'>"+idCarrierNameTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[id_destination]'>"+Destino+"</td>\n\
                                                        <td id='AccountingDocumentTemp[id_accounting_document]'>"+numDocumentoTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[min_etx]'>"+minutosTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[min_carrier]'>"+MinutosProv+"</td>\n\
                                                        <td id='AccountingDocumentTemp[rate_etx]'>"+TarifaEtx+"</td>\n\
                                                        <td id='AccountingDocumentTemp[rate_carrier]'>"+TarifaProv+"</td>\n\
                                                        <td id='AccountingDocumentTemp[amount_etx]'>"+cantidadEtx+"</td>\n\
                                                        <td id='AccountingDocumentTemp[amount]'>"+cantidadProv+"</td>\n\
                                                        <td id='AccountingDocumentTemp[dispute]'>"+dispute.toFixed(2)+"</td>\n\
                                                        <td><img class='edit' name='edit_DispRec' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td>\n\
                                                    </tr>");
                $('.lista_DispEnv').fadeIn('slow');
                $('.Label_DispEnv').fadeIn('slow');
               }
                if(selecTipoDoc=='7'){
                    console.log(data);
                  $(".lista_NotCredEnv").find("tr:first").after("<tr class='vistaTemp' id='"+obj.idDoc+"'>\n\
                                                        <td id='AccountingDocumentTemp[id_destination]'>"+Destino+"</td>\n\
                                                        <td id='AccountingDocumentTemp[id_accounting_document]'>"+numDocumentoTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[min_etx]'>"+minutosTemp+"</td>\n\
                                                        <td id='AccountingDocumentTemp[min_carrier]'>"+MinutosProv+"</td>\n\
                                                        <td id='AccountingDocumentTemp[rate_etx]'>"+TarifaEtx+"</td>\n\
                                                        <td id='AccountingDocumentTemp[rate_carrier]'>"+TarifaProv+"</td>\n\
                                                        <td id='AccountingDocumentTemp[amount_etx]'>"+cantidadEtx+"</td>\n\
                                                        <td id='AccountingDocumentTemp[amount]'>"+cantidadProv+"</td>\n\
                                                        <td id='AccountingDocumentTemp[dispute]'>"+dispute+"</td>\n\
                                                        <td id='AccountingDocumentTemp[monto_nota]'><input id='montoNota'value=''></td>\n\
                                                        <td><img class='edit' name='edit_Fac_Rec' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td>\n\
                                                    </tr>");
                $('.lista_NotCredEnv').fadeIn('slow');
                $('.Label_NotCredEnv').fadeIn('slow');
               }
                $('#botAgregarDatosContableFinal').fadeIn('slow');
                
                $SORI.UI.init();
                $("#AccountingDocumentTemp_email_received_hour, #AccountingDocumentTemp_note, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_minutes, #AccountingDocumentTemp_id_destination_supplier, #AccountingDocumentTemp_minutes, #AccountingDocumentTemp_MinutosProvee, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_MontoProvee, #AccountingDocumentTemp_id_destination").val('');
                if (selecTipoDoc=='3'||selecTipoDoc=='4'){
                     $("#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_issue_date").val('');
                } 
                if (selecTipoDoc=='5'||selecTipoDoc=='6'){
                     $("#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date").val('');
                } 
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
                           $('.tablaVistDocTemporales, #botAgregarDatosContableFinal, .Label_F_Env, .Label_F_Rec, .LabelPagos, .LabelCobros, .Label_DispRec, .lista_DispEnv').fadeOut('fast');
                           var obj = JSON.parse(data),
                           exito=$('.mensaje').html("<h4>Se almacenaron <b> "+obj.length+"</b>  documentos contables de forma definitiva</h4><img src='/images/si.png'width='95px' height='95px'/>").hide().fadeIn('fast');
                            setTimeout(function()
                            {
                                exito.fadeOut('fast');
                                revisa.fadeOut('fast');
                            }, 4000);

                         }  
                    });
                }else{
                     revisa.fadeOut('fast');
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
                        var stop= $("<div class='cargando'></div><div class='mensaje'><h3>No ha seleccionado ninguna factura para confirmar</h3><p><p><p><p><p><p><p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();   
                         $("body").append(stop);
                        stop.fadeIn('fast');
                        setTimeout(function()
                        { stop.fadeOut('fast');
                        }, 1000);
        }else{
             var revisa=$("<div class='cargando'></div><div class='mensaje'><h4>Esta a punto de confirmar las siguientes facturas enviadas<p></h4><p><p>Si los datos son correctos, presione Aceptar, de lo contrario Cancelar<p><p><div id='cancelar'class='cancelar'><p><label><b>Cancelar</b></label></div>&nbsp;<div id='confirma' class='confirma'><p><label><b>Aceptar</b></label></div></div>").hide();           
            $("body").append(revisa);
            revisa.fadeIn('fast'); 

            $('#confirma,#cancelar').on('click',function()
             {
                 var tipo=$(this).attr('id');
                 if(tipo=="confirma")
                 {             
                    var array= $("input[type=checkbox]:checked").each(function(){
                    var id=($(this).val()),
                    paraBorrar=$('#'+id);
                    
                    $.ajax({ 
                            type: "GET",
                            url: "../AccountingDocument/Confirmar/"+id,
                            success: function(data) 
                            {
      
                               paraBorrar.empty(); 
                               revisa=null;
                            }
                    });
                  });
                  var cuantos=array.length,
                  id=($("input[type=checkbox]:checked").val());
                  if (cuantos >0){
                     if(id=='on'){
                         cuantos=array.length-1;
                     }
                      var exito=$('.mensaje').html(" <h4>Se confirmaron <b>"+cuantos+"</b> facturas enviadas</h4><img src='/images/si.png'width='95px' height='95px'/>").hide().fadeIn('fast');
                                         setTimeout(function()
                                         { exito.fadeOut('fast');
                                             $('.cargando').fadeOut('fast');
                                         }, 3000);   
                 }
                }else{
                    revisa.fadeOut('slow'); 
                    revisa=null;
                 }
             });   
        }      
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

//**
//modulo de colores por zona geografica
//*

$(".seleColor").on("click",function()
{
    var paleta=$('.paletaColores');
    paleta.toggle("slow");
});
$( "button" ).click(function() {
    var paleta=$('.paletaColores');
    var text = $( this ).attr('id');
    $( "input#GeographicZone_color_zona" ).val( text );
    var color=("#"+text+"");
    $( "input#GeographicZone_color_zona" ).css( "background-color",color ).css( "opacity", '0.2' );
    paleta.hide("slow");
});

$('#GeographicZone_acciones').change(function()
{
    var acciones=$('#GeographicZone_acciones').val();
    if (acciones==1){
        $('div.valoresDocumento').slideDown('slow');
        $('input#GeographicZone_name_zona').slideDown('slow');
        $('select#GeographicZone_name_zona').hide('slow');
        $('.acciones').html('Acciones');
    }
    if (acciones==2){
        $('div.valoresDocumento').slideDown('slow');
        $('select#GeographicZone_name_zona').slideDown('slow');
        $('input#GeographicZone_name_zona').hide('slow');
        $('.acciones').html('Acciones');
    }
    
});

$('select#GeographicZone_name_zona').change(function()
{
     var id_zonaSelect= $( "select#GeographicZone_name_zona" ).val();
     $.ajax({
            type: "GET",
            url: "buscaColor",
            data: "&id_zonaSelect="+id_zonaSelect,

            success: function(data) 
            {
               $( "input#GeographicZone_color_zona" ).val( data );
               var color=("#"+data+"");
               $( "input#GeographicZone_color_zona" ).css( "background-color",color ).css( "opacity", '0.2' ); 
            }
     });
});

$('.botGuardarZonaColor').click('on',function(e)
{
    e.preventDefault();
    var acciones=$('#GeographicZone_acciones').val(),
    name_zona=$('input#GeographicZone_name_zona').val(),
    name_zonaSelect=$('select#GeographicZone_name_zona').val(),
    color_zona=$('#GeographicZone_color_zona').val();

        if(acciones=='' || color_zona=='')
    {  
        var msjIndicador = $("<div class='cargando'></div><div class='mensaje'><h3>Faltan datos por agregar</h3><p><p><p><p><p><p><p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
                    $("body").append(msjIndicador);
                    msjIndicador.fadeIn('fast');
                        setTimeout(function()
                        {msjIndicador.fadeOut('fast');
                        }, 1000);
    }else{
                if (acciones==1){
                    var Action="GuardarZoneColor";
                }if(acciones==2){
                    Action="UpdateZoneColor";
                }
        $.ajax({
            type: "GET",
            url: Action,
            data: "&name_zona="+name_zona+"&color_zona="+color_zona+"&name_zonaSelect="+name_zonaSelect,

              success: function(data) 
                      {
                obj = JSON.parse(data);
                var name_zonaSave=obj.name_zonaG, 
                color_zonaSave=obj.color_zonaG;

                  var msjIndicador = $("<div class='cargando'></div><div class='mensaje'><h3>La zona geografica <p><b>"+name_zonaSave+"</b></h3><p>Fue guardada con exito y se le asigno el color<div style='background-color: #"+color_zonaSave+";width:25%;margin-left: 36%;'>"+color_zonaSave+"</div><p><p><p><p><p><img src='/images/si.png'width='95px' height='95px'/></div>").hide();
                        $("body").append(msjIndicador);
                        msjIndicador.fadeIn('fast');
                            setTimeout(function()
                            {msjIndicador.fadeOut('fast');
                            }, 3000);
            $("select#GeographicZone_acciones").val('');
            $("input#GeographicZone_name_zona").val('');
            $( "select#GeographicZone_name_zona" ).val('');
            $("#GeographicZone_color_zona").val('');
            $( "input#GeographicZone_color_zona" ).css( "background-color","white" ).css( "opacity", '1' );
                      }
        });
    }
});
//**
//fin modulo de colores por zona geografica
//**

//**
//modulo de grupo carriers
//**

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
    var grupo=$('#Carrier_id').val(),
    asignados=$('#select_left').val(),
    noasignados=$('#select_right').val();
    if(grupo<1)
    {
        var aguanta=$("<div class='cargando'></div><div class='mensaje'><h3>No ha seleccionado ningun grupo</h3><p><p><p><p><p><p><p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
        $("body").append(aguanta)
            aguanta.fadeIn('fast');
            setTimeout(function()
            {
                aguanta.fadeOut('fast');
            }, 3000);$("#carriers select option").prop("selected",false);
    }else 
    {
        $.ajax({
            type: "GET",
            url: "../Carrier/BuscaNombres",
            data: "asignados="+asignados+"&noasignados="+noasignados+"&grupo="+grupo,
            success: function(data)
            {
                obj = JSON.parse(data);
                        var grupoName=obj.grupo, 
                        asigname=obj.asignados,
                        noasigname=obj.noasignados;
                      
                if(asigname<="1" && noasigname<="1")
                     {
                        var nohaynada=$("<div class='cargando'></div><div class='mensaje'><h3>No hay carriers preselccionados <br> para asignar o \n\
                                                     desasignar</h3><p><p><p><p><p><p><p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
                        $("body").append(nohaynada);
                            nohaynada.fadeIn('fast');
                            setTimeout(function()
                            {
                                nohaynada.fadeOut('fast');
                            }, 4000);$("#carriers select option").prop("selected",false);
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
                        var revisa=$("<div class='cargando'></div><div class='mensaje'><h4>Esta a punto de realizar los siguientes cambios en el grupo\n\
                                      <br><b>"+grupoName+"</b></h4>\n\<p><h6>"+asig+"<p>"+asigname+"</h6><p><p><h6>"+desA+"<p>\n\
                                      "+noasigname+"</h6><p>Si los datos son correctos, presione Aceptar, de lo contrario Cancelar<p><p><p><p><p><p><p><div id='cancelar'\n\
                                      class='cancelar'><p><label><b>Cancelar</b></label></div>&nbsp;<div id='confirma' class='confirma'><p><label><b>Aceptar</b></label></div></div>").hide();
                        $(".cargando").hide();
                        $(".mensaje").hide();
                        $("body").append(revisa);
                            revisa.fadeIn('fast');

                $('#confirma,#cancelar').on('click', function()
                {
                    var tipo=$(this).attr('id');
                    if(tipo=="confirma"&& grupo!="")
                    {
                        $.ajax({
                            type: "GET",
                            url: "../Carrier/SaveCarrierGroup",
                            data:   '&grupo='+grupo+'&asignados='+asignados+'&noasignados='+noasignados,
                            success: function(data)
                            {
                                obj = JSON.parse(data);
                                var grupoSave=obj.grupo;

                                        if(asigname=="")
                                        {
                                            var pudo="Le fue";
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
                                            var aguanta=$('.mensaje').html("<h3>El grupo <b>" + grupoSave + "</b><p>Fue modificado con exito</h3><br><h5>" + pudo + "<br><b>" + asigname + "</b><p>" + nopudo + "<br><b>" + noasigname + "</b></h5><p><p><img src='/images/si.png'width='95px' height='95px'/>").hide().fadeIn('fast');
                                            setTimeout(function()
                                            {
                                                aguanta.fadeOut('fast');
                                                $('.cargando').fadeOut('fast');
                                            }, 4000);
                                                $('#Carrier_id').val('');
                                                $("#carriers select option").prop("selected",false);
                            }
                        });
                    }else{
                       revisa.fadeOut();
                    }
                });
              }
            }
      });
   }
});
//**
//fin modulo de grupo carriers
//**
//**
//agregar managers
//**

$(".G_ManagerNuevo").on( "click",function(e)
{
    e.preventDefault();
    alert('hola');
     var name=$('#Managers_name').val(),
     lastname=$('#Managers_lastname').val(),
     record_date=$('#Managers_record_date').val(),
     position=$('#Managers_position').val(),
     address=$('#Managers_address').val();
     
        $.ajax({
            type: "GET",
            url: "../Managers/GuardarManager",
            data:   'name='+name+'&lastname='+lastname+'&record_date='+record_date+'&address='+address+'&position='+position,
            success: function(data)
            {
                        var obj = JSON.parse(data),
                        nameSave=obj.nameSave, 
                        lastnameSave=obj.lastnameSave,
                        addressSave=obj.addressSave,
                        record_dateSave=obj.record_dateSave,
                        positionSave=obj.positionSave;

                        var msjIndicador = $("<div class='cargando'></div><div class='mensaje'><h3>El Manager <p><b>"+nameSave+" "+lastnameSave+"</b></h3><p>Fue almacenado con exito con los siguientes datos:\n\
                                            <p><h5>Fecha de Ingreso: <b>"+record_dateSave+"</b><p>Posición: <b>"+positionSave+"</b><p>Dirección: <b>"+addressSave+"</b></h5><p><img src='/images/si.png'width='95px' height='95px'/></div>").hide();
                                $("body").append(msjIndicador);
                                msjIndicador.fadeIn('fast');
                                    setTimeout(function()
                                    {msjIndicador.fadeOut('fast');
                                    }, 4000);
                
                 $('#Managers_name').val('');
                 $('#Managers_lastname').val('');
                 $('#Managers_record_date').val('');
                 $('#Managers_position').val('');
                 $('#Managers_address').val('');
            
            }
        });
    
});

//**
//fin agregar managers
//**

//**
//check marcar 
//**
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
//**
//fin check marcar
//**
  
  
//                            <label class="doc_number">Numero de Documento</label>
//
//    <div class="contratoForm DestinoDisp">
//    <label class="DestDisp">Destino</label>
//        
//    <div class="contratoForm minutosDoc">
//    <label class="MinutosE">Minutos</label>
//    
//    <div class="contratoForm minutosDocProveedor">
//    
//    <label class="MinutosP">Minutos Proveedor</label>
//    <input id="AccountingDocumentTemp_MinutosProvee"type="text" name="AccountingDocumentTemp[MinutosProvee]">
//    
//    <div class="contratoForm montoDoc">
//    <label class="MontoE">Monto</label>
//    
//    <div class="contratoForm montoDocProveedor">
//    <label class="MontoP">Tarifa Proveedor</label>
//    <input id="AccountingDocumentTemp_MontoProvee"type="text" name="AccountingDocumentTemp[MontoProvee]">
//    
//    
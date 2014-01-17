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
            $("div.diario").fadeIn("slow").css({
                'display':'block'
            });
            $("div.horas").fadeOut("slow");
            $("div.rerate").fadeOut("slow");
            console.log(msj.acumulador);
            if(msj.acumulador>=2)
            {
                
                //$('input[type="file"], input[type="submit"]').attr('disabled','disabled');
                $('input[type="file"], input[type="submit"]').filter(function(){return $(this).attr('name')!='grabartemp'}).attr('disabled','disabled');
            }
            else
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
                var carriers=data.split("/"),
                managerName=carriers[0].split(","),
                asigname=carriers[2].split(","),
                noasigname=carriers[1].split(",");
                                  
                if(asigname<="1" && noasigname<="1")
                {
                    $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>No hay carriers preselccionados <br> para asignar o desasignar</h3>","aguanta.png","1500","width:40px; height:90px;"); 
                }
                else
                {
                    if(asigname=="")  var asig="";
                      else            var asig='Asignarle: ';
                    
                    if(noasigname=="")var desA="";
                      else            var desA='Dsasignarle:';
                    
                    $SORI.UI.msj_confirm("Esta a punto de realizar los siguientes cambios en la Distribucion Comercial para el manager: <br><b>"+managerName+"</b></h4><p><h6>"+asig+"<p>"+asigname+"</h6><p><h6>"+desA+"<p>"+noasigname+"</h6>");
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

                                if(asigname=="")  var pudo="";
                                  else            var pudo='Le fue asignado:';
                                
                                if(noasigname=="")var nopudo="";
                                  else            var nopudo='Desasignado:';
                                $SORI.UI.msj_change("<h5>Al manager <b>" + managerName + "</b><br>" + pudo + "<br><b>" + asigname + "</b></h5><p><h5>" + nopudo + "<br><b>" + noasigname + "</b></h5>","si.png","2000","width:95px; height:95px;"); 
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
$SORI.UI.formChange('Contrato_id_carrier');
//esto tengo que modularlo....
$("#Contrato_id_termino_pago,#Contrato_id_termino_pago_supplier").change(function()
{
    var inputs=$(".divide_fact,.periodo_fact"),
    fac_period="Contrato_id_fact_period";
      if($(this).attr('id')=="Contrato_id_termino_pago") {$("#Contrato_id_termino_pago_supplier").val($("#Contrato_id_termino_pago").val());}
      
    var tp=$("#Contrato_id_termino_pago_supplier").val();
    
      if(tp=="1"||tp=="3"||tp=="4"||tp=="5"||tp=="6"||tp=="7"||tp=="8"||tp=="12")
      {
          inputs.css("display","inline-block").hide().show("slow");
          
            if(tp=="1"||tp=="3"||tp=="4"||tp=="5") 
            {
                $(".dia_ini_fact").css("display","inline-block").hide().show("slow");
                $("#"+fac_period+" option[value='3'],#"+fac_period+" option[value='4']").hide("fast");
                $("#"+fac_period+" option[value='1'],#"+fac_period+" option[value='2']").show("fast");
            }else{
                  $(".dia_ini_fact").hide().hide("slow");
                  $("#"+fac_period+" option[value='3'],#"+fac_period+" option[value='4']").show("fast");
                  $("#"+fac_period+" option[value='1'],#"+fac_period+" option[value='2']").hide("fast");
            }
      }else{
            $("#dia_ini_fact,#divide_fact,#Contrato_id_fact_period").val(""); inputs.hide("slow");$(".dia_ini_fact").hide("slow");
      }
});
//calma calma calma .)

$('#botAsignarContrato').click('on',function(e)
{
    e.preventDefault();
    $("#Contrato_id_company,#Contrato_end_date,#Contrato_sign_date").prop("disabled", false);
    var carrier = $("#Contrato_id_carrier").val(),
    company = $("#Contrato_id_company").val(),
    termino_pago = $("#Contrato_id_termino_pago").val(),
    termino_pago_supplier = $("#Contrato_id_termino_pago_supplier").val(),
    divide_fact = $("#divide_fact").val(),
    fact_period = $("#Contrato_id_fact_period").val(),
    dia_ini_fact = $("#dia_ini_fact").val(),
    monetizable = $("#Contrato_id_monetizable").val(),
    Contrato_up = $("#Contrato_up").val(),
    Contrato_status = $("#Contrato_status").val(),
    diasDisputaOculto = $("#dias_disputa_Oculto").val(),
    diasDisputaSolvedOculto = $("#dias_disputa_solved_Oculto").val(),
    F_Firma_Contrato_Oculto = $("#F_Firma_Contrato_Oculto").val(),
    F_P_produccion_Oculto = $("#F_P_produccion_Oculto").val(),
    monetizableOculto = $("#monetizable_Oculto").val(),      
    TPOculto = $("#TerminoP_Oculto").val(),
    TP_supplier_Oculto = $("#TerminoP_supplier_Oculto").val(),
    
    divide_fact_Oculto = $("#divide_fact_Oculto").val(),
    fact_period_Oculto = $("#Contrato_id_fact_period_Oculto").val(),
    dia_ini_fact_Oculto = $("#dia_ini_fact_Oculto").val(),
    
    Contrato_upOculto = $("#Contrato_upOculto").val(),
    Contrato_statusOculto = $("#Contrato_statusOculto").val();
    
    var dias_disputa=$("#Contrato_id_disputa").val(),
    dias_disputa_solved=$("#Contrato_id_disputa_solved").val(),
    credito=$("#Contrato_id_limite_credito").val(),
    compra=$("#Contrato_id_limite_compra").val();
   
    var valid_input=$SORI.UI.seleccionaCampos('general'),
    valid_tp_supp=$SORI.UI.seleccionaCampos('tp_supplier'); 
      
    if(valid_input==1 && valid_tp_supp==1)
    {
        $.ajax({
            type: "GET",
            url: "ContratoConfirma",
            data: "id_carrier="+carrier+"&id_company="+company+"&id_monetizable="+monetizable+"&Contrato_up="+Contrato_up+"&Contrato_status="+Contrato_status+"&id_M_Oculto="+monetizableOculto+"&id_termino_pago="+termino_pago+"&id_TP_Oculto="+TPOculto+"&termino_pago_supplier="+termino_pago_supplier+"&TP_supplier_Oculto="+TP_supplier_Oculto+"&fact_period="+fact_period+"&fact_period_Oculto="+fact_period_Oculto+"&divide_fact="+divide_fact+"&divide_fact_Oculto="+divide_fact_Oculto+"&dia_ini_fact="+dia_ini_fact+"&dia_ini_fact_Oculto="+dia_ini_fact_Oculto,
            success: function(data) 
            {
                var obj=JSON.parse(data),
                carrierName=obj.carrierName,
                companyName=obj.companyName,
                termino_pName=obj.termino_pName,
                termino_p_supp_Name=obj.termino_p_supp_Name,
                monetizableName=obj.monetizableName,
                Contrato_upC=obj.Contrato_upConfirma,
                Contrato_StatusC=obj.Contrato_statusConfirma,
                sign_date = $("#Contrato_sign_date").val(),
                production_date = $("#Contrato_production_date").val(),
                end_date=$("#Contrato_end_date").val(),
                monetizableNameO=obj.monetizableNameO,
                termino_pNameO=obj.termino_pNameO,
                termino_p_supp_NameO=obj.termino_p_supp_NameO,
                creditoO = $("#credito_Oculto").val(),
                compraO = $("#compra_Oculto").val();

                if(TPOculto==false && monetizableOculto==false)
                {
                    var guardoEdito=" Se guardo con exito el Contrato";
                    $SORI.UI.msj_confirm("<h4>Esta a punto de crear un nuevo Contrato: \n\
                                          <br><b>( "+carrierName+" / "+companyName+" )</b></h4><p>Con las siguientes condiciones comerciales:\n\
                                          <h6><p>Status del Carrier: "+Contrato_StatusC+"</p>\n\
                                          <p>Termino de pago Prov: "+termino_pName+"</p>\n\
                                          <p>Termino de pago Client: "+termino_p_supp_Name+"</p>\n\
                                          <p>Monetizable: "+monetizableName+"</p>\n\
                                          <p>Dias max para disputar: "+dias_disputa+"</p>\n\
                                          <p>Limite de Credito: "+credito+"</p>\n\
                                          <p>Dias para solventar disputas: "+dias_disputa_solved+"</p>\n\
                                          <p>Limite de Compra: "+compra+"</p>\n\
                                          <p>Unidad de producción: "+Contrato_upC+"</p>\n\
                                          <p>Fecha de Firma de contrato: "+sign_date+"</p>\n\
                                          <p>Fecha de puesta en produccion: "+production_date+"</p></h6>");
                    $SORI.UI.changeCss(".mensaje","top","2%");
                }
                else
                {   guardoEdito=" Se realizaron los siguientes cambios en el Contrato";  
                   
                    if(Contrato_upOculto==''){     
                        backUP="Unidad de producción: ";  
                    }else{  if(Contrato_upOculto != Contrato_up){
                                if (Contrato_upOculto==0)          Contrato_upOculto='Ventas';
                                else if(Contrato_upOculto==1)      Contrato_upOculto='Presidencia';
                                var backUP="Unidad de producción de: "+Contrato_upOculto+" a ";}
                              else backUP="Unidad de producción: ";}
                          
                    if(Contrato_statusOculto==''){ 
                        backStatus="Status del Carrier: ";  
                    }else{  if(Contrato_statusOculto != Contrato_status){
                                if (Contrato_statusOculto==0)      Contrato_statusOculto='Inactivo';
                                else if(Contrato_statusOculto==1)  Contrato_statusOculto='Activo';
                                var backStatus="Status del Carrier: "+Contrato_statusOculto+" a ";}
                              else backStatus="Status del Carrier: ";}
                          
                  if(end_date!="") var advertencia=" <h4>Esta a punto de finalizar el Contrato<br><b>("+carrierName+" / "+companyName+")</b></h4>";
                     else  advertencia="<h4>Esta a punto de realizar los siguientes cambios en el Contrato :<br><b>("+carrierName+" / "+companyName+")</b></h4>\n\<h6><p>\n\
                                        "+backStatus+ "<span class='status'>"+Contrato_StatusC+"</span><p>\n\
                                        "+$SORI.UI.resultadoContrato(TPOculto,termino_pago,"Terminos de pago client de: "+termino_pNameO+" a ","Terminos de pago client: ")+ "<span class='termino_pName'>"+termino_pName+"</span><p>\n\
                                        "+$SORI.UI.resultadoContrato(TP_supplier_Oculto,termino_pago_supplier,"Terminos de pago prov de: "+termino_p_supp_NameO+" a ","Terminos de pago prov: ")+ "<span class='termino_pago_supplier'>"+termino_p_supp_Name+"</span><p>\n\
                                        "+$SORI.UI.resultadoContrato(monetizableOculto,monetizable,"Monetizable de: "+monetizableNameO+" a ","Monetizable: ")+" <span class='monetizableName'>"+monetizableName+"</span><p>\n\
                                        "+$SORI.UI.resultadoContrato(diasDisputaOculto,dias_disputa,"Dias max para disputar de: "+diasDisputaOculto+" a ","Dias max para disputar: ")+" <span class='dias_disputa'>"+dias_disputa+"</span><p>\n\
                                        "+$SORI.UI.resultadoContrato(diasDisputaSolvedOculto,dias_disputa_solved,"Dias para solventar disputas de: "+diasDisputaSolvedOculto+" a ","Dias para solventar disputas: ")+" <span class='dias_disputa_solved'>"+dias_disputa_solved+"</span><p>\n\
                                        "+$SORI.UI.resultadoContrato(creditoO,credito,"Limite de Credito de: "+creditoO+" a ","Limite de Credito: ")+" <span class='credito'>"+credito+"</span><p>\n\
                                        "+$SORI.UI.resultadoContrato(compraO,compra,"Limite de Compra de: "+compraO+" a ","Limite de Compra: ")+" <span class='compra'>"+compra+"</span><p>\n\
                                        "+backUP+" <span class='Contrato_upC'>"+Contrato_upC+"</span><p>\n\
                                        "+$SORI.UI.resultadoContrato(F_Firma_Contrato_Oculto,sign_date,"Fecha de firma de contrato de: "+F_Firma_Contrato_Oculto+" a ","Fecha de firma de contrato: ")+" <span class='sign_date'>"+sign_date+"</span><p> \n\
                                        "+$SORI.UI.resultadoContrato(F_P_produccion_Oculto,production_date,"Fecha de Puesta en Produccion de: "+F_P_produccion_Oculto+" a ","Fecha de Puesta en Produccion: ")+" <span class='production_date'>"+production_date+"</span><p></h6>";
                    
                    $SORI.UI.msj_confirm(advertencia);
                    $SORI.UI.changeCss(".mensaje","top","2%");
                    $SORI.UI.casosParaMsjConfirmContrato(TP_supplier_Oculto,termino_pago_supplier,diasDisputaOculto,dias_disputa,diasDisputaSolvedOculto,dias_disputa_solved,F_Firma_Contrato_Oculto,sign_date,F_P_produccion_Oculto,production_date,TPOculto,termino_pago,monetizableOculto,monetizable,creditoO,credito,compraO,compra,Contrato_upOculto,Contrato_up,Contrato_statusOculto,Contrato_status);//esta function sedebe modular, espero poder hacerlo esta semana, junto con todo lo demas 
                }
                $('#confirma,#cancelar').on('click',function()
                {
                    var tipo=$(this).attr('id');
                    if(tipo=="confirma")
                    {  
                        $.ajax({
                            type: "GET",
                            url: "Contrato",
                            data: "sign_date="+sign_date+"&production_date="+production_date+"&end_date="+end_date+"&id_carrier="+carrier+"&id_company="+company+"&id_termino_pago="+termino_pago+"&termino_pago_supplier="+termino_pago_supplier+"&id_monetizable="+monetizable+"&dias_disputa="+dias_disputa+"&dias_disputa_solved="+dias_disputa_solved+"&credito="+credito+"&compra="+compra+"&Contrato_up="+Contrato_up+"&Contrato_status="+Contrato_status+"&fact_period="+fact_period+"&divide_fact="+divide_fact+"&dia_ini_fact="+dia_ini_fact,
                            success: function(data) 
                            {  
                                if(end_date!="")$SORI.UI.msj_change("<h4>El Contrato: <br><b>("+carrierName+" / "+companyName+")</b></h4><h6><p>Fue Finalizado con exito en la fecha: "+end_date+"</h6>","si.png","1000","width:90px; height:90px;");
                                  else          $SORI.UI.msj_change("<h4>"+guardoEdito+": <br><b>("+carrierName+" / "+companyName+")</b></h4><h6><p>Status del Carrier: "+Contrato_StatusC+"<p>Terminos Pago client:"+termino_pName+"<p>Termino pago prov:"+termino_p_supp_Name+"<p>Monetizable: "+monetizableName+"<p>Dias max para disputar:"+dias_disputa+"<p>Dias para solventar disputas:"+dias_disputa_solved+"<p>Limite de Credito:"+credito+"<p>Limite de Compra:"+compra+"<p>Unidad de producción: "+Contrato_upC+"<p>Fecha de firma de contrato: "+sign_date+"<p>Fecha de puesta en Produccion:"+production_date+"<p>"+end_date+"<p><p></h6>","si.png","1000","width:90px; height:90px;");
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
                var obj = JSON.parse(data),
                GeographicZoneName = (obj.GeographicZoneName), AsigNames = (obj.asigNames),  NoAsigNames = (obj.noasigNames);
                if(AsigNames<1&&NoAsigNames<1)
                {
                    $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>No hay datos que cambiar</h3>","aguanta.png","1500","width:40px; height:90px;"); 
                }
                else
                {
                    if(AsigNames=="")   var asig="";
                      else              var asig='Asignarle: ';
                    if(NoAsigNames=="") var desA="";
                      else              var desA = 'Dsasignarle:';
                      
                    $SORI.UI.msj_confirm("<h4>Esta a punto de actualizar "+tipoDestino+" la Zona Geográfica: <br><b> "+GeographicZoneName+"</h4><h4></b>"+asig+"</h4><p><h6><p>"+AsigNames+"</h6><p><br><h4>"+desA+"</h4><p><h6><p><p>"+NoAsigNames+"</h6>");
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
                                     $SORI.UI.msj_change("<h4>Se han actualizado los "+tipoDestino+" con la Zona Geográfica: <br><b> "+GeographicZoneName+"</h4><h4></b>"+asig+"</h4>\n\<p> <h6><p>"+AsigNames+"</h6><p><br><h4>"+desA+"</h4><p><h6>"+NoAsigNames+"</h6>","si.png","3000","width:95px; height:95px;");      
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
    var tipoDocument= $('#AccountingDocumentTemp_id_type_accounting_document').val();
    $SORI.UI.changeCss('#AccountingDocumentTemp_id_accounting_document','color','#777');
    $SORI.UI.elijeOpciones(tipoDocument);   
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

$('div.hacerUnaNota').click('on',function()
{
    $('div.hacerUnaNota').toggle('fast');
    $('div.quitaNota').toggle('fast');
    $('div.contratoFormTextArea').toggle('slow');
});
$('div.quitaNota').click('on',function()
{
    $('div.hacerUnaNota').toggle('slow');
    $('div.quitaNota').toggle('slow');
    $('div.contratoFormTextArea').toggle('slow');
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
            {console.log(data);
                obj = JSON.parse(data);
                if((obj.valid==1)){
                    $SORI.UTILS.updateMontoAprobadoDisp();
                    $SORI.UI.llenarTabla(obj);
                    $SORI.UI.init();
                    $SORI.UI.emptyFields();
                }else if((obj.valid==0)){
                    $SORI.UI.MensajeYaExiste(obj);
                }else{
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
                    $('.tablaVistDocTemporales, #botAgregarDatosContableFinal, .Label_F_Env, .Label_F_Rec, .LabelPagos, .LabelCobros, .Label_DispRec, .Label_DispEnv,.Label_NotCredEnv,.Label_NotCredRec,.botonesParaExportar').fadeOut('fast');
                    $('.vistaTemp').remove();
                    $SORI.UI.msj_change("<h4>Se almacenaron <b> "+data+"</b>  documentos contables de forma definitiva</h4>","si.png","1000","width:95px; height:95px;"); 
                }  
            });
        }else{
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
        $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>No ha seleccionado ninguna factura para confirmar</h3>","aguanta.png","1000","width:40px; height:90px;"); 
    }else{
        $SORI.UI.msj_confirm("<h4>Esta a punto de confirmar las siguientes facturas enviadas</h4>");  
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
                        }
                    });
                });
                var cuantos=array.length,
                id=($("input[type=checkbox]:checked").val());
                if (cuantos >0){
                    if(id=='on'){
                        cuantos=array.length-1;
                    }
                    $SORI.UI.msj_change("<h4>Se confirmaron <b>"+cuantos+"</b> facturas enviadas</h4>","si.png","2000","width:95px; height:95px;");   
                }
            }else{
                $(".cargando, .mensaje").fadeOut('slow'); 
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

//**
//modulo de colores por zona geografica
//*
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

$('input#color_zona').change(function()
{
   $("#color_zona_hidden").val($(this).val());
   $("#color_zona").css("background",$(this).val());
});

$('select#GeographicZone_name_zona').change(function()
{
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
});

$('.botGuardarZonaColor').click('on',function(e)
{
    e.preventDefault();
    var acciones=$('#GeographicZone_acciones').val(), color=$("input#color_zona_hidden").val();
    if(acciones=='' || color=='')
    {  
        $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>Faltan datos por agregar</h3>","aguanta.png","1000","width:40px; height:90px;"); 
    }else
        {
            if (acciones==1){var Action="GuardarZoneColor";}
            if(acciones==2){ Action="UpdateZoneColor";}
            $.ajax({
                type: "GET",
                url: Action,
                data: "name_zona="+$('input#GeographicZone_name_zona').val()+"&color_zona="+color.replace('#','')+"&name_zonaSelect="+$('select#GeographicZone_name_zona').val(),
    
                success: function(data) 
                {
                    console.log(data);
                    obj = JSON.parse(data);
                    var name_zonaSave=obj.name_zonaG, 
                    color_zonaSave=obj.color_zonaG;
                    $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>La zona geografica <p><b>"+name_zonaSave+"</b></h3><p>Fue guardada con exito y se le asigno el color<div style='background-color: #"+color_zonaSave+";width:25%;margin-left: 36%;'>"+color_zonaSave+"</div>","si.png","3000","width:95px; height:95px;"); 
                    $("select#GeographicZone_acciones,input#GeographicZone_name_zona,select#GeographicZone_name_zona,input#color_zona_hidden").val('');
                    $( "input#color_zona" ).css( "background-color","white" );
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
    var grupo=$('#Carrier_id').val(), asignados=$('#select_left').val(), noasignados=$('#select_right').val();
    if(grupo<1)
    {
        $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>No ha seleccionado ningun grupo</h3>","aguanta.png","1000","width:40px; height:90px;"); 
        $("#carriers select option").prop("selected",false);
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
                    $SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>No hay carriers preselccionados <br> para asignar o  desasignar</h3>","aguanta.png","2000","width:40px; height:90px;"); 
                    $("#carriers select option").prop("selected",false);
                }
                else
                {
                    if(asigname=="")   var asig="";
                       else            var asig='Asignarle: ';
                    if(noasigname=="") var desA="";
                       else            var desA='Dsasignarle:';
                    
                    $SORI.UI.msj_confirm("<h4>Esta a punto de realizar los siguientes cambios en el grupo<br><b>"+grupoName+"</b></h4><p><h6>"+asig+"<p>"+asigname+"</h6><h6>"+desA+"<p>"+noasigname+"</h6>");  
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
                                    if(asigname=="")  var pudo="Le fue";
                                      else            var pudo='Le fue asignado:';
                                    
                                    if(noasigname=="")var nopudo="";
                                      else            var nopudo='Desasignado:';
                                    
                                    $SORI.UI.msj_change("<h3>El grupo <b>" + grupoSave + "</b><p>Fue modificado con exito</h3><br><h5>" + pudo + "<br><b>" + asigname + "</b><p>" + nopudo + "<br><b>" + noasigname + "</b></h5>","si.png","4000","width:95px; height:95px;"); 
                                    $('#Carrier_id').val('');
                                    $("#carriers select option").prop("selected",false);
                                }
                            });
                        }else{
                            $(".cargando,.mensaje").fadeOut();
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

/**
 * Objeto Global
 */
 var $SORI={};
 
/**
 * Sobmodulo UI
 */
$SORI.UI=(function()
{
	/**
	 * @access public
	 */
	function init()
	{
		accion();
	}

	/**
	 * Metodo encargado de agregar campos para editar en la tabla
	 * @access private
	 * @param obj obj es el objeto de la fila que se quiere manipular
	 */
	function _editar(obj)
	{
		for (var i=2, j=obj[0].childElementCount-2;i<=j;i++)
		{
			var input=document.createElement('input');
			input.name=obj[0].children[i].id;
			input.value=obj[0].children[i].innerHTML;
			if(i>=2 && i<=7)
			{
				$(input).datepicker();
			}
                        if(i>=8 && i<=9)
			{
				$(input).clockpick({ starthour: "00", endhour: "23", military: "TRUE" });
			} 
			obj[0].children[i].innerHTML="";
			obj[0].children[i].appendChild(input);
			input=null;
		}
		obj[0].children[14].innerHTML="";
		obj[0].children[14].innerHTML="<img name='save' alt='save' src='/images/icon_check.png'><img name='cancel' alt='cancel' src='/images/icon_arrow.png'>";
		obj=null;
		accion();
	}
	function _editar_Fac_Rec(obj)
	{
		for (var i=1, j=obj[0].childElementCount-1;i<=j;i++)
		{
			var input=document.createElement('input');
			input.name=obj[0].children[i].id;
			input.value=obj[0].children[i].innerHTML;
			if(i>=1 && i<=5)
			{
				$(input).datepicker();
			}
                        if(i>=6 && i<=7)
			{
				$(input).clockpick({ starthour: "00", endhour: "23", military: "TRUE" });
			} 
			obj[0].children[i].innerHTML="";
			obj[0].children[i].appendChild(input);
			input=null;
		}
		obj[0].children[12].innerHTML="";
		obj[0].children[12].innerHTML="<img name='save_Fac_Rec' alt='save' src='/images/icon_check.png'><img name='cancel_Fac_Rec' alt='cancel' src='/images/icon_arrow.png'>";
		obj=null;
		accion();
	}
	function _editar_Fac_Env(obj)
	{
		for (var i=1, j=obj[0].childElementCount-1;i<=j;i++)
		{
			var input=document.createElement('input');
			input.name=obj[0].children[i].id;
			input.value=obj[0].children[i].innerHTML;
			if(i>=1 && i<=4)
			{
				$(input).datepicker();
			}
			obj[0].children[i].innerHTML="";
			obj[0].children[i].appendChild(input);
			input=null;
		}
		obj[0].children[9].innerHTML="";
		obj[0].children[9].innerHTML="<img name='save_Fac_Env' alt='save' src='/images/icon_check.png'><img name='cancel_Fac_Env' alt='cancel' src='/images/icon_arrow.png'>";
		obj=null;
		accion();
	}
	function _editar_Cobros(obj)
	{
		for (var i=1, j=obj[0].childElementCount-1;i<=j;i++)
		{
			var input=document.createElement('input');
			input.name=obj[0].children[i].id;
			input.value=obj[0].children[i].innerHTML;
			if(i>=1 && i<=1)
			{
				$(input).datepicker();
			}
			obj[0].children[i].innerHTML="";
			obj[0].children[i].appendChild(input);
			input=null;
		}
		obj[0].children[5].innerHTML="";
		obj[0].children[5].innerHTML="<img name='save_Cobros' alt='save' src='/images/icon_check.png'><img name='cancel_Cobros' alt='cancel' src='/images/icon_arrow.png'>";
		obj=null;
		accion();
	}
	function _editar_Pagos(obj)
	{
		for (var i=1, j=obj[0].childElementCount-1;i<=j;i++)
		{
			var input=document.createElement('input');
			input.name=obj[0].children[i].id;
			input.value=obj[0].children[i].innerHTML;
			if(i>=1 && i<=1)
			{
				$(input).datepicker();
			}
			obj[0].children[i].innerHTML="";
			obj[0].children[i].appendChild(input);
			input=null;
		}
		obj[0].children[5].innerHTML="";
		obj[0].children[5].innerHTML="<img name='save_Pagos' alt='save' src='/images/icon_check.png'><img name='cancel_Pagos' alt='cancel' src='/images/icon_arrow.png'>";
		obj=null;
		accion();
	}
	function _editar_DispRec(obj)
	{
		for (var i=2, j=obj[0].childElementCount-2;i<=j;i++)
		{
			if(i>=3 && i<=6)
			{
                            var input=document.createElement('input');
                            input.name=obj[0].children[i].id;
                            input.value=obj[0].children[i].innerHTML;
                            obj[0].children[i].innerHTML="";
                            obj[0].children[i].appendChild(input);
                            input=null;
                        }
		}
		obj[0].children[10].innerHTML="";
		obj[0].children[10].innerHTML="<img name='save_DispRec' alt='save' src='/images/icon_check.png'><img name='cancel_DispRec' alt='cancel' src='/images/icon_arrow.png'>";
		obj=null;
		accion();
	}
	function _editar_DispEnv(obj)
	{
		for (var i=2, j=obj[0].childElementCount-2;i<=j;i++)
		{
			if(i>=3 && i<=6)
			{
                            var input=document.createElement('input');
                            input.name=obj[0].children[i].id;
                            input.value=obj[0].children[i].innerHTML;
                            obj[0].children[i].innerHTML="";
                            obj[0].children[i].appendChild(input);
                            input=null;
                        }
		}
		obj[0].children[10].innerHTML="";
		obj[0].children[10].innerHTML="<img name='save_DispEnv' alt='save' src='/images/icon_check.png'><img name='cancel_DispRec' alt='cancel' src='/images/icon_arrow.png'>";
		obj=null;
		accion();
	}

	/**
	 * Metodo encargado de regresar la fila a su estado normal si estuvo en estado de edicion
	 * @access private
	 * @param obj obj es el objeto de la fila que se esta manipulando
	 */
	function _revert_Fac_Rec(obj)
	{
		var contenido=new Array();
		for (var i=1, j=obj[0].childElementCount-1;i<=j;i++)
		{
			contenido[i]=obj[0].children[i].children[0].value;
			obj[0].children[i].children[0].remove();
			obj[0].children[i].innerHTML=contenido[i];
		}
		obj[0].children[12].innerHTML="";
		obj[0].children[12].innerHTML="<img class='edit' name='edit_Fac_Rec' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'>";
		obj=contenido=null;
		accion();
	}
	function _revert_Fac_Env(obj)
	{
		var contenido=new Array();
		for (var i=1, j=obj[0].childElementCount-1;i<=j;i++)
		{
			contenido[i]=obj[0].children[i].children[0].value;
			obj[0].children[i].children[0].remove();
			obj[0].children[i].innerHTML=contenido[i];
		}
		obj[0].children[9].innerHTML="";
		obj[0].children[9].innerHTML="<img class='edit' name='edit_Fac_Env' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'>";
		obj=contenido=null;
		accion();
	}
	function _revert_Cobros(obj)
	{
		var contenido=new Array();
		for (var i=1, j=obj[0].childElementCount-1;i<=j;i++)
		{
			contenido[i]=obj[0].children[i].children[0].value;
			obj[0].children[i].children[0].remove();
			obj[0].children[i].innerHTML=contenido[i];
		}
		obj[0].children[5].innerHTML="";
		obj[0].children[5].innerHTML="<img class='edit' name='edit_Cobros' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'>";
		obj=contenido=null;
		accion();
	}
	function _revert_Pagos(obj)
	{
		var contenido=new Array();
		for (var i=1, j=obj[0].childElementCount-1;i<=j;i++)
		{
			contenido[i]=obj[0].children[i].children[0].value;
			obj[0].children[i].children[0].remove();
			obj[0].children[i].innerHTML=contenido[i];
		}
		obj[0].children[5].innerHTML="";
		obj[0].children[5].innerHTML="<img class='edit' name='edit_Pagos' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'>";
		obj=contenido=null;
		accion();
	}
	function _revert_DispRec(obj)
	{
		var contenido=new Array();
		for (var i=2, j=obj[0].childElementCount-2;i<=j;i++)
		{
                    if(i>=3 && i<=6)
			{
			contenido[i]=obj[0].children[i].children[0].value;
			obj[0].children[i].children[0].remove();
			obj[0].children[i].innerHTML=contenido[i];
                        }
		}
		obj[0].children[10].innerHTML="";
		obj[0].children[10].innerHTML="<img class='edit' name='edit_DispRec' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'>";
		obj=contenido=null;
		accion();
	}
	function _revert_DispEnv(obj)
	{
		var contenido=new Array();
		for (var i=2, j=obj[0].childElementCount-2;i<=j;i++)
		{
                    if(i>=3 && i<=6)
			{
			contenido[i]=obj[0].children[i].children[0].value;
			obj[0].children[i].children[0].remove();
			obj[0].children[i].innerHTML=contenido[i];
                        }
		}
		obj[0].children[10].innerHTML="";
		obj[0].children[10].innerHTML="<img class='edit' name='edit_DispEnv' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'>";
		obj=contenido=null;
		accion();
	}
	function _update_monto_Disp()
	{
           var minutosETX=$('td #AccountingDocumentTemp[min_etx]').attr(),
               minutosCarr=$('td #AccountingDocumentTemp[min_carrier]').attr(),
               tarifaETX=$('td #AccountingDocumentTemp[rate_etx]').attr(),
               tarifaCarr=$('td #AccountingDocumentTemp[rate_carrier]').attr();
        alert(minutosETX); 
        alert(minutosCarr); 
        alert(tarifaETX); 
        alert(tarifaCarr); 
	}
	/**
	 * Metodo encargado de ejecutar las repectivas llamadas
	 * @access public
	 */
	function accion()
	{
		var $fila;
		$("img[name='edit'],img[name='edit_DispRec'],img[name='edit_DispEnv'], img[name='edit_Pagos'], img[name='edit_Cobros'], img[name='edit_Fac_Env'], img[name='edit_Fac_Rec'], img[name='delete'], img[name='save_Pagos'], img[name='save_DispRec'], img[name='save_DispEnv'], img[name='save_Cobros'], img[name='save_Fac_Env'], img[name='save_Fac_Rec'], img[name='cancel_Fac_Rec'], img[name='cancel_Fac_Env'], img[name='cancel_Pagos'], img[name='cancel_Cobros'], img[name='cancel_DispRec'], img[name='cancel_DispEnv']").on('click',function()
		{
			$fila=$(this).parent().parent();
//                        GENERAL
			if($(this).attr('name')=="delete")
			{
                            var revisa = $("<div class='cargando'></div><div class='mensaje'><h4>Esta a punto de ELIMINAR<p>Si esta seguro presione Aceptar, de lo contrario Cancelar <p><p><p><div id='cancelar'class='cancelar'><p><label><b>Cancelar</b></label></div>&nbsp;<div id='confirma' class='confirma'><p><label><b>Aceptar</b></label></div></div>").hide();

                              $("body").append(revisa);
                              revisa.fadeIn('slow');
                              $('#confirma,#cancelar').on('click',function()
                              {
                                  var tipo=$(this).attr('id');
                                  if(tipo=="confirma")
                                  {
                                     $fila.remove();
                                     $SORI.AJAX.borrar($fila[0].id);
                                     revisa.fadeOut('slow'); 
                                  }else{
                                    revisa.fadeOut('slow');  
                                  }
                              });
			}
//                        FACTURAS RECIBIDAS
			if($(this).attr('name')=='edit_Fac_Rec')
			{
				_editar_Fac_Rec($fila);
			}
                        if($(this).attr('name')=='save_Fac_Rec')
			{
				$SORI.AJAX.actualizar($fila[0].id);
				_revert_Fac_Rec($fila);
			}
                        if($(this).attr('name')=='cancel_Fac_Rec')
			{
				_revert_Fac_Rec($fila);
			}
//                        FACTURAS ENVIADAS
			if($(this).attr('name')=='edit_Fac_Env')
			{
				_editar_Fac_Env($fila);
			}
                        if($(this).attr('name')=='save_Fac_Env')
			{
				$SORI.AJAX.actualizar($fila[0].id);
				_revert_Fac_Env($fila);
			}
                        if($(this).attr('name')=='cancel_Fac_Env')
			{
				_revert_Fac_Env($fila);
			}
//                        COBROS
			if($(this).attr('name')=='edit_Cobros')
			{
				_editar_Cobros($fila);
			}
                        if($(this).attr('name')=='save_Cobros')
			{
				$SORI.AJAX.actualizar($fila[0].id);
				_revert_Cobros($fila);
			}
                        if($(this).attr('name')=='cancel_Cobros')
			{
				_revert_Cobros($fila);
			}
//                        PAGOS
			if($(this).attr('name')=='edit_Pagos')
			{
				_editar_Pagos($fila);
			}
			if($(this).attr('name')=='save_Pagos')
			{
				$SORI.AJAX.actualizar($fila[0].id);
				_revert_Pagos($fila);
			}
			if($(this).attr('name')=='cancel_Pagos')
			{
				_revert_Pagos($fila);
			}
//                        DISPUTAS: El mismo código sirve para disp recibidas y enviadas
			if($(this).attr('name')=='edit_DispRec')
			{
				_editar_DispRec($fila);
			}
			if($(this).attr('name')=='save_DispRec')
			{
				$SORI.AJAX.actualizar($fila[0].id);
				_revert_DispRec($fila);
				_update_monto_Disp($fila);
			}
			if($(this).attr('name')=='cancel_DispRec')
			{
				_revert_DispRec($fila);
			}
//                        DISPUTAS: El mismo código sirve para disp recibidas y enviadas
			if($(this).attr('name')=='edit_DispEnv')
			{
				_editar_DispEnv($fila);
			}
			if($(this).attr('name')=='save_DispEnv')
			{
				$SORI.AJAX.actualizar($fila[0].id);
				_revert_DispEnv($fila);
				_update_monto_Disp($fila);
			}
			if($(this).attr('name')=='cancel_DispEnv')
			{
				_revert_DispEnv($fila);
			}
		});
		$fila=null;
	}
        /**
	 * Metodo encargado de la actualizacion de las facturas en disputas y notas de credito
	 * @access public
	 */ 
        function toggleDestProv()
        {
            $('div.nuevoDestProv').click('on',function()
            {
                $(this).hide('fast');
                $('div.cancelarDestProv').show('fast');
                $('#AccountingDocumentTemp_select_dest_supplier').hide('fast'); 
                $('#AccountingDocumentTemp_input_dest_supplier').show('fast');
                $('#AccountingDocumentTemp_select_dest_supplier').val('');
            });
            $('div.cancelarDestProv').click('on',function()
            {
                $(this).hide('fast');
                $('div.nuevoDestProv').show('fast');
                $('#AccountingDocumentTemp_select_dest_supplier').show('fast'); 
                $('#AccountingDocumentTemp_input_dest_supplier').hide('fast');
                $('#AccountingDocumentTemp_input_dest_supplier').val('');
            });
        }
	/**
	 * Metodo encargado de la actualizacion de las facturas en disputas y notas de credito
	 * @access public
	 * @param id string es el id del formulario que se realizan cambios
	 */  
        function buscaFactura(id)
        {
            $(id).change(function()
            {
                var tipo_Ac_doc=$('#AccountingDocumentTemp_id_type_accounting_document').val(),
                    CarrierDisp=$('#AccountingDocumentTemp_id_carrier').val(),
                    desdeDisp=$('#AccountingDocumentTemp_from_date').val(),
                    hastaDisp=$('#AccountingDocumentTemp_to_date').val();
                    
               if(tipo_Ac_doc==5||tipo_Ac_doc==7){
                   var tipoDoc=1;
               }else if(tipo_Ac_doc==6||tipo_Ac_doc==8){
                   tipoDoc=2;
               }     

                if (CarrierDisp && desdeDisp && hastaDisp){
                $.ajax({
                    type: "GET",
                    url: "BuscaFactura",
                    data:"&tipoDoc="+tipoDoc+"&CarrierDisp="+CarrierDisp+"&desdeDisp="+desdeDisp+"&hastaDisp="+hastaDisp,

                success: function(data) 
                        {
                            console.log(data);
                            if(data=="[]"){
                                var noHayFacturas = $("<div class='cargando'></div><div class='mensaje'><h4>No hay facturas registradas con el carrier y el periodo de facturacion indicado</h4>Por favor revise los datos, y vuelva a intentar<p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
                                $("body").append(noHayFacturas);
                                noHayFacturas.fadeIn('slow');
                                setTimeout(function()
                                { noHayFacturas.fadeOut('slow');
                                }, 4000);
                            }else{
                                obj = JSON.parse(data);
                                $("select#AccountingDocumentTemp_id_accounting_document").html("");
                                for(var i=0, j=obj.length; i<j;i++)
                                    {
                                        console.log(obj[i].id,obj[i].factura);
                                        $("select#AccountingDocumentTemp_id_accounting_document").append("<option value="+obj[i].id+">"+obj[i].factura+"</option>");
                                    }
                            }
                        }
                    });
                }
            });
        }
        /**
	 * Metodo encargado de la actualizacion de las facturas en disputas y notas de credito
	 * @access public
	 * @param ocultar array es el arreglo que contiene los elementos a ocultarse
	 * @param mostrar array es el arreglo que contiene los elementos a mostrarse
	 */  
        function formChangeAccDoc(ocultar, mostrar){
            
            for (var i=0, j=ocultar.length - 1; i <= j; i++){
                $(ocultar[i]).fadeOut('fast');              
            }
            for (var x=0, z=mostrar.length - 1; x <= z; x++){
                $(mostrar[x]).fadeIn('fast');              
            }
            
        }
        /**
	 * Metodo encargado de las animaciones de la tabla comercial
	 * @access public
	 * @param id string es el id del formulario que se realizan cambios
	 */ 
	function formChange(id)
	{
		$('#'+id).change(function()
		{
			var nota=$('.note'), muestraDiv1= $('.divOculto'), muestraformC= $('.formularioContrato'),
				muestraDiv2=$('.divOculto1'), pManager=$('.pManager'), NombreCarrier=$('.CarrierActual'),
				idCarrier=$("#Contrato_id_carrier").val(), end_date=$("#Contrato_end_date").val();
		    $("#Contrato_id_company").val('');
		    $("#Contrato_sign_date").val('');
		    $("#Contrato_production_date").val('');
		    $("#Contrato_id_termino_pago").val('');
		    $("#Contrato_id_monetizable").val('');
		    $("#Contrato_up").val('');
		    $("#Contrato_id_disputa").val('');
		    $("#F_Firma_Contrato_Oculto").val('');
		    $("#F_P_produccion_Oculto").val('');
		    $("#TerminoP_Oculto").val('');
		    $("#Contrato_id_monetizable").val('');
		    $("#dias_disputa_Oculto").val('');
		    $(".manageractual").empty();
		    $(".CarrierActual").empty();
		    $.ajax({
		        type: "GET",
		        url: "DynamicDatosContrato",
		        data: "idCarrier="+idCarrier,
		        success: function(data)
		        {   
		            obj=JSON.parse(data);
		            $("#Contrato_id_company").val(obj.company);
		            if(obj.company!='')
		            {
		                $("#Contrato_id_company").prop("disabled", true);
		                $("#Contrato_end_date").prop("disabled", false);
		                 if(obj.sign_date=='' || obj.sign_date == null){
                                     $("#Contrato_sign_date").prop("disabled", false);
                                }else{
                                     $("#Contrato_sign_date").prop("disabled", true);
                                }
		            }
		            else
		            {
                               $("#Contrato_sign_date").prop("disabled", false)
		                $("#Contrato_id_company").prop("disabled", false);
		                $("#Contrato_end_date").prop("disabled", true);
		               
		            }
		            $("#Contrato_sign_date").val(obj.sign_date);
		            $("#Contrato_production_date").val(obj.production_date);
		            $("#Contrato_id_termino_pago").val(obj.termino_pago);
		            $("#Contrato_id_monetizable").val(obj.monetizable);
		            $("#Contrato_id_managers").val(obj.manager);
		            $("#Contrato_id_disputa").val(obj.dias_disputa);
		            $("#Contrato_id_disputa_solved").val(obj.dias_disputa_solved);
		            $("#Contrato_up").val(obj.Contrato_up);
		            $("#Contrato_upOculto").val(obj.Contrato_up);
		            $("#F_Firma_Contrato_Oculto").val(obj.sign_date);
		            $("#F_P_produccion_Oculto").val(obj.production_date);
		            $("#TerminoP_Oculto").val(obj.termino_pago);
		            $("#monetizable_Oculto").val(obj.monetizable);
		            $("#dias_disputa_Oculto").val(obj.dias_disputa);
		            $("#dias_disputa_solved_Oculto").val(obj.dias_disputa_solved);
		            $("#Contrato_id_limite_credito").val(obj.credito);
		            $("#credito_Oculto").val(obj.credito);
		            $("#Contrato_id_limite_compra").val(obj.compra);
		            $("#compra_Oculto").val(obj.compra);

		            var manageractual=(obj.manager), carrierenlabel=(obj.carrier),
		            	fechaManagerCarrier=(obj.fechaManager),

		            	managerA=$("<label><h3 style='margin-left: -66px; margin-top: \n\ 105px; color:rgba(111,204,187,1)'>"+manageractual+" / " +fechaManagerCarrier+"</h3></label><label><h6 style='margin-left: -66px; margin-top: \n\ -10px; '></h6></label>");
		            	carrierA=$("<label id='labelCarrier'><h1 align='right' style='margin-left: 8px; margin-top: \n\ -106px; color:rgba(111,204,187,1)'>"+carrierenlabel+"</h1></label>");
		            $('.manageractual').append(managerA);
		            managerA.slideDown('slow');
		            $('.CarrierActual').append(carrierA);
		            carrierA.slideDown('slow');
		        }
		    });
		    muestraDiv2.slideDown("slow");
		    nota.fadeIn("slow");
		    pManager.slideDown("slow");
		    muestraDiv1.slideDown("slow");
		    muestraformC.slideDown("slow");
		    NombreCarrier.slideDown("slow");
		    carrierA=managerA=fechaManagerCarrier=carrierenlabel=manageractual=end_date=idCarrier=NombreCarrier=pManager=muestraDiv2=muestraformC=muestraDiv1=nota=null;
		});
	}
        function llenarTabla(tipo,data){
            obj = JSON.parse(data);
            console.dir(obj.issue_date);
            if(obj.id !== undefined) var id="<tr class='vistaTemp' id='"+obj.id+"'>";
            if(obj.carrier !== undefined) var carrier="<td id='AccountingDocumentTemp[id_carrier]'>"+obj.carrier+"</td>";
            if(obj.group !== undefined) var group="<td id='AccountingDocumentTemp[carrier_groups]'>"+obj.group+"</td>";
            if(obj.issue_date !== undefined) var issue_date="<td id='AccountingDocumentTemp[issue_date]'>"+obj.issue_date+"</td>";
            if(obj.from_date !== undefined) var from_date="<td id='AccountingDocumentTemp[from_date]'>"+obj.from_date+"</td>";
            if(obj.to_date !== undefined) var to_date="<td id='AccountingDocumentTemp[to_date]'>"+obj.to_date+"</td>";
            if(obj.sent_date !== undefined) var sent_date="<td id='AccountingDocumentTemp[sent_date]'>"+obj.sent_date+"</td>";
            if(obj.doc_number !== undefined) var doc_number="<td id='AccountingDocumentTemp[doc_number]'>"+obj.doc_number+"</td>";
            if(obj.fact_number !== undefined) var fact_number="<td id='AccountingDocumentTemp[id_accounting_document]'>"+obj.fact_number+"</td>";
            if(obj.minutes !== undefined) var minutes="<td id='AccountingDocumentTemp[minutes]'>"+obj.minutes+"</td>";
            if(obj.amount !== undefined) var amount="<td id='AccountingDocumentTemp[amount]'>"+obj.amount+"</td>";
            if(obj.currency !== undefined) var currency="<td id='AccountingDocumentTemp[id_currency]'>"+obj.currency+"</td>";
            if(obj.email_received_date !== undefined) var email_received_date="<td id='AccountingDocumentTemp[email_received_date]'>"+obj.email_received_date+"</td>";
            if(obj.email_received_hour !== undefined) var email_received_hour="<td id='AccountingDocumentTemp[email_received_hour]'>"+obj.email_received_hour+"</td>";
            if(obj.valid_received_date !== undefined) var valid_received_date="<td id='AccountingDocumentTemp[valid_received_date]'>"+obj.valid_received_date+"</td>";
            if(obj.valid_received_hour !== undefined) var valid_received_hour="<td id='AccountingDocumentTemp[valid_received_hour]'>"+obj.valid_received_hour+"</td>";
            if(obj.destination !== undefined) var destination="<td id='AccountingDocumentTemp[id_destination]'>"+obj.destination+"</td>";
            if(obj.destinationSupp !== undefined) var destinationSupp="<td id='AccountingDocumentTemp[id_destination_supplier]'>"+obj.destinationSupp+"</td>";
            if(obj.min_etx !== undefined) var min_etx="<td id='AccountingDocumentTemp[min_etx]'>"+obj.min_etx+"</td>";
            if(obj.min_carrier !== undefined) var min_carrier="<td id='AccountingDocumentTemp[min_carrier]'>"+obj.min_carrier+"</td>";
            if(obj.rate_etx !== undefined) var rate_etx="<td id='AccountingDocumentTemp[rate_etx]'>"+obj.rate_etx+"</td>";
            if(obj.rate_carrier !== undefined) var rate_carrier="<td id='AccountingDocumentTemp[rate_carrier]'>"+obj.rate_carrier+"</td>";
            if(min_etx !== undefined && rate_etx !== undefined) var amount_etx="<td id='AccountingDocumentTemp[amount_etx]'>"+(obj.min_etx*obj.rate_etx).toFixed(2)+"</td>";
            if(min_carrier !== undefined && rate_carrier !== undefined) var amount_carrier="<td id='AccountingDocumentTemp[amount_carrier]'>"+(obj.min_carrier*obj.rate_carrier).toFixed(2)+"</td>";
            if(amount_etx !== undefined && amount_carrier !== undefined) var dispute="<td id='AccountingDocumentTemp[amount_carrier]'>"+((obj.min_etx*obj.rate_etx)-(obj.min_carrier*obj.rate_carrier)).toFixed(2)+"</td>";
            console.dir(id+group+issue_date+doc_number+amount+currency);
            switch (tipo){
                case '1':
                    var tabla = id+carrier+issue_date+from_date+to_date+sent_date+doc_number+minutes+amount+currency+"<td><img class='edit' name='edit_Fac_Env' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td></tr>",
                    clase=".lista_FacEnv",
                    label='.Label_F_Env';
                    break
                case '2':
                    var tabla = id+carrier+issue_date+from_date+to_date+email_received_date+valid_received_date+email_received_hour+valid_received_hour+doc_number+minutes+amount+currency+"<td><img class='edit' name='edit_Fac_Rec' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td></tr>",
                    clase=".lista_FacRec",
                    label='.Label_F_Rec';
                    break
                case '3':
                    var tabla = id+group+issue_date+doc_number+amount+currency+"<td><img class='edit' name='edit_Pagos' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td></tr>",
                    clase=".lista_Pagos",
                    label='.LabelPagos';
                    break
                case '4':
                    var tabla = id+group+valid_received_date+doc_number+amount+currency+"<td><img class='edit' name='edit_Cobros' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td></tr>",
                    clase=".lista_Cobros",
                    label='.LabelCobros';
                    break
                case '5':
                    var tabla = id+carrier+destination+fact_number+min_etx+min_carrier+rate_etx+rate_carrier+amount_etx+amount_carrier+dispute+"<td><img class='edit' name='edit_DispRec' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td></tr>",
                    clase=".lista_DispRec",
                    label='.Label_DispRec';
                    break
                case '6':
                    var tabla = id+carrier+destinationSupp+fact_number+min_etx+min_carrier+rate_etx+rate_carrier+amount_etx+amount_carrier+dispute+"<td><img class='edit' name='edit_DispEnv' alt='editar' src='/images/icon_lapiz.png'><img class='delete' name='delete' alt='borrar' src='/images/icon_x.gif'></td></tr>",
                    clase=".lista_DispEnv",
                    label='.Label_DispEnv';
                    break
                case '7':
                    var tabla = "id+carrier+fact_number+sum_dispute+amount",
                    clase=".lista_NotCredEnv",
                    label='.Label_NotCredEnv';
                    break
                case '8':
                    var tabla = "cd",
                    clase=".lista_NotCredRec",
                    label='.Label_NotCredRec';
                    break
            }
            $(clase).find("tr:first").after(tabla);
            $(clase).fadeIn('slow');
            $(label).fadeIn('slow');
            $('#botAgregarDatosContableFinal').fadeIn('slow');
        }
        
        function emptyFields(selecTipoDoc){
            $("#AccountingDocumentTemp_email_received_hour, #AccountingDocumentTemp_note, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_minutes, #AccountingDocumentTemp_id_destination_supplier, #AccountingDocumentTemp_minutes, #AccountingDocumentTemp_min_carrier, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_rate_carrier, #AccountingDocumentTemp_id_destination").val('');
                if (selecTipoDoc=='3'||selecTipoDoc=='4'){
                     $("#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_issue_date").val('');
                } 
                if (selecTipoDoc=='5'||selecTipoDoc=='6'){
                     $("#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date").val('');
                } 
        }

	/**
	 * Retorna los mestodos publicos
	 */
	return{
		init:init,
		formChange:formChange,
                buscaFactura:buscaFactura,
                formChangeAccDoc:formChangeAccDoc,
                toggleDestProv:toggleDestProv,
                llenarTabla:llenarTabla,
                emptyFields:emptyFields
	}
})();

/**
 * Submodulo de llamadas AJAX
 */
$SORI.AJAX=(function()
{	
	/**
	 * Metodo encargado de enviar solicitud de eliminar por ajax la fila
	 * @param id int id de la fila que se va a eliminar
	 * @access public
	 */
	function borrar(id)
	{
		$.ajax(
		{
			type:'GET',
			url:'borrar/'+id,
			data:'',
			success:function(data)
			{
				console.log("eliminado");
			}
		});
		id=null;
	}

	/**
	 * Metodo encargado de enviar la solicutid de actualizar por ajax de la fila indicada
	 * @param id int id de la fila que se va actualizar
	 * @access public
	 */
	function actualizar(id)
	{
		$.ajax(
		{
			type:'POST',
			url:'update/'+id,
			data:$SORI.UTILS.getData(id),
			success:function(data)
			{
				console.log(data);
				console.log('actualizo');
			}
		});
		id=null;
	}
	/**
	 * retorna los metodos publicos*/
return{
	actualizar:actualizar,
	borrar:borrar
	}
})();

/**
 * Submodulo de utilidades
 */
$SORI.UTILS=(function()
{
	/**
	 * Obtiene los datos de los inputs dentro de una fila
	 * @access public
	 * @param id int es el id de la fila donde se encuentran los inputs
	 */
	function getData(id)
	{
		var inputs=$('tr#'+id).children().children(), datos="";
		for (var i=0, j=inputs.length - 2; i <= j; i++)
		{
			datos+=inputs[i].name+"="+inputs[i].value+"&";
		};
                console.dir(datos);
		id=null;
		return datos;
	}
        
        function getURL(tipo){
            var action;
            switch (tipo){
                case '1':
                    action = "GuardarFac_EnvTemp";
                    break
                case '2':
                    action = "GuardarFac_RecTemp";
                    break
                case '3':
                    action = "GuardarPagoTemp";
                    break
                case '4':
                    action = "GuardarCobroTemp";
                    break
                case '5':
                    action = "GuardarDisp_RecTemp";
                    break
                case '6':
                    action = "GuardarDisp_EnvTemp";
                    break
                case '7':
                    action = "GuardarNotaC_EnvTemp";
                    break
                case '8':
                    action = "GuardarNotaC_RecTemp";
                    break
            }
            return action;
        }

	/**
	 * retorna los metodos y variables publicos
	 */
	return{
		getData:getData,
		getURL:getURL
	}
})();

$SORI.constructor=(function()
 {
    $SORI.UI.init();
 })();

/**
*
*/
var mensajes=function()
{
	var contenido="";
	this.acumulador=0, this.transparente="transparente", this.interna="interna", this.cuerpo=$('body'), men=this, this.servidor=location.protocol+"//"+location.host+"/";
}
mensajes.prototype.contar=function(objeto)
{
	console.log("Contar");
	console.log(objeto);
	$(objeto).filter(function(){ return $(this).attr('id')=="definitivo"; }).each(function()
	{
		men.acumulador=men.acumulador+parseFloat(1);
		console.log(men.acumulador);
	});
}

mensajes.prototype.lightbox=function(html,estilo,tiempo)
{
	if($("div."+this.transparente).length<=0)
	{
		$(this.cuerpo).append("<div class='"+this.transparente+" oculta'></div>");
	}
	capa=$("div."+this.transparente).append("<div class='"+this.interna+" oculta'>"+html+"</div>");
	if(estilo)
	{
		$("div."+this.interna).css(estilo);
	}
	if(tiempo)
	{
		setTimeout(this.elimina,tiempo);
	}
	$("div."+this.transparente+", div."+this.interna).fadeIn("slow");
}
mensajes.prototype.elimina=function()
{
	capa.fadeOut('slow',function()
	{
		this.remove();
	});
	return true;
}












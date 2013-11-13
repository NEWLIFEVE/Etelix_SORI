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
				$(input).datepicker({ dateFormat: "yy-mm-dd"});
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
	function _editar_Nota_cred(obj)
	{
		for (var i=2, j=obj[0].childElementCount-2;i<=j;i++)
		{
			if(i>=2 && i<=3)
			{
                            var input=document.createElement('input');
                            input.name=obj[0].children[i].id;
                            input.value=obj[0].children[i].innerHTML;
                            obj[0].children[i].innerHTML="";
                            obj[0].children[i].appendChild(input);
                            input=null;
                        }
		}
		obj[0].children[4].innerHTML="";
		obj[0].children[4].innerHTML="<img name='save_Nota_cred' alt='save' src='/images/icon_check.png'><img name='cancel_Nota_cred' alt='cancel' src='/images/icon_arrow.png'>";
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
        function _revert_Nota_cred(obj)
	{
		var contenido=new Array();
		for (var i=2, j=obj[0].childElementCount-2;i<=j;i++)
		{
                    if(i>=2 && i<=3)
			{
			contenido[i]=obj[0].children[i].children[0].value;
			obj[0].children[i].children[0].remove();
			obj[0].children[i].innerHTML=contenido[i];
                        }
		}
		obj[0].children[4].innerHTML="";
		obj[0].children[4].innerHTML="<img class='edit' name='edit_Nota_cred' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'>";
		obj=contenido=null;
		accion();
	}
        function _update_monto_Disp(obj)
        {
//            alert('siiiiii :)');// pausado por ahora
//
//
//            for (var i = 2, j = obj[0].childElementCount - 2; i <= j; i++)
//            {
//                if (i >= 3 && i <= 6)
//                {
//                    var input = document.createElement('input');
//                    input.name = obj[0].children[i].id;
//                    input.value = obj[0].children[i].innerHTML;
//                    obj[0].children[i].innerHTML = "";
//                    obj[0].children[i].appendChild(input);
//                    input = null;
//                }
//            }
//
//
//
//            obj[0].children[7].innerHTML = ('aqui taaaa');
//            obj[0].children[8].innerHTML = (obj[4] * obj[6]);
//            obj[0].children[9].innerHTML = ((obj[3] * obj[5]) - (obj[4] * obj[6]));
//            console.dir((obj[3] * obj[5]));
//            console.dir((obj[4] * obj[6]));
//            console.dir((obj[3] * obj[5]) - (obj[4] * obj[6]));
//
//
//            obj = null;
//            accion();
      
         }
        /**
         * escucha el click sobre la imagen delete y valida para eliminar el documento temporal
         * @param {type} $fila
         * @returns {undefined}
         */
	function _elimina_doc($fila)
	{  
            $('.cargando,.mensaje').remove();var revisa=$("<div class='cargando'></div><div class='mensaje'>Esta a punto de eliminar el documento contable seleccionado<p>Si esta seguro presione Aceptar, de lo contrario Cancelar <p><p><p><div id='cancelar' class='cancelar'><p><label><b>Cancelar</b></label></div>&nbsp;<div id='confirma' class='confirma'><p><label><b>Aceptar</b></label></div></div>").hide();$("body").append(revisa);revisa.fadeIn('slow');
            $('#confirma,#cancelar').on('click',function()
            {
                var tipo=$(this).attr('id');
                if(tipo=="confirma")
                {  
                   revisa.fadeOut('slow'); 
                   $fila.remove();
                   $SORI.AJAX.borrar($fila[0].id);
                }else{
                  revisa.fadeOut('slow'); 
                }setTimeout(function(){revisa.remove();},2000); 
            });
        }



	/**
	 * Metodo encargado de ejecutar las repectivas llamadas
	 * @access public
	 */
	function accion()
	{
		var $fila;
		$("img[name='edit'],img[name='edit_DispRec'],img[name='edit_DispEnv'], img[name='edit_Nota_cred'],img[name='edit_Pagos'], img[name='edit_Cobros'], img[name='edit_Fac_Env'], img[name='edit_Fac_Rec'], img[name='delete'], img[name='save_Pagos'], img[name='save_DispRec'], img[name='save_DispEnv'], img[name='save_Cobros'], img[name='save_Fac_Env'], img[name='save_Nota_cred'],img[name='save_Fac_Rec'], img[name='cancel_Fac_Rec'], img[name='cancel_Fac_Env'], img[name='cancel_Pagos'], img[name='cancel_Cobros'], img[name='cancel_DispRec'], img[name='cancel_DispEnv'], img[name='cancel_Nota_cred']") .on('click',function()
		{
			$fila=$(this).parent().parent();
//                        GENERAL
			if($(this).attr('name')=="delete")
			{
                            _elimina_doc($fila);
			}
//                        FACTURAS RECIBIDAS
			if($(this).attr('name')=='edit_Fac_Rec')
			{
				_editar_Fac_Rec($fila);
			}
                        if($(this).attr('name')=='save_Fac_Rec')
			{
				$SORI.AJAX.actualizar($fila[0].id,2);
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
				$SORI.AJAX.actualizar($fila[0].id,2);
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
				$SORI.AJAX.actualizar($fila[0].id,'2');
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
				$SORI.AJAX.actualizar($fila[0].id,'2');
				_revert_Pagos($fila);
			}
			if($(this).attr('name')=='cancel_Pagos')
			{
				_revert_Pagos($fila);
			}
//                        DISPUTAS RECIBIDAS
			if($(this).attr('name')=='edit_DispRec')
			{
				_editar_DispRec($fila);
			}
			if($(this).attr('name')=='save_DispRec')
			{
				$SORI.AJAX.actualizar($fila[0].id,'2');
				_revert_DispRec($fila);
				_update_monto_Disp($fila);
			}
			if($(this).attr('name')=='cancel_DispRec')
			{
				_revert_DispRec($fila);
			}
//                        DISPUTAS ENVIADAS
			if($(this).attr('name')=='edit_DispEnv')
			{
				_editar_DispEnv($fila);
			}
			if($(this).attr('name')=='save_DispEnv')
			{
				$SORI.AJAX.actualizar($fila[0].id,'2');
				_revert_DispEnv($fila);
				_update_monto_Disp($fila);
			}
			if($(this).attr('name')=='cancel_DispEnv')
			{
				_revert_DispEnv($fila);
			}
//                        NOTAS DE CREDITO
			if($(this).attr('name')=='edit_Nota_cred')
			{
				_editar_Nota_cred($fila);
			}
			if($(this).attr('name')=='save_Nota_cred')
			{
				$SORI.AJAX.actualizar($fila[0].id,'2');
				_revert_Nota_cred($fila);
			}
			if($(this).attr('name')=='cancel_Nota_cred')
			{
				_revert_Nota_cred($fila);
			}
		});
		$fila=null;
	}

        /**
	 * Metodo encargado de mcambiar el select a un input de Destinos Proveedores y viceversa
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
                var tipo_Ac_doc=$('#AccountingDocumentTemp_id_type_accounting_document').val(),CarrierDisp=$('#AccountingDocumentTemp_id_carrier').val(),
                desdeDisp=$('#AccountingDocumentTemp_from_date').val(), hastaDisp=$('#AccountingDocumentTemp_to_date').val();
                if(tipo_Ac_doc==5||tipo_Ac_doc==7)
                {
                    var tipoDoc=1;
                }else if(tipo_Ac_doc==6||tipo_Ac_doc==8)
                { 
                   tipoDoc=2;
                }     
                if (CarrierDisp && desdeDisp && hastaDisp)
                {
                $.ajax({
                    type: "GET",
                    url: "BuscaFactura",
                    data:"&tipoDoc="+tipoDoc+"&CarrierDisp="+CarrierDisp+"&desdeDisp="+desdeDisp+"&hastaDisp="+hastaDisp,
                    success: function(data) 
                    { 
                        $('.cargando,.mensaje,.listaDisputas').remove();
                        $('.tabla_N_C,.montoDoc').fadeOut('fast');
                        if(data=="[]")
                        {     
                              $("#AccountingDocumentTemp_id_accounting_document").html("<option>No hay facturas registradas</option>").css('color','rgb(245, 105, 109)');  
                              $('select#AccountingDocumentTemp_doc_number').val("");
                        }else{
                            obj = JSON.parse(data);
                            $("#AccountingDocumentTemp_id_accounting_document").html("<option>Seleccione</option>").css('color','#777');
                            for(var i=0, j=obj.length; i<j;i++)
                            {
                                $("#AccountingDocumentTemp_id_accounting_document").append("<option value="+obj[i].id+">"+obj[i].factura+"</option>");  console.log(obj[i].id,obj[i].factura);$('.listaDisputas').remove();$('.tabla_N_C').hide('fast');

                            }
                        }
                    }
                });
                }
            });
        }
        
        function buscaDisputa(tipo)
    {
        $('#AccountingDocumentTemp_id_accounting_document').change(function()
        {
            if (tipo === '7'||tipo === '8') {
            if (tipo === '7') {
               var url = "BuscaDisputaRec";
           } else {
               url = "BuscaDisputaEnv";
           }

            $.ajax({
                type: "GET",
                url: url,
                data: $('#accounting-document-temp-form').serialize(),
                success: function(data)
                {
                   $('.listaDisputas').remove();
                   if(data=="[]"){
                      //no hay datos y asi no muestra nada
                   }else{
                    console.log(data);
                    var obj = JSON.parse(data);
                    $('.tabla_N_C').fadeIn("fast");
                    for (var i = 0, j = obj.length; i < j; i++)
                    {
                        var montoTotal = (obj[i].amount) + (obj[i].dispute);
                        $(".lista_Disp_NotaCEnv").append("<tr class='listaDisputas' id='" + obj[i].id + "'>\n\
                                                            <td id='AccountingDocumentTemp[id_destination]'>" + obj[i].id_destination + "</td>\n\
                                                            <td id='AccountingDocumentTemp[min_etx]'>" + obj[i].min_etx + "</td>\n\
                                                            <td id='AccountingDocumentTemp[min_carrier]'>" + obj[i].min_carrier + "</td>\n\
                                                            <td id='AccountingDocumentTemp[rate_etx]'>" + obj[i].rate_etx + "</td>\n\
                                                            <td id='AccountingDocumentTemp[rate_carrier]'>" + obj[i].rate_carrier + "</td>\n\
                                                            <td id='AccountingDocumentTemp[amount_etx]'>" + obj[i].amount_etx + "</td>\n\
                                                            <td id='AccountingDocumentTemp[amount]'>" + obj[i].amount_carrier + "</td>\n\
                                                            <td id='AccountingDocumentTemp[dispute]'>" + Math.round(obj[i].amount_etx-obj[i].amount_carrier) + "</td>\n\
                                                            <td id='AccountingDocumentTemp[monto_nota]'><input name='AccountingDocumentTemp[amount]' id='montoNota'value=" + Math.round(obj[i].dispute) + "></td>\n\
                                                        </tr>");
                        $('.lista_Disp_NotaCEnv,.numDocument,.Label_Disp_NotaCEnv, .montoDoc').fadeIn('slow');
                        $('#AccountingDocumentTemp_amount').text(montoTotal);
                        var acum = 0;
                        $('input#montoNota').each(function() {
                            acum = acum + parseFloat($(this).val());
                            $('#AccountingDocumentTemp_amount').val(acum);
                        });
                        sumMontoNota();
                    }
                  } 
                }
            });
          }
      });
    }
    
        /**
         *
	 */  
        $('.botonImprimir').click('on',function()
        {
            var imprimir = window.open("/AccountingDocumentTemp/print","Vista de impresion");
            imprimir.print();
            setTimeout(function(){ imprimir.close();}, 100);
        });
        /**
         *
	 */  
        $('.botonCorreo').click('on',function()
        {
             console.dir('al menos entra');
    
            var html = "<table class='lista_FacEnv'>" + $(".lista_FacEnv").clone().html() + "</table>" + "<br/>"+"<table>" + $(".lista_FacRec").clone().html() + "</table>" + "<br/>"+"<table>" + $(".lista_Cobros").clone().html() + "</table>" + "<br/>"+"<table>" + $(".lista_Pagos").clone().html() + "</table>" + "<br/>"+"<table>" + $(".lista_DispRec").clone().html() + "</table>" + "<br/>"+"<table>" + $(".lista_DispEnv").clone().html() + "</table>" + "<br/>"+"<table>" + $(".lista_NotCredEnv").clone().html() + "</table>" + "<br/>" + $(".lista_NotCredRec").clone().html() + "</table>";
            console.log(html);
            $("#html").val(html);
            $("#FormularioCorreo").submit();
            alert('Correo Enviado');
                console.dir('es un milagro!! !), llego aqui .}');

        });
        
//        $("img#mail").click(function(event)
//        {
//            var html = $("div.enviar").clone().html();
//            $("#html").val(html);
//            $("#FormularioCorreo").submit();
//            alert('Correo Enviado');
//        });
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
		    $("#Contrato_status").val('');
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
		            $("#Contrato_status").val(obj.Contrato_status);
		            $("#Contrato_statusOculto").val(obj.Contrato_status);
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
        function llenarTabla(obj){
            console.dir(obj);
            if (obj.valid==1){
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
                switch (obj.id_type_accounting_document){
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
                        var tabla = id+carrier+destination+fact_number+min_etx+min_carrier+rate_etx+rate_carrier+amount_etx+amount_carrier+amount+"<td><img class='edit' name='edit_DispRec' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td></tr>",
                        clase=".lista_DispRec",
                        label='.Label_DispRec';
                        break
                    case '6':
                        var tabla = id+carrier+destinationSupp+fact_number+min_etx+min_carrier+rate_etx+rate_carrier+amount_etx+amount_carrier+amount+"<td><img class='edit' name='edit_DispEnv' alt='editar' src='/images/icon_lapiz.png'><img class='delete' name='delete' alt='borrar' src='/images/icon_x.gif'></td></tr>",
                        clase=".lista_DispEnv",
                        label='.Label_DispEnv';
                        break
                    case '7':
                        var tabla = id+carrier+fact_number+doc_number+amount+"<td><img class='edit' name='edit_Nota_cred' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td></tr>",
                        clase=".lista_NotCredEnv",
                        label='.Label_NotCredEnv';
                        break
                    case '8':
                        var tabla = id+carrier+fact_number+doc_number+amount+"<td><img class='edit' name='edit_Nota_cred' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td></tr>",
                        clase=".lista_NotCredRec",
                        label='.Label_NotCredRec';
                        break
                }
                $(clase).find("tr:first").after(tabla);
                $(clase).fadeIn('slow');
                $(label).fadeIn('slow');
                $('#botAgregarDatosContableFinal, .botonesParaExportar').fadeIn('slow');
            }else{
                $SORI.UI.MensajeYaExiste(obj);
            }
        }
         function MensajeYaExiste(obj){
             var F_facturas="en el periódo <b>"+obj.from_date+" / "+obj.to_date+"</b>",carrier="con el carrier <b>"+obj.carrier+"</b>", grupo=" el grupo <b>"+obj.group+"</b>",doc_number="<b>"+obj.doc_number+"</b>";
             switch (obj.id_type_accounting_document){
                    case '1':
                        $SORI.UI.MuestraMensaje("La Factura Enviada",carrier,F_facturas,doc_number);
                        break
                    case '2':
                        $SORI.UI.MuestraMensaje("La Factura Recibida",carrier,F_facturas,doc_number);
                        break
                    case '3':
                        $SORI.UI.MuestraMensaje("El Pago","con fecha de emisión <b>"+obj.issue_date+"</b>",grupo,doc_number);
                        break
                    case '4':
                        $SORI.UI.MuestraMensaje("El Cobro","con fecha de recepción <b>"+obj.valid_received_date+"</b>",grupo,doc_number);
                        break
                    case '5':
                        $SORI.UI.MuestraMensaje("La Disputa Recibida",carrier+", el destino <b>"+obj.destination+"</b> ",F_facturas,"de factura <b>"+obj.fact_number+"</b>");
                        break
                    case '6':
                        $SORI.UI.MuestraMensaje("La Disputa Enviada",carrier+" el destino supplier <b>"+obj.destinationSupp+"</b> ",F_facturas,"de factura <b>"+obj.fact_number+"</b>");
                        break
                    case '7':
                        $SORI.UI.MuestraMensaje("La Nota de Crédito Enviada",carrier,F_facturas,"de factura <b>"+obj.fact_number+"</b> y N°. de documento  <b>"+obj.doc_number+"</b>");
                        break
                    case '8':
                        $SORI.UI.MuestraMensaje("La Nota de Crédito Enviada",carrier,F_facturas,"de factura <b>"+obj.fact_number+"</b> y N°. de documento  <b>"+obj.doc_number+"</b>");
                        break
             }
         }
         function MuestraMensaje(tipo,operador,fecha,doc_number)
         {
              $('.cargando, .mensaje').remove();
              var msj=$("<div class='cargando'></div><div class='mensaje'><h4 align='justify'><b>"+tipo+"</b> que intenta guardar, ya se encuentra registrado(a) "+operador+", "+fecha+" , bajo el N°. "+doc_number+"</h4><br><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
              $("body").append(msj); 
              msj.fadeIn('slow');
              setTimeout(function() { msj.fadeOut('slow'); }, 3000);
         }
        
        function emptyFields(obj){
            
            $("#AccountingDocumentTemp_email_received_hour, #AccountingDocumentTemp_note, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_minutes, #AccountingDocumentTemp_id_destination_supplier, #AccountingDocumentTemp_minutes, #AccountingDocumentTemp_min_carrier, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_rate_carrier, #AccountingDocumentTemp_id_destination").val('');
                if (obj.id_type_accounting_document=='3'||obj.id_type_accounting_document=='4'){
                     $("#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_issue_date").val('');
                } 
                if (obj.id_type_accounting_document=='5'||obj.id_type_accounting_document=='6'){
                     $("#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date").val('');
                } 
        }
        
        function changeCss(clase,attr,value){
            $(clase).css(attr,value);
        }
        
        function sumMontoNota() {
            console.log('entro');
        $('input#montoNota').change(function()
        {
            var acum = 0;
            $('input#montoNota').each(function() {
                acum = acum + parseFloat($(this).val());
                $(this).parent().attr('id');
                console.log(acum);
                $('input#AccountingDocumentTemp_amount').val(acum);
            });
        });
    }

	/**
	 * Retorna los mestodos publicos
	 */
	return{
		init:init,
		formChange:formChange,
                buscaFactura:buscaFactura,
                buscaDisputa:buscaDisputa,
                formChangeAccDoc:formChangeAccDoc,
                toggleDestProv:toggleDestProv,
                llenarTabla:llenarTabla,
                emptyFields:emptyFields,
                changeCss:changeCss,
                sumMontoNota:sumMontoNota,
                MensajeYaExiste:MensajeYaExiste,
                MuestraMensaje:MuestraMensaje
	};
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
	 * @param tope int es el tope de columna a la cual voy a leer, se pasa a getData
	 * @access public
	 */
	function actualizar(id,tope)
	{       
            if(tope=='2'){
                    var url = "update/"+id;
                }else{
                    var url = "../AccountingDocument/UpdateDisputa/"+id;
                }
		$.ajax(
		{
			type:'POST',
			url:url,
			data:$SORI.UTILS.getData(id,tope),
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
	 * @param tope int es el tope de columna a la cual voy a leer
	 */
	function getData(id,tope)
	{
		var inputs=$('tr#'+id).children().children(), datos="";
		for (var i=0, j=inputs.length - tope; i <= j; i++)
		{
			datos+=inputs[i].name+"="+inputs[i].value+"&";
		};
                console.dir(datos);
		id=null;
		return datos;
	}
        /**
	 * Recorre la tabla de disputas en notas de credito y actualiza el monto
	 * @access public
	 * @param id int es el id de la fila donde se encuentran los inputs
	 */
	function updateMontoAprobadoDisp()
	{
           $('.lista_Disp_NotaCEnv').children().children().each(function()
            {
                if($(this).attr('id')!== undefined){
                    $SORI.AJAX.actualizar($(this).attr('id'),'1');
                }
                
            });
        }
	/**
	 * Valida los input y select del formulario
	 * @access public
	 * @param  lleno
	 */
	function validaCampos(lleno)
	{
            for (var i=0, j=lleno.length - 2; i <= j; i++)
                {
                    if(lleno[i].value==""){
                        var respuesta=0;
                     }else{respuesta=1;}
                };
                if(respuesta==0){var msjIndicador = $("<div class='cargando'></div><div class='mensaje'><h3>Faltan datos por agregar</h3><p><p><p><p><p><p><p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
                                                    $("body").append(msjIndicador);msjIndicador.fadeIn('fast');setTimeout(function(){ msjIndicador.fadeOut('fast');msjIndicador.remove(4000); }, 1000);}
            return respuesta;
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
                    action = "GuardarNotaC_Env";
                    break
                case '8':
                    action = "GuardarNotaC_Rec";
                    break
            }
            return action;
        }

	/**
	 * retorna los metodos y variables publicos
	 */
	return{
		getData:getData,
                validaCampos:validaCampos,
		getURL:getURL,
		updateMontoAprobadoDisp:updateMontoAprobadoDisp
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

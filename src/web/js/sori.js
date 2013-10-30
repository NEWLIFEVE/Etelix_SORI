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
			if(i>=1 && i<=6)
			{
				$(input).datepicker();
			}
                        if(i>=7 && i<=8)
			{
				$(input).clockpick({ starthour: "00", endhour: "23", military: "TRUE" });
			} 
			obj[0].children[i].innerHTML="";
			obj[0].children[i].appendChild(input);
			input=null;
		}
		obj[0].children[13].innerHTML="";
		obj[0].children[13].innerHTML="<img name='save_Fac_Rec' alt='save' src='/images/icon_check.png'><img name='cancel_Fac_Rec' alt='cancel' src='/images/icon_arrow.png'>";
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
		obj[0].children[13].innerHTML="";
		obj[0].children[13].innerHTML="<img class='edit' name='edit_Fac_Rec' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'>";
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
	/**
	 * Metodo encargado de ejecutar las repectivas llamadas
	 * @access public
	 */
	function accion()
	{
		var $fila;
		$("img[name='edit'], img[name='edit_Pagos'], img[name='edit_Cobros'], img[name='edit_Fac_Env'], img[name='edit_Fac_Rec'], img[name='delete'], img[name='save_Pagos'], img[name='save_Cobros'], img[name='save_Fac_Env'], img[name='save_Fac_Rec'], img[name='cancel_Fac_Rec'], img[name='cancel_Fac_Env'], img[name='cancel_Pagos'], img[name='cancel_Cobros']").on('click',function()
		{
			$fila=$(this).parent().parent();
//                        GENERAL
			if($(this).attr('name')=="delete")
			{
				$fila.remove();
				$SORI.AJAX.borrar($fila[0].id);
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
//                        DISPUTAS RECIBIDAS
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
		});
		$fila=null;
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

	/**
	 * Retorna los mestodos publicos
	 */
	return{
		init:init,
		formChange:formChange
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
		id=null;
		return datos;
	}

	/**
	 * retorna los metodos y variables publicos
	 */
	return{
		getData:getData
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












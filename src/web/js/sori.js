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
			if(i>=2 && i<=6)
			{
				$(input).datepicker();
			}
			obj[0].children[i].innerHTML="";
			obj[0].children[i].appendChild(input);
			input=null;
		}
		obj[0].children[10].innerHTML="";
		obj[0].children[10].innerHTML="<img name='save' alt='save' src='/images/icon_check.png'><img name='cancel' alt='cancel' src='/images/icon_arrow.png'>";
		obj=null;
		accion();
	}

	/**
	 * Metodo encargado de regresar la fila a su estado normal si estuvo en estado de edicion
	 * @access private
	 * @param obj obj es el objeto de la fila que se esta manipulando
	 */
	function _revert(obj)
	{
		var contenido=new Array();
		for (var i=2, j=obj[0].childElementCount-2;i<=j;i++)
		{
			contenido[i]=obj[0].children[i].children[0].value;
			obj[0].children[i].children[0].remove();
			obj[0].children[i].innerHTML=contenido[i];
		}
		obj[0].children[10].innerHTML="";
		obj[0].children[10].innerHTML="<img class='edit' name='edit' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'>";
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
		$("img[name='edit'], img[name='delete'], img[name='save'], img[name='cancel']").on('click',function()
		{
			$fila=$(this).parent().parent();
			if($(this).attr('name')=="delete")
			{
				$fila.remove();
				$SORI.AJAX.borrar($fila[0].id);
			}
			if($(this).attr('name')=='edit')
			{
				_editar($fila);
			}
			if($(this).attr('name')=='save')
			{
				$SORI.AJAX.actualizar($fila[0].id);
				_revert($fila);
			}
			if($(this).attr('name')=='cancel')
			{
				_revert($fila);
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
		                $("#Contrato_sign_date").prop("disabled", true);
		            }
		            else
		            {
		                $("#Contrato_id_company").prop("disabled", false);
		                $("#Contrato_end_date").prop("disabled", true);
		                $("#Contrato_sign_date").prop("disabled", false)
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
		            $("#Contrato_id_limite_credito").val(obj.credito);
		            $("#credito_Oculto").val(obj.credito);
		            $("#Contrato_id_limite_compra").val(obj.compra);
		            $("#compra_Oculto").val(obj.compra);

		            var manageractual=(obj.manager), carrierenlabel=(obj.carrier),
		            	fechaManagerCarrier=(obj.fechaManager),
		            	managerA=$("<label><h3 style='margin-left: -66px; margin-top: \n\ 105px; color:rgba(111,204,187,1)'>"+manageractual+" / " +fechaManagerCarrier+" </h3></label><label><h6 style='margin-left: -66px; margin-top: \n\ -10px; '></h6></label>"),
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












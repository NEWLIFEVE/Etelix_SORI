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
	function _editarTP(obj, type)
	{
                var topCols=3; if(type==true) topCols=6;
		for (var i=0; i< obj[0].childElementCount-1; i++)
		{
			var select=document.createElement('select');  select.name=obj[0].children[i].id; select.value=obj[0].children[i].innerHTML; /*select.id=obj[0].children[i].id;*/
			var input=document.createElement('input');  input.name=obj[0].children[i].id;  input.value=obj[0].children[i].innerHTML;
                        var option=document.createElement("option"); option.value = "No Aplica"; option.text = option.value;
   
                        if(i==0 || i==3 || i==4 || i==5)
			{
                                if(i==0)  var parent="div.paymentTermnCustomer";
                                if(i==3)  var parent="div.periodo_fact";
                                if(i==4)  var parent="div.divide_fact";
                                if(i==5)  var parent="div.dia_ini_fact";
                                select.innerHTML=($(parent).children()[1].outerHTML);/*obtengo el html del select correspondiente*/
                                if(i!=0) select.add(option);                         /*agrego option 'No Aplica para los select fact period, month breack y first day'*/
                                for(var n=0; n<select.childElementCount;n++)
                                {
                                    select.children[n].value=select.children[n].innerHTML;
                                }
                                select.value=obj[0].children[i].innerHTML;
                                obj[0].children[i].innerHTML="";
                                obj[0].children[i].appendChild(select); 
                                if(i==3) for (var x=0, j=5;x<=j;x++)obj[0].children[3].children[0].children[x].style.display="block"; 
                        }
			if(i>=1 && i<=2)
			{
                            $(input).datepicker({ dateFormat: "yy-mm-dd"});
                            obj[0].children[i].innerHTML="";
                            obj[0].children[i].appendChild(input);
			}
                        $(input).css("height","inherit").css("width", "90%"); $(select).css("height","inherit").css("width", "90%").css("padding", "initial");
			select=input=null;
		}
		obj[0].children[topCols].innerHTML="";
		obj[0].children[topCols].innerHTML="<img name='saveTP' alt='save' src='/images/icon_check.png'><img name='cancelTP' alt='cancel' src='/images/icon_arrow.png'>";
		if(type==true)_adminObjTP(obj);
                obj=null;
		accion();
	}
        function _adminObjTP(obj)
        {
            $(obj[0].children[0].children[0]).on('change',function()
            {
                switch (obj[0].children[0].children[0].value){
                    case "Sin estatus":case "30/30":case "30/15": case "30/7": case "P-Mensuales":
                        obj[0].children[3].children[0].value="No Aplica";
                        obj[0].children[4].children[0].value="No Aplica";
                        obj[0].children[5].children[0].value="No Aplica";
                        break;
                    case "15/15":case "15/7":case "15/5": case "P-Quincenales":
                        obj[0].children[3].children[0].children[5].style.display="none"; 
                        obj[0].children[3].children[0].children[1].style.display="none"; 
                        obj[0].children[3].children[0].children[2].style.display="none"; 
                        obj[0].children[3].children[0].children[3].style.display="block"; 
                        obj[0].children[3].children[0].children[4].style.display="block"; 
                        obj[0].children[3].children[0].value="Seleccione";
                        obj[0].children[4].children[0].value="No Aplica";
                        obj[0].children[5].children[0].value="No Aplica";
                        break;
                    default:
                        obj[0].children[3].children[0].children[5].style.display="none"; 
                        obj[0].children[3].children[0].children[1].style.display="block"; 
                        obj[0].children[3].children[0].children[2].style.display="block"; 
                        obj[0].children[3].children[0].children[3].style.display="none"; 
                        obj[0].children[3].children[0].children[4].style.display="none"; 
                        obj[0].children[3].children[0].value="Seleccione";
                        obj[0].children[4].children[0].value="No Aplica";
                        obj[0].children[5].children[0].value="No Aplica";
                        break;
                }
            });
            $(obj[0].children[3].children[0]).on('change',function()
            {
                switch (obj[0].children[3].children[0].value){
                    case "Dia Semana(L/M/M/J/V/S/D)":
                        obj[0].children[4].children[0].value=" Seleccione ";
                        obj[0].children[5].children[0].value=" Seleccione ";
                        break;
                    default:
                        obj[0].children[4].children[0].value="No Aplica";
                        obj[0].children[5].children[0].value="No Aplica";
                        break;
                }
            });
        }
        function _revert_TP(obj, type)
	{
                var topCols=3; if(type==true) topCols=6;
		var contenido=new Array();
		for (var i=0, j=obj[0].childElementCount + -1;i<=j;i++)
		{
			contenido[i]=obj[0].children[i].children[0].value;
			obj[0].children[i].children[0].remove();
			obj[0].children[i].innerHTML=contenido[i];
		}
		obj[0].children[topCols].innerHTML="";
		obj[0].children[topCols].innerHTML="<img class='edit' name='editTP' alt='editar' src='/images/icon_lapiz.png'><img name='deleteTP' alt='borrar' src='/images/icon_x.gif'>";
		obj=contenido=null;
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
				$(input).datepicker({ dateFormat: "yy-mm-dd", maxDate: "-0D"});
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
				$(input).datepicker({ dateFormat: "yy-mm-dd", maxDate: "-0D"});
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
				$(input).datepicker({ dateFormat: "yy-mm-dd", maxDate: "-0D"});
			}
			obj[0].children[i].innerHTML="";
			obj[0].children[i].appendChild(input);
			input=null;
		}
		obj[0].children[6].innerHTML="";
		obj[0].children[6].innerHTML="<img name='save_Cobros' alt='save' src='/images/icon_check.png'><img name='cancel_Cobros' alt='cancel' src='/images/icon_arrow.png'>";
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
				$(input).datepicker({ dateFormat: "yy-mm-dd", maxDate: "-0D"});
			}
			obj[0].children[i].innerHTML="";
			obj[0].children[i].appendChild(input);
			input=null;
		}
		obj[0].children[6].innerHTML="";
		obj[0].children[6].innerHTML="<img name='save_Pagos' alt='save' src='/images/icon_check.png'><img name='cancel_Pagos' alt='cancel' src='/images/icon_arrow.png'>";
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

			if(i>=1 && i<=4)
			{
                            var input=document.createElement('input');
                            input.name=obj[0].children[i].id;
                            input.value=obj[0].children[i].innerHTML;
                        if(i>=2 && i<=2)
			{
			    $(input).datepicker({ dateFormat: "yy-mm-dd", maxDate: "-0D"});
			}
                            obj[0].children[i].innerHTML="";
                            obj[0].children[i].appendChild(input);
                            input=null;
                        }
		}
		obj[0].children[5].innerHTML="";
		obj[0].children[5].innerHTML="<img name='save_Nota_cred' alt='save' src='/images/icon_check.png'><img name='cancel_Nota_cred' alt='cancel' src='/images/icon_arrow.png'>";
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
		obj[0].children[6].innerHTML="";
		obj[0].children[6].innerHTML="<img class='edit' name='edit_Cobros' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'>";
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
		obj[0].children[6].innerHTML="";
		obj[0].children[6].innerHTML="<img class='edit' name='edit_Pagos' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'>";
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
                    if(i>=1 && i<=4)
			{
			contenido[i]=obj[0].children[i].children[0].value;
			obj[0].children[i].children[0].remove();
			obj[0].children[i].innerHTML=contenido[i];
                        }
		}
		obj[0].children[5].innerHTML="";
		obj[0].children[5].innerHTML="<img class='edit' name='edit_Nota_cred' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'>";
		obj=contenido=null;
		accion();
	}
        /**
         * 
         * @param {type} obj, type
         * @returns {undefined}
         */
        function _update_monto_Disp(obj,type)
        {
            for (var i=2, j=obj[0].childElementCount-2;i<=j;i++)
		{
                    obj[0].children[7].innerHTML = ((obj[0].children[3].innerHTML * obj[0].children[5].innerHTML).toFixed(2));
                    obj[0].children[8].innerHTML = ((obj[0].children[4].innerHTML * obj[0].children[6].innerHTML).toFixed(2));
                    if(type==1)
                        obj[0].children[9].innerHTML = ((obj[0].children[8].innerHTML - obj[0].children[7].innerHTML).toFixed(2));
                    else
                        obj[0].children[9].innerHTML = ((obj[0].children[7].innerHTML - obj[0].children[8].innerHTML).toFixed(2));
		}
                $SORI.AJAX.actualizar(obj[0].id , "1" , obj[0].children[9].innerHTML);
         }
         
//         defineAmountDisp($('#AccountingDocumentTemp_id_accounting_document').val(), amount_carrier, amount_etx)
         
        /**
        * escucha el click sobre la imagen delete y valida para eliminar el documento temporal
        * @param {type} $fila
        * @param {type} extra
        * @returns {undefined}          */
	function _elimina_doc($fila, extra)
	{  
            $('.cargando,.mensaje').remove();
            
            if(extra===true || extra===false)
              $SORI.UI.msj_confirm("<h3>Esta a punto de eliminar el historial termino pago seleccionado</h3>"); 
            else
              $SORI.UI.msj_confirm("<h3>Esta a punto de eliminar el documento contable seleccionado</h3>");  
          
            $('#confirma,#cancelar').on('click',function()
            {
                var tipo=$(this).attr('id');
                if(tipo=="confirma")
                {  
                   $('.cargando,.mensaje').fadeOut('slow'); 
                   var head=$("#"+$fila[0].id).parent().parent().parent();
                   $fila.remove();
                   var trs=head.children().children().children();
                    if(trs.length <= 1){
                     head.children().fadeOut(); head.children().fadeOut();
                     console.log("oculto el head");
                    }
                   $SORI.AJAX.borrar($fila[0].id, extra);
                }else{
                  $('.cargando,.mensaje').fadeOut('slow'); 
                }
            });
        }
	function _readonly(input,value)
	{  
                var input_cuestion= $("input[name='AccountingDocumentTemp["+input+"]']");

                if(input_cuestion.val() == value)
                {
                input_cuestion.attr('readonly', true);
                }
        }
	/**
	 * Metodo encargado de ejecutar las repectivas llamadas
	 * @access public
	 */
	function accion()
	{
		var $fila;
		$("img[name='editTP'],img[name='saveTP'],img[name='cancelTP'],img[name='deleteTP'],img[name='edit_DispRec'],img[name='edit_DispEnv'], img[name='edit_Nota_cred'],img[name='edit_Pagos'], img[name='edit_Cobros'], \n\
                   img[name='edit_Fac_Env'], img[name='edit_Fac_Rec'], img[name='delete'], img[name='save_Pagos'], img[name='save_DispRec'], img[name='save_DispEnv'],\n\
                   img[name='save_Cobros'], img[name='save_Fac_Env'], img[name='save_Nota_cred'],img[name='save_Fac_Rec'], img[name='cancel_Fac_Rec'], img[name='cancel_Fac_Env'], \n\
                   img[name='cancel_Pagos'], img[name='cancel_Cobros'], img[name='cancel_DispRec'], img[name='cancel_DispEnv'], img[name='cancel_Nota_cred']") .on('click',function()
		{
			$fila=$(this).parent().parent();
//                        GENERAL
			if($(this).attr('name')=="delete")
			{
                                _elimina_doc($fila, null);
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
				_readonly("amount_bank_fee","N/A");
			}
                        if($(this).attr('name')=='save_Cobros')
			{
				$SORI.AJAX.actualizar($fila[0].id,2);
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
                                _readonly("amount_bank_fee","N/A");
			}
			if($(this).attr('name')=='save_Pagos')
			{
				$SORI.AJAX.actualizar($fila[0].id,2);
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
				$SORI.AJAX.actualizar($fila[0].id,2);
				_revert_DispRec($fila);
				_update_monto_Disp($fila,1);
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
				$SORI.AJAX.actualizar($fila[0].id,2);
				_revert_DispEnv($fila);
				_update_monto_Disp($fila,2);
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
				$SORI.AJAX.actualizar($fila[0].id,2);
				_revert_Nota_cred($fila);
			}
			if($(this).attr('name')=='cancel_Nota_cred')
			{
				_revert_Nota_cred($fila);
			}
 //                       TERMINOS PAGOS
                        switch ($(this).attr('name')) {
                            case "editTP":  case "saveTP":  case "cancelTP": case "deleteTP": 
                                var type=false; if($(".formPaymentTermnsSupplier").css("display")!="none") type=true;
                                    console.log(type);
                                switch ($(this).attr('name')) {
                                    case "editTP":   _editarTP($fila, type);
                                        break; 
                                    case "saveTP":   $SORI.AJAX.actualizar($fila[0].id, type); _revert_TP($fila,type); 
                                        break; 
                                    case "cancelTP": _revert_TP($fila,type);
                                        break; 
                                    case "deleteTP": _elimina_doc($fila, type);
                                        break;
                                }
                            break;
                    }
		});
		$fila=type=null;
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
                var tipo_Ac_doc=$('#AccountingDocumentTemp_id_type_accounting_document').val(),
                    CarrierDisp=$('#AccountingDocumentTemp_id_carrier').val(),
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
                        $('.tabla_N_C,.montoDoc,.numDocument').fadeOut('fast');
                        if(data=="[]")
                        {     
                              $("#AccountingDocumentTemp_id_accounting_document").html("<option>No hay facturas registradas</option>").css('color','rgb(245, 105, 109)');  
                              $('select#AccountingDocumentTemp_doc_number').val("");
                        }else{
                            obj = JSON.parse(data);
                            $("#AccountingDocumentTemp_id_accounting_document").html("<option>Seleccione</option>").css('color','#777');
                            console.log(data);
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
        /**
         * 
         * @param {type} tipo
         * @returns {undefined}
         */
        


function roundNumber(number,decimals) 
{
	var newString;// The new rounded number
	decimals = Number(decimals);
	if (decimals < 1) {
		newString = (Math.round(number)).toString();
	} else {
		var numString = number.toString();
		if (numString.lastIndexOf(".") == -1) {// If there is no decimal point
			numString += ".";// give it one at the end
		}
		var cutoff = numString.lastIndexOf(".") + decimals;// The point at which to truncate the number
		var d1 = Number(numString.substring(cutoff,cutoff+1));// The value of the last decimal place that we'll end up with
		var d2 = Number(numString.substring(cutoff+1,cutoff+2));// The next decimal, after the last one we want
		if (d2 >= 5) {// Do we need to round up at all? If not, the string will just be truncated
			if (d1 == 9 && cutoff > 0) {// If the last digit is 9, find a new cutoff point
				while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {
					if (d1 != ".") {
						cutoff -= 1;
						d1 = Number(numString.substring(cutoff,cutoff+1));
					} else {
						cutoff -= 1;
					}
				}
			}
			d1 += 1;
			//newString = numString.substring(0,cutoff) + d1.toString();
		} //else {
			newString = numString.substring(0,cutoff) + d1.toString();// Just the string up to cutoff point
		//}
	}
	if (newString.lastIndexOf(".") == -1) {// Do this again, to the new string
		newString += ".";
	}

        return newString; // Output the result to the form field (change for your purposes)
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
                       if($('#AccountingDocumentTemp_id_accounting_document').val()!="5" && $('#AccountingDocumentTemp_id_accounting_document').val()!="6"){ 
                        $('.listaDisputas').remove();
                        if(data=="[]"){
                           //no hay datos y asi no muestra nada
     //                        $('.cargando,.mensaje').remove();
     //                        var msj=$("<div class='cargando'></div><div class='mensaje'><h3>No hay disputas para esta factura</h3><br><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();$("body").append(msj); msj.fadeIn('slow');setTimeout(function() { msj.fadeOut('slow'); }, 3000);
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
                                                                 <td id='AccountingDocumentTemp[dispute]'>" + roundNumber($SORI.UI.defineAmountDisp(tipo, obj[i].amount_carrier,obj[i].amount_etx), 2) + "</td>\n\
                                                                 <td id='AccountingDocumentTemp[monto_nota]'><input name='AccountingDocumentTemp[amount_aproved]' id='montoNota"+i+"' class='montoNota'  value=" + roundNumber(obj[i].dispute,2) + "></td>\n\
                                                             </tr>");
                             $('.fechaIniFact,.fechaFinFact').fadeOut(10);
                             $SORI.UI.changeCss('.numFactura','width','24%');
                             $('.lista_Disp_NotaCEnv,.numDocument,.Label_Disp_NotaCEnv, .montoDoc,.fechaDeEmision').fadeIn('fast');
                             $('#AccountingDocumentTemp_amount').attr('readonly', true);
                             $('#AccountingDocumentTemp_amount').val(montoTotal);
                             var acum = 0;
                             $('input.montoNota').each(function() {
                                 acum = acum + parseFloat($(this).val());
                                 $('#AccountingDocumentTemp_amount').val(acum);
                             });
                             sumMontoNota();
                         }
                         $("#AccountingDocumentTemp_amount").val(roundNumber($("#AccountingDocumentTemp_amount").val(),2));
                       } 
                       } 
                    }
                });
              }
          });
        }
        function defineAmountDisp(tipo, amount_carrier, amount_etx)
        {
            if (tipo === '7') {
               return amount_carrier - amount_etx;
            }else {
               return amount_etx - amount_carrier;
            }
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
            $('.lista_FacEnv,.lista_FacRec,.lista_Cobros,.lista_Pagos,.lista_DispRec,.lista_DispEnv,.lista_NotCredEnv,.lista_NotCredRec').children().children().children(":last-child").remove();
            var tr_fondoblanco=$(".vistaTemp").css('background','white').css('color','gray').css('font','small-caption'),

            html="Facturas Enviadas:        <table  style='font-weight:bold;color: white;width: 900px;background: rgb(111, 204, 187);border-bottom-color: white;'>" + $(".lista_FacEnv").clone(tr_fondoblanco).html() + "</table>" + "<br/>" + 
                 "Facturas Recibidas:       <table  style='font-weight:bold;color: white;width: 900px;background: rgb(161, 177, 171);border-bottom-color: white;'>" + $(".lista_FacRec").clone(tr_fondoblanco).html() + "</table>" + "<br/>" + 
                 "Cobros:                   <table  style='font-weight:bold;color: white;width: 900px;background: rgb(152, 198, 213);border-bottom-color: white;'>" + $(".lista_Cobros").clone(tr_fondoblanco).html() + "</table>" + "<br/>" + 
                 "Pagos:                    <table  style='font-weight:bold;color: white;width: 900px;background: rgb(226, 168, 140);border-bottom-color: white;'>" + $(".lista_Pagos").clone(tr_fondoblanco).html() + "</table>" + "<br/>" + 
                 "Disputas Recibidas:       <table  style='font-weight:bold;color: white;width: 900px;background: rgb(140, 170, 223);border-bottom-color: white;'>" + $(".lista_DispRec").clone(tr_fondoblanco).html() + "</table>" + "<br/>" + 
                 "Disputas Enviadas:        <table  style='font-weight:bold;color: white;width: 900px;background: rgb(20, 121, 121); border-bottom-color: white;'>" + $(".lista_DispEnv").clone(tr_fondoblanco).html() + "</table>" + "<br/>" + 
                 "Notas de Crédito Enviadas:<table  style='font-weight:bold;color: white;width: 900px;background: rgb(172, 208, 212);border-bottom-color: white;'>" + $(".lista_NotCredEnv").clone(tr_fondoblanco).html() + "</table>" + "<br/>" + 
                 "Notas de Crédito Recibidas:<table style='font-weight:bold;color: white;width: 900px;background: rgb(189, 170, 194);border-bottom-color: white;'>" + $(".lista_NotCredRec").clone(tr_fondoblanco).html() + "</table>" + "<br/>";
            
            $("#html").val(html);
            $("#FormularioCorreo").submit();
            $SORI.UI.msj_cargando("<h2>Enviando documentos por correo</h2><p>Espere un momento por favor<p>","image_464753.gif");
        });
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
//            $('#'+id).change(function()
//            {     
                    $.ajax({
                        type: "GET",
                        url: "DynamicDatosContrato",
                        data: "idCarrier="+$(id).val(),
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
                                $("#Contrato_sign_date,#Contrato_id_company").prop("disabled", false);
                                $("#Contrato_end_date").prop("disabled", true);
                            }
                            $("#Contrato_sign_date").val(obj.sign_date);
                            $("#Contrato_production_date").val(obj.production_date);
                            $("#Contrato_id_termino_pago").val(obj.termino_pago);
                            $("#Contrato_start_date_TP_customer").val(obj.start_date_TP_customer);
                            $("#Contrato_id_termino_pago_supplier").val(obj.termino_pago_supplier);
                            $("#Contrato_start_date_TP_supplier").val(obj.start_date_TP_supplier);
                            $("#Contrato_id_fact_period").val(obj.fact_period);
                            $("#dia_ini_fact").val(obj.dia_ini_fact);
                            $("#divide_fact").val(obj.divide_fact);
                            $("#Contrato_id_monetizable").val(obj.monetizable);
                            $("#Contrato_id_managers").val(obj.manager);
                            $("#Contrato_id_disputa").val(obj.dias_disputa);
                            $("#Contrato_id_disputa_solved").val(obj.dias_disputa_solved);
                            $("#Contrato_up").val(obj.Contrato_up);
                            $("#Contrato_bank_fee").val(obj.bank_fee);
                            $("#Contrato_status").val(obj.Contrato_status);
                            $("#Contrato_statusOculto").val(obj.Contrato_status);
                            $("#Contrato_upOculto").val(obj.Contrato_up);
                            $("#bank_feeOculto").val(obj.bank_fee);
                            $("#F_Firma_Contrato_Oculto").val(obj.sign_date);
                            $("#F_P_produccion_Oculto").val(obj.production_date);
                            $("#TerminoP_Oculto").val(obj.termino_pago);
//                            $("#start_date_TP_cus_Oculto").val(obj.start_date_TP_customer);
                            $("#TerminoPViews").val($("#Contrato_id_termino_pago  option:selected").html());
                            $("#TerminoP_supplier_Oculto").val(obj.termino_pago_supplier);
//                            $("#start_date_TP_cus_Oculto").val(obj.start_date_TP_supplier);
                            $("#id_fact_period_Oculto").val(obj.fact_period);
                            $("#dia_ini_fact_Oculto").val(obj.dia_ini_fact);
                            $("#divide_fact_Oculto").val(obj.divide_fact);
                            $("#monetizable_Oculto").val(obj.monetizable);
                            $("#dias_disputa_Oculto").val(obj.dias_disputa);
                            $("#dias_disputa_solved_Oculto").val(obj.dias_disputa_solved);
                            $("#Contrato_id_limite_credito").val(obj.credito);
                            $("#credito_Oculto").val(obj.credito);
                            $("#Contrato_id_limite_compra").val(obj.compra);
                            $("#compra_Oculto").val(obj.compra);
                            $('.hManagerA').html(""+obj.manager+" / " +obj.fechaManager+"");
                            $('.hCarrierA').html(""+obj.carrier+"");
                            
                            $SORI.UI.resuelveTP(obj.termino_pago_supplier);
                            $SORI.UI.resuelvePeriodo(obj.fact_period); 
                            $('.manageractual').show("slow");
                            $('.note').fadeIn("slow");
                            $('.pManager').slideDown("slow");
                            $('.formularioContrato').slideDown("slow");
                            $('.CarrierActual').slideDown("slow");
                        }
                    });
//            });
	}
        /**
         * 
         * @param {type} tp
         * @returns {undefined}
         */
        function resuelveTP(tp)
        {   
            $("#TerminoPViewsS").val($("#Contrato_id_termino_pago_supplier  option:selected").html());
            var periodo_semanal=["#Contrato_id_fact_period option[value='1']","#Contrato_id_fact_period option[value='2']"],
             periodo_quincenal = ["#Contrato_id_fact_period option[value='3']","#Contrato_id_fact_period option[value='4']"];
             $("#Contrato_id_fact_period option[value='5']").hide("fast");
            switch (tp)
            {
                case "1": case "3": case "4": case "5":case 1: case 3: case 4: case 5:
                   $(".periodo_fact").css("display","inline-block").hide().show("slow");
                   $SORI.UI.formChangeAccDoc(periodo_quincenal, periodo_semanal);
                    break;
                case "6": case "7": case "8":case "12": case 6: case 7: case 8:case 12:
                   $(".periodo_fact").css("display","inline-block").hide().show("slow");
                   $(".dia_ini_fact,.divide_fact").hide("slow");
                   $SORI.UI.formChangeAccDoc(periodo_semanal, periodo_quincenal);
                    break;
                case "2": case "9": case "10": case "11": case "13":case 2: case 9: case 10: case 11: case 13:
                    $("#Contrato_id_fact_period").val("5"); $("#dia_ini_fact,#divide_fact").val("");$(".divide_fact,.periodo_fact,.dia_ini_fact").hide("slow");
                    break;
            } 
        }
        /**
         * 
         * @param {type} fact_period
         * @returns {undefined}
         */
        function resuelvePeriodo(fact_period)
        {    
            var periodo_semanal=["#Contrato_id_fact_period option[value='1']","#Contrato_id_fact_period option[value='2']"],
             periodo_quincenal = ["#Contrato_id_fact_period option[value='3']","#Contrato_id_fact_period option[value='4']"];
            switch (fact_period)
            {
                case "1":case 1:
                   $SORI.UI.formChangeAccDoc(periodo_quincenal, periodo_semanal);
                   $(".divide_fact,.dia_ini_fact").hide("slow");$("#dia_ini_fact,#divide_fact").val("");
                    break;
                case "2":case 2:
                   $SORI.UI.formChangeAccDoc(periodo_quincenal, periodo_semanal);
                   $(".dia_ini_fact,.divide_fact,.periodo_fact").css("display","inline-block").hide().show("slow");
                    break;
                case "3": case "4": case 3: case 4: 
                   $SORI.UI.formChangeAccDoc(periodo_semanal, periodo_quincenal);
                   $(".dia_ini_fact,.divide_fact").hide("slow");
                   $("#dia_ini_fact,#divide_fact").val("");
                    break;
                case "":case 5:
                   $(".dia_ini_fact,.divide_fact").hide("slow");
                   $("#dia_ini_fact,#divide_fact").val("");
                    break;
                case null:
                   $(".dia_ini_fact,.divide_fact,.periodo_fact").hide("slow");
                   $("#dia_ini_fact,#divide_fact").val("");
                    break;
            } 
        }
        /**
         * 
         * @param {type} obj
         * @returns {undefined}
         */
        function llenarTabla(obj){
            console.dir(obj);
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
                /*tema para disputas */
                if(amount_etx !== undefined && amount_carrier !== undefined){ 
                    if(obj.id_type_accounting_document=="6") 
                        var dispute="<td id='AccountingDocumentTemp[amount_carrier]'>"+((obj.min_carrier*obj.rate_carrier)-(obj.min_etx*obj.rate_etx)).toFixed(2)+"</td>";
                    else if(obj.id_type_accounting_document=="5") 
                        var dispute="<td id='AccountingDocumentTemp[amount_carrier]'>"+((obj.min_etx*obj.rate_etx)-(obj.min_carrier*obj.rate_carrier)).toFixed(2)+"</td>";
                }
                /**/
                if(obj.amount_bank_fee !== undefined) var amount_bank_fee="<td id='AccountingDocumentTemp[amount_bank_fee]'>"+obj.amount_bank_fee+"</td>"; 
               
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
                        var tabla = id+group+issue_date+doc_number+amount+amount_bank_fee+currency+"<td><img class='edit' name='edit_Pagos' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td></tr>",
                        clase=".lista_Pagos",
                        label='.LabelPagos';
                        break
                    case '4':
                        var tabla = id+group+valid_received_date+doc_number+amount+amount_bank_fee+currency+"<td><img class='edit' name='edit_Cobros' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td></tr>",
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
                        var tabla = id+carrier+fact_number+issue_date+doc_number+amount+"<td><img class='edit' name='edit_Nota_cred' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td></tr>",
                        clase=".lista_NotCredEnv",
                        label='.Label_NotCredEnv';
                        break
                    case '8':
                        var tabla = id+carrier+fact_number+issue_date+doc_number+amount+"<td><img class='edit' name='edit_Nota_cred' alt='editar' src='/images/icon_lapiz.png'><img name='delete' alt='borrar' src='/images/icon_x.gif'></td></tr>",
                        clase=".lista_NotCredRec",
                        label='.Label_NotCredRec';
                        break
                }
                $(clase).find("tr:first").after(tabla);
                $(clase).fadeIn('slow');
                $(label).fadeIn('slow');
                $('#botAgregarDatosContableFinal, .botonesParaExportar').fadeIn('slow');
        }
        /**
         * 
         * @param {type} obj
         * @returns {undefined}
         */
         function MensajeYaExiste(obj){
             $('.cargando, .mensaje').remove();
             switch (obj.id_type_accounting_document){
                    case '1':
                         var msj="<h4 align='justify'>Ya existe una<b> Factura Enviada</b> con el N°. <b>"+obj.doc_number+"</b> emitida desde la compañia: <b>"+obj.company_name+"</b></h4>";
                        break
                    case '2':
                         var msj="<h4 align='justify'>La <b>Factura Recibida </b>que intenta guardar, ya se encuentra registrada con el carrier <b>"+obj.carrier+"</b>, en el periódo <b>"+obj.from_date+" / "+obj.to_date+"</b>, con el N°. <b>"+obj.doc_number+"</b></h4>";
                        break
                    case '3':
                         var msj="<h4 align='justify'>El <b>Pago</b> que intenta guardar, ya se encuentra registrado con el grupo <b>"+obj.group+"</b>, con fecha de emisión <b>"+obj.issue_date+"</b> y bajo el N°. <b>"+obj.doc_number+"</b></h4>";
                        break
                    case '4':
                        var msj="<h4 align='justify'>El <b>Grupo</b> que intenta guardar, ya se encuentra registrado con el grupo <b>"+obj.group+"</b>, con fecha de recepción <b>"+obj.valid_received_date+"</b> y bajo el N°. <b>"+obj.doc_number+"</b></h4>";
                        break
                    case '5':
                         var msj="<h4 align='justify'>La <b>Disputa Recibida</b> que intenta guardar, ya se encuentra registrada con el destino <b>"+obj.destination+"</b>, con fecha de recepción en el periódo <b>"+obj.from_date+" / "+obj.to_date+"</b> , bajo el N° de factura. <b>"+obj.fact_number+"</b></h4>";
                        break
                    case '6':
                         var msj="<h4 align='justify'>La <b>Disputa Recibida</b> que intenta guardar, ya se encuentra registrada con el destino <b>"+obj.destinationSupp+"</b>, con fecha de recepción en el periódo <b>"+obj.from_date+" / "+obj.to_date+"</b> , bajo el N° de factura. <b>"+obj.fact_number+"</b></h4>";
                        break
                    case '7':
                         var msj="<h4 align='justify'>La <b>Disputa Recibida</b> que intenta guardar, ya se encuentra registrada con el destino <b>"+obj.destination+"</b>, con fecha de recepción en el periódo <b>"+obj.from_date+" / "+obj.to_date+"</b> , bajo el N° de factura. <b>"+obj.fact_number+"</b> y N°. de documento  <b>"+obj.doc_number+"</b></h4>";
                        break
                    case '8':
                        var msj="<h4 align='justify'>La <b>Disputa Recibida</b> que intenta guardar, ya se encuentra registrada con el destino <b>"+obj.destinationSupp+"</b>, con fecha de recepción en el periódo <b>"+obj.from_date+" / "+obj.to_date+"</b> , bajo el N° de factura. <b>"+obj.fact_number+"</b> y N°. de documento  <b>"+obj.doc_number+"</b></h4>";
                        break
             }
              $SORI.UI.msj_cargando("","");$SORI.UI.msj_change(msj,"aguanta.png","2000","width:40px; height:90px;"); 
         }
        /**
         * 
         * @returns {undefined}
         */
         function sesionCerrada()
         {
             $SORI.UI.msj_cargando("<h2>Su sesión ha expirado</h2>por favor presione aceptar y vuelva a ingresar<p><div class='cerradalasesion'><a class='relogin' href='/site/logout'>Aceptar</a></div>","white.png");
             $('.relogin').click('on',function() { $(".cargando,.mensaje").fadeOut('slow'); }); 
         }
        /**
         * 
         * @returns {undefined}
         */
        function emptyFields(){
            $("#AccountingDocumentTemp_note, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_minutes, #AccountingDocumentTemp_id_destination_supplier, #AccountingDocumentTemp_minutes, #AccountingDocumentTemp_min_carrier, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_rate_carrier, #AccountingDocumentTemp_id_destination").val('');
                if (obj.id_type_accounting_document=='3'||obj.id_type_accounting_document=='4'){
                     $("#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_issue_date,#AccountingDocumentTemp_valid_received_date,#AccountingDocumentTemp_bank_fee").val('');
                } 
                if (obj.id_type_accounting_document=='5'||obj.id_type_accounting_document=='6'){
                     $("#AccountingDocumentTemp_min_etx,AccountingDocumentTemp_min_carrier,#AccountingDocumentTemp_rate_etx,#AccountingDocumentTemp_rate_carrier,#AccountingDocumentTemp_select_dest_supplier,#AccountingDocumentTemp_input_dest_supplier").val('');
                } 
                if (obj.id_type_accounting_document=='7'||obj.id_type_accounting_document=='8'){
                     $("#AccountingDocumentTemp_from_date,#AccountingDocumentTemp_to_date,#AccountingDocumentTemp_id_accounting_document,#AccountingDocumentTemp_doc_number,#AccountingDocumentTemp_issue_date").val('');
                     $('.tabla_N_C,.numDocument,.montoDoc,.fechaDeEmision').fadeOut("fast");$('.listaDisputas').remove();
                     $('.fechaIniFact,.fechaFinFact').fadeIn('fast');
                     $SORI.UI.changeCss('.numFactura','width','51%');
                } 
        }
        /**
         * 
         * @param {type} clase
         * @param {type} attr
         * @param {type} value
         * @returns {undefined}
         */
        function changeCss(clase,attr,value){
            $(clase).css(attr,value);
        }
        /**
         * 
         * @returns {undefined}
         */
        function coloursIfChange()
        {
           if($("#TerminoP_Oculto").val() != $("#Contrato_id_termino_pago").val() && $("#TerminoP_Oculto").val()!="")                           $SORI.UI.changeCss($('.termino_pName'),'color','red');
           if($("#TerminoP_supplier_Oculto").val() != $("#Contrato_id_termino_pago_supplier").val() && $("#TerminoP_supplier_Oculto").val()!="")$SORI.UI.changeCss($('.termino_p_supp_Name'),'color','red');
           if($("#id_fact_period_Oculto").val() != $("#Contrato_id_fact_period").val() && $("#id_fact_period_Oculto").val()!="")                $SORI.UI.changeCss($('.fact_period_Name'),'color','red');
           if($("#dia_ini_fact_Oculto").val() != $("#dia_ini_fact").val() && $("#dia_ini_fact_Oculto").val()!="")                               $SORI.UI.changeCss($('.dia_ini_fact_Name'),'color','red');
           if($("#divide_fact_Oculto").val() != $("#divide_fact").val() && $("#divide_fact_Oculto").val()!="")                                  $SORI.UI.changeCss($('.divide_fact_Name'),'color','red');
           if($("#monetizable_Oculto").val() != $("#Contrato_id_monetizable").val() && $("#monetizable_Oculto").val()!="")                      $SORI.UI.changeCss($('.monetizableName'),'color','red');
           if($("#dias_disputa_Oculto").val() != $("#Contrato_id_disputa").val() && $("#dias_disputa_Oculto").val()!="")                        $SORI.UI.changeCss($('.dias_disputa'),'color','red');
           if($("#dias_disputa_solved_Oculto").val() != $("#Contrato_id_disputa_solved").val() && $("#dias_disputa_solved_Oculto").val()!="")   $SORI.UI.changeCss($('.dias_disputa_solved'),'color','red');
           if($("#F_Firma_Contrato_Oculto").val() != $("#Contrato_sign_date").val() && $("#F_Firma_Contrato_Oculto").val()!="")                 $SORI.UI.changeCss($('.sign_date'),'color','red');
           if($("#F_P_produccion_Oculto").val() != $("#Contrato_production_date").val() && $("#F_P_produccion_Oculto").val()!="")               $SORI.UI.changeCss($('.production_date'),'color','red'); 
           if($("#credito_Oculto").val() != $("#Contrato_id_limite_credito").val() && $("#credito_Oculto").val()!="")                           $SORI.UI.changeCss($('.credito'),'color','red');
           if($("#compra_Oculto").val() != $("#Contrato_id_limite_compra").val() && $("#compra_Oculto").val()!="")                              $SORI.UI.changeCss($('.compra'),'color','red');
           if($("#Contrato_upOculto").val() != $("#Contrato_up").val() && $("#Contrato_upOculto").val()!="")                                    $SORI.UI.changeCss($('.Contrato_upC'),'color','red');
           if($("#Contrato_statusOculto").val() != $("#Contrato_status").val()&&$("#Contrato_statusOculto").val()!="")                          $SORI.UI.changeCss($('.Contrato_StatusC'),'color','red');
           if($("#bank_feeOculto").val() != $("#Contrato_bank_fee").val() && $("#bank_feeOculto").val()!="")                                    $SORI.UI.changeCss($('.bank_feeName'),'color','red');
        }
        /**
         * 
         * @returns {undefined}
         */
        function sumMontoNota() 
        {
            $('input.montoNota').change(function()
            {
                var acum = 0;
                $('input.montoNota').each(function() {
                    acum = acum + parseFloat(roundNumber($(this).val(),2));
                    $(this).val(roundNumber($(this).val(),2));
                    $(this).parent().attr('id');
                    console.log(acum);
                    $('input#AccountingDocumentTemp_amount').val(roundNumber(acum,2));
                });
            });
        }
        /**
         * 
         * @param {type} tipo
         * @returns {unresolved}
         */
	function seleccionaCampos(tipo)
	{  
           switch (tipo){
            case '1'://facturas enviadas
                var respuesta=$SORI.UI.validaCampos($('#AccountingDocumentTemp_id_carrier,#AccountingDocumentTemp_issue_date,#AccountingDocumentTemp_from_date,#AccountingDocumentTemp_to_date,#AccountingDocumentTemp_minutes,#AccountingDocumentTemp_doc_number,#AccountingDocumentTemp_amount').serializeArray());
                break 
            case '2'://facturas recibidas
                var respuesta=$SORI.UI.validaCampos($('#AccountingDocumentTemp_id_carrier,#AccountingDocumentTemp_issue_date,#AccountingDocumentTemp_from_date,#AccountingDocumentTemp_to_date,#AccountingDocumentTemp_email_received_date,#AccountingDocumentTemp_email_received_hour,#AccountingDocumentTemp_minutes,#AccountingDocumentTemp_doc_number,#AccountingDocumentTemp_amount').serializeArray());
                break 
            case '3'://pagos
//                var respuesta=$SORI.UI.validaCampos($('#AccountingDocumentTemp_carrier_groups,#AccountingDocumentTemp_issue_date,#AccountingDocumentTemp_doc_number,#AccountingDocumentTemp_amount').serializeArray());
                var respuesta=$SORI.UI.validaCampos($('#AccountingDocumentTemp_carrier_groups,#AccountingDocumentTemp_issue_date,#AccountingDocumentTemp_amount').serializeArray());
                break 
            case '4'://cobros
//                var respuesta=$SORI.UI.validaCampos($('#AccountingDocumentTemp_carrier_groups,#AccountingDocumentTemp_valid_received_date,#AccountingDocumentTemp_doc_number,#AccountingDocumentTemp_amount').serializeArray());
                var respuesta=$SORI.UI.validaCampos($('#AccountingDocumentTemp_carrier_groups,#AccountingDocumentTemp_valid_received_date,#AccountingDocumentTemp_amount').serializeArray());
                break 
            case '5'://disputas recibidas
                var respuesta=$SORI.UI.validaCampos($('#AccountingDocumentTemp_id_carrier,#AccountingDocumentTemp_from_date,#AccountingDocumentTemp_to_date,#AccountingDocumentTemp_id_accounting_document,#AccountingDocumentTemp_id_destination,#AccountingDocumentTemp_min_etx,#AccountingDocumentTemp_min_carrier,#AccountingDocumentTemp_rate_etx,#AccountingDocumentTemp_rate_carrier').serializeArray());
                break 
            case '6'://disputas enviadas
                var respuesta=$SORI.UI.validaCampos($('#AccountingDocumentTemp_id_carrier,#AccountingDocumentTemp_from_date,#AccountingDocumentTemp_to_date,#AccountingDocumentTemp_id_accounting_document,#AccountingDocumentTemp_min_etx,#AccountingDocumentTemp_min_carrier,#AccountingDocumentTemp_rate_etx,#AccountingDocumentTemp_rate_carrier').serializeArray());
                break 
            case '7'://notas de credito enviadas
                var respuesta=$SORI.UI.validaCampos($('#AccountingDocumentTemp_id_carrier,#AccountingDocumentTemp_issue_date,#AccountingDocumentTemp_from_date,#AccountingDocumentTemp_to_date,#AccountingDocumentTemp_id_accounting_document,#AccountingDocumentTemp_doc_number,#AccountingDocumentTemp_amount').serializeArray());
                break 
            case '8'://notas de credito recibidas

                var respuesta=$SORI.UI.validaCampos($('#AccountingDocumentTemp_id_carrier,#AccountingDocumentTemp_issue_date,#AccountingDocumentTemp_from_date,#AccountingDocumentTemp_to_date,#AccountingDocumentTemp_id_accounting_document,#AccountingDocumentTemp_doc_number,#AccountingDocumentTemp_amount').serializeArray());
                break 
            //esta parte aplica solo para contrato
            case 'tp_supplier':
                var respuesta=$SORI.UI.validaContratoTpSemanal($("#Contrato_id_termino_pago_supplier").val());
                break
            case 'general':
                var respuesta=$SORI.UI.validaCampos($('#Contrato_id_monetizable,#Contrato_id_termino_pago,#Contrato_id_termino_pago_supplier,#Contrato_id_company,#Contrato_id_limite_credito,#Contrato_id_limite_compra,#Contrato_up,#Contrato_bank_fee').serializeArray());
                break
           }
           return respuesta;
        }
        /**
         * 
         * @param {type} tp
         * @returns {Number}
         */
        function validaContratoTpSemanal(tp)
        {
            if(tp=="1"||tp=="3"||tp=="4"||tp=="5"||tp=="6"||tp=="7"||tp=="8"||tp=="12")
            {
                if(tp=="1"||tp=="3"||tp=="4"||tp=="5") {
                    if($("#Contrato_id_fact_period").val()=="1"){
                            var respuesta=$SORI.UI.validaCampos($('#Contrato_id_fact_period').serializeArray());
                        }else{
                            var respuesta=$SORI.UI.validaCampos($('#divide_fact,#Contrato_id_fact_period,#dia_ini_fact').serializeArray());
                        }
                }else{                              
                    var respuesta=$SORI.UI.validaCampos($('#Contrato_id_fact_period').serializeArray());
                }
            }else{
                var respuesta=1; 
            }
            return respuesta;
        }
         /**
	 * Valida los input y select del formulario- 1
	 * @access public
	 * @param  campos
	 */
	function validaCampos(campos)
	{  
            for (var i=0, j=campos.length - 1; i <= j; i++)
                {
                    if(campos[i].value==""){
                        console.dir(campos[i]);
                        var respuesta=0;
                        break;
                     }else{respuesta=1;}
                };
                if(respuesta==0){$SORI.UI.msj_cargando("","");$SORI.UI.msj_change("<h3>Faltan datos por agregar</h3>","aguanta.png","1000","width:40px; height:90px;"); }
                return respuesta;
        }
        
        function msj_cargando(cuerpo_msj,imagen)
        {
            imagen_url="";
            if(imagen!=null||imagen!="")var imagen_url="<img src='/images/"+imagen+"'>";;
            $(".cargando, .mensaje").remove();
            var msj=$("<div class='cargando'></div><div class='mensaje'>"+cuerpo_msj+"<p><br>"+imagen_url+"</div>").hide(); 
            $("body").append(msj); msj.fadeIn('slow');
        }
        /**
         * 
         * @param {type} cuerpo_msj
         * @returns {undefined}
         */
        function msj_confirm(cuerpo_msj)
        {
            $(".cargando, .mensaje").remove();
            var msj=$("<div class='cargando'></div><div class='mensaje'>"+cuerpo_msj+"<p>Si esta de acuerdo, presione Aceptar, de lo contrario Cancelar<p><p><div id='cancelar'class='cancelar'><b>Cancelar</b></div>&nbsp;<div id='confirma'class='confirma'><b>Aceptar</b></div></div></div>").hide(); 
            $("body").append(msj);  msj.fadeIn('slow');
        }
        /**
         * 
         * @param {type} cuerpo_msj
         * @param {type} imagen
         * @param {type} tiempo
         * @param {type} style
         * @returns {undefined}
         */
        function msj_change(cuerpo_msj,imagen,tiempo,style)
        {
            $(".mensaje").html(""+cuerpo_msj+"<p><img style='"+style+"' src='/images/"+imagen+"'>");
            setTimeout(function() { $(".cargando, .mensaje").fadeOut('slow'); }, tiempo);
        }
        /**
         * 
         * @param {type} tipoDocument
         * @returns {undefined}
         */
        
        function elijeOpciones(tipoDocument)
	{
            var ocultar =['.tabla_N_C,.CarrierDocument','.GrupoDocument','.emailReceivedDate','.validReceivedDate','.fechaDeEmision','.fechaIniFact','.fechaFinFact','.emailReceivedTime','.minutosDoc','.minutosEtx','.minutosProveedor','.DestinoEtx','.DestinoProv','.Moneda','select#AccountingDocumentTemp_id_destination_supplier','input#AccountingDocumentTemp_id_destination_supplier','.montoDoc','.numDocument','.numFactura','.rateEtx','.rateProveedor,.bank_fee'];
            switch (tipoDocument){
                case '1'://facturas enviadas
                    var mostrar =['.numDocument','.montoDoc','.Moneda','.fechaDeEmision','.fechaIniFact','.fechaFinFact','.CarrierDocument','.minutosDoc'];
                    $SORI.UI.formChangeAccDoc(ocultar, mostrar);
                    $("#AccountingDocumentTemp_email_received_date,#AccountingDocumentTemp_email_received_hour,#AccountingDocumentTemp_issue_date").val('');
                    $("#AccountingDocumentTemp_from_date,#AccountingDocumentTemp_to_date,#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_id_destination_supplier").val('');
                    $("#AccountingDocumentTemp_minutes, #AccountingDocumentTemp_min_carrier, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_rate_carrier, #AccountingDocumentTemp_id_destination").val('');
                    break
                case '2'://facturas recibidas
                    var mostrar =['.numDocument','.montoDoc','.Moneda','.fechaDeEmision','.fechaIniFact','.fechaFinFact','.emailReceivedDate','.emailReceivedTime','.CarrierDocument','.minutosDoc'];
                    $SORI.UI.formChangeAccDoc(ocultar, mostrar);
                    $("#AccountingDocumentTemp_issue_date,#AccountingDocumentTemp_from_date,#AccountingDocumentTemp_to_date,#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_id_destination_supplier").val('');
                    $("#AccountingDocumentTemp_minutes, #AccountingDocumentTemp_min_carrier, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_rate_carrier, #AccountingDocumentTemp_id_destination").val('');
                    break
                case '3'://pago
                    var mostrar =['.numDocument','.montoDoc','.Moneda','.fechaDeEmision','.GrupoDocument'];
                    $SORI.UI.formChangeAccDoc(ocultar, mostrar);
                    $("#AccountingDocumentTemp_email_received_date,#AccountingDocumentTemp_email_received_hour,#AccountingDocumentTemp_id_carrier,#AccountingDocumentTemp_issue_date").val('');
                    $("#AccountingDocumentTemp_from_date,#AccountingDocumentTemp_to_date,#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_id_destination_supplier, #AccountingDocumentTemp_carrier_groups").val('');
                    $("#AccountingDocumentTemp_minutes, #AccountingDocumentTemp_min_carrier, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_rate_carrier, #AccountingDocumentTemp_id_destination").val('');
                    break
                case '4'://cobro
                    var mostrar =['.numDocument','.montoDoc','.Moneda','.validReceivedDate','.GrupoDocument'];
                    $SORI.UI.formChangeAccDoc(ocultar, mostrar);
                    $("#AccountingDocumentTemp_email_received_date,#AccountingDocumentTemp_email_received_hour,#AccountingDocumentTemp_issue_date, #AccountingDocumentTemp_carrier_groups").val('');
                    $("#AccountingDocumentTemp_issue_date,#AccountingDocumentTemp_from_date,#AccountingDocumentTemp_to_date,#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_id_destination_supplier").val('');
                    $("#AccountingDocumentTemp_minutes, #AccountingDocumentTemp_min_carrier, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_rate_carrier, #AccountingDocumentTemp_id_destination").val('');
                    break
                case '5'://disputas recibidas
                    var mostrar =['.numFactura','.fechaIniFact','.fechaFinFact','.CarrierDocument','.minutosEtx','.minutosProveedor','.DestinoEtx','.rateEtx','.rateProveedor'];
                    $SORI.UI.formChangeAccDoc(ocultar, mostrar);
                    $SORI.UI.changeCss('.numFactura','width','24%');
                    $("#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date, #AccountingDocumentTemp_id_destination,#AccountingDocumentTemp_minutes, #AccountingDocumentTemp_min_carrier, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_rate_carrier").val('');
                    $("#AccountingDocumentTemp_id_accounting_document").html("");
                    $SORI.UI.buscaFactura('#AccountingDocumentTemp_id_carrier, #AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date');
                    break
                case '6'://Disputa enviada
                    var mostrar =['.numFactura','.fechaIniFact','.fechaFinFact','.CarrierDocument','.minutosEtx','.minutosProveedor','.DestinoProv','.rateEtx','.rateProveedor','select#AccountingDocumentTemp_id_destination_supplier'];
                    $SORI.UI.formChangeAccDoc(ocultar, mostrar);
                    $SORI.UI.changeCss('.numFactura','width','24%');
                    $("#AccountingDocumentTemp_doc_number, #AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date, #AccountingDocumentTemp_id_destination, #AccountingDocumentTemp_minutes, #AccountingDocumentTemp_min_carrier, #AccountingDocumentTemp_amount, #AccountingDocumentTemp_rate_carrier, #AccountingDocumentTemp_bank_fee").val('');
                    $("#AccountingDocumentTemp_id_accounting_document").html("");
                    $SORI.UI.buscaFactura('#AccountingDocumentTemp_id_carrier, #AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date');
                    $SORI.UI.toggleDestProv();
                    break
                case '7'://Nota de credito enviada
                    var mostrar =['.numFactura','.fechaIniFact','.fechaFinFact','.CarrierDocument'];
                    $SORI.UI.formChangeAccDoc(ocultar, mostrar);
                    $SORI.UI.changeCss('.numFactura','width','51%');
                    $SORI.UI.buscaFactura('#AccountingDocumentTemp_id_carrier, #AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date');
                    $("#AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date, #AccountingDocumentTemp_id_accounting_document, #AccountingDocumentTemp_id_carrier, #AccountingDocumentTemp_id_accounting_document").val('');
                    $("#AccountingDocumentTemp_id_accounting_document").html(""); 
                    $SORI.UI.sumMontoNota();
                    $SORI.UI.buscaDisputa(tipoDocument);
                    break
                case '8'://Nota de credito recibida
                    var mostrar =['.numFactura','.fechaIniFact','.fechaFinFact','.CarrierDocument'];
                    $SORI.UI.formChangeAccDoc(ocultar, mostrar);
                    $SORI.UI.changeCss('.numFactura','width','51%');
                    $SORI.UI.buscaFactura('#AccountingDocumentTemp_id_carrier, #AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date');
                    $("#AccountingDocumentTemp_from_date, #AccountingDocumentTemp_to_date, #AccountingDocumentTemp_id_accounting_document, #AccountingDocumentTemp_id_carrier").val('');
                    $("#AccountingDocumentTemp_id_accounting_document").html("");
                    $SORI.UI.sumMontoNota();
                    $SORI.UI.buscaDisputa(tipoDocument);
                    break
            }
        }
        /**
         * 
         * @param {type} var_hide
         * @param {type} var_show
         * @param {type} if_result
         * @param {type} else_result
         * @returns {unresolved}
         */
        function resultadoContrato(var_hide,var_show,if_result,else_result)
        {
            if(var_hide != var_show ){
                if(var_hide==null||var_hide==""){
                    return else_result;
                }else {
                    return if_result;
                }
            }else{
                return else_result; 
            }
        }
        function defineNull(variable, resultado)
        {
            if(variable=="" || variable==null) return resultado;
              else return variable;
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
                validaCampos:validaCampos,
                seleccionaCampos:seleccionaCampos,
                elijeOpciones:elijeOpciones,
                sesionCerrada:sesionCerrada,
                coloursIfChange:coloursIfChange,
                roundNumber:roundNumber,
                msj_cargando:msj_cargando,
                msj_confirm:msj_confirm,
                msj_change:msj_change,
                resultadoContrato:resultadoContrato,
                resuelveTP:resuelveTP,
                validaContratoTpSemanal:validaContratoTpSemanal,
                defineNull:defineNull,
                resuelvePeriodo:resuelvePeriodo,
                defineAmountDisp:defineAmountDisp
	};
})();

/**
 * Submodulo de llamadas AJAX
 */
$SORI.AJAX=(function()
{	
        function init()
	{
            _getNamesCarriers();
	}
        
        function UpdateIdCarrier()
        {
            $.ajax({
                    type: "GET",
                    url: "../Carrier/UpdateIdCarrier",
                    data: "Carrier_new_groups="+$("#Carrier_new_groups").val(),
                    success: function(data)
                    {
                        if(data!=null)  $("#Carrier_id").append("<option value="+data+">"+$("#Carrier_new_groups").val().toUpperCase()+"</option>");
                    }
            });
        }
	/**
	 * Metodo encargado de enviar solicitud de eliminar por ajax la fila
	 * @param id int id de la fila que se va a eliminar
         * @param {type} id
         * @param {type} extra
         * @returns {undefined} 
        */
	function borrar(id, extra)
	{
                switch (extra) {
                    case true:  var url = "/ContratoTerminoPagoSupplier/DeleteCTPS/"+id;console.log(extra);
                        break;
                    case false: var url = "/ContratoTerminoPago/DeleteCTP/"+id;console.log(extra);
                        break;
                    case null:  var url = "/AccountingDocumentTemp/borrar/"+id;console.log(extra);
                        break;
                }
		$.ajax(
		{
			type:'GET',
			url:url,
			data:'',
			success:function(data)
			{
				console.log("eliminado");
			}
		});
		id=extra=null;
	}
	/**
	 * Metodo encargado de enviar la solicutid de actualizar por ajax de la fila indicada
	 * @param id int id de la fila que se va actualizar
	 * @param tope int es el tope de columna a la cual voy a leer, se pasa a getData
	 * @param especial es usado solo para editar el monto de las disputas en disputas recibidas y enviadas (el uso de esta opcion dependera de tope, quien, si tope es diferente de 2)
	 * @access public
	 */
	function actualizar(id,tope,especial)
	{       
            switch (tope) {
                case 2:
                    var url = "update/"+id, urlData=$SORI.UTILS.getData(id,tope);
                    break;
                case 1:case "1":  
                    var url = "UpdateDisp/"+id, urlData="dispute="+especial;
                    break;
                case true:        
                    var url = "/ContratoTerminoPagoSupplier/Update/"+id, urlData=$SORI.UTILS.getData(id,3);
                    break;
                case false:       
                    var url = "/ContratoTerminoPago/Update/"+id, urlData=$SORI.UTILS.getData(id,3);
                    break;
                default:          
                    console.log("algo salio mal");console.log(tope);
                    break;
            }
		$.ajax(
		{
			type:'POST',
			url:url,
			data:urlData,
			success:function(data)
			{
				console.log(data);
//				console.log('actualizo');
			}
		});
		id=null;
	}
        /**
         * autocomplete para carriers
         * @returns {undefined}
         */
        function _getNamesCarriers()
	{
            $.ajax({url:"../Carrier/Nombres",success:function(datos)
            {
                 $SORI.DATA.carriers=JSON.parse(datos);
                 $SORI.DATA.nombresCarriers=Array();
                 for(var i=0, j=$SORI.DATA.carriers.length-1; i<=j; i++)
                 {
                      $SORI.DATA.nombresCarriers[i]=$SORI.DATA.carriers[i].name;
                 };
//                 $('input#Contrato_id_carrier').autocomplete({source:$SORI.DATA.nombresCarriers});
            }
            });
	}
        
        function send(type,action,form, extra)
        {
            $.ajax({
                 type: type,
                 url: action,
                 data: form,
                 success: function(data)
                 {  
                     if(extra===true || extra===false){
                        if(extra===true)   
                            $(".adminCTPS").html(data);
                        else
                             $(".adminCTP").html(data);
                        $SORI.UI.init();
                     }else{
                         console.log(data);
                     }
                 }
            });
        }
        function getTerminoPago(obj)
        {
            $.ajax({url:"../TerminoPago/Names",success:function(datos)
            {
                    $SORI.DATA.list=JSON.parse(datos);
                    $SORI.DATA.names=Array();
                    for(var i=0, j=$SORI.DATA.list.length-1; i<=j; i++)
                    {
                            $SORI.DATA.names[i]=$SORI.DATA.list[i].name;
                    };
                    console.log(obj);
                    if($(""+obj+"").autocomplete({source:$SORI.DATA.names}))
                        console.log("ahi le asigno el autocomplete");
                    if($(""+obj+"").addClass("ui-autocomplete-input"))
                        console.log("ahi le asigno la clase ui-autocomplete-input");
            }
            });
        }
	/**
	 * retorna los metodos publicos*/
return{ init:init,
	actualizar:actualizar,
	borrar:borrar,
        UpdateIdCarrier:UpdateIdCarrier,
        send:send,
        getTerminoPago:getTerminoPago
	}
})();
$SORI.DATA={};
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
	function updateMontoAprobadoDisp(obj)
	{
           $('.lista_Disp_NotaCEnv').children().children().each(function()
            {
                if($(this).attr('id')!== undefined){
                    $SORI.AJAX.actualizar($(this).attr('id'),'1');
                }
            });
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
        function changePositive(val)
        {
            if (val<0)
                return val*-1;
            else
                return val;
        }
	/**
	 * retorna los metodos y variables publicos
	 */
	return{
		getData:getData,
		getURL:getURL,
		updateMontoAprobadoDisp:updateMontoAprobadoDisp,
                changePositive:changePositive
	}
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



   

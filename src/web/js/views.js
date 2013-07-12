$(document).on('ready',function()
{
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
		if(msj.acumulador>=4)
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
function valForm(objeto)
{
	$('input[name="grabar"]').on('click',function(e)
	{	
		e.preventDefault();
		if($("input:checked").val()==undefined)
		{
			var html="<p>Debe seleccionar una opcion</p>";
			var estilo={
//				'background':'#FBE3E4',
//				'border':'10px #FBC2C4 solid',
//				'color':'#8a1f11'
			};
            objeto.interna="error";
			objeto.lightbox(html,estilo,2000);
		}
		else
		{
			var html="<p>Este este proceso es irreversible </br> ¿Esta seguro de los Archivos a Cargar?</p><button name='aceptar'>Aceptar</button><button name='cancelar'>Cancelar</button>";
			var estilo={
//				'background':'#FFF6BF',
//				'border':'10px #FFD324 solid',
//				'color':'#514721'
			};
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













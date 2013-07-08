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
	});
	//Muestra mensaje con el nombre de los archivos rerates guardados
	$('input[value="rerate"]').on('click',function()
	{
		$("div.rerate").fadeIn("slow").css({'display':'block'});
		$("div.diario").fadeOut("slow");
		$("div.horas").fadeOut('slow');
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
				'background':'#FBE3E4',
				'border':'10px #FBC2C4 solid',
				'color':'#8a1f11'
			};
			objeto.lightbox(html,estilo,3000);
		}
		else
		{
			var html="<p>Esta seguro?</p><button name='aceptar'>Aceptar</button><button name='cancelar'>Cancelar</button>";
			var estilo={
				'background':'#FFF6BF',
				'border':'10px #FFD324 solid',
				'color':'#514721'
			};
			objeto.lightbox(html,estilo);
			$('button').on('click',function()
			{
				if($(this).attr('name')=="aceptar")
				{
					if($('div.interna').remove())
					{
						var html="<h1>CARGANDO ARCHIVOS</h1><p>Este proceso puede tardar unos minutos</p><p>Por favor espere</p><img src='images/image_464753.gif'>";
						objeto.lightbox(html);
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













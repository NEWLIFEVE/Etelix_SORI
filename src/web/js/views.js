$(document).on('ready',function()
{
	$('input[value="dia"]').on('click',function()
	{
		$("div.diario").fadeIn("slow").css({'display':'block'});
		$("div.horas").fadeOut("slow");
		$("div.rerate").fadeOut("slow");
	});
	$('input[value="hora"]').on('click',function()
	{
		$("div.horas").fadeIn("slow").css({'display':'block'});
		$("div.diario").fadeOut("slow");
		$("div.rerate").fadeOut("slow");
	});
	$('input[value="rerate"]').on('click',function()
	{
		$("div.rerate").fadeIn("slow").css({'display':'block'});
		$("div.diario").fadeOut("slow");
		$("div.horas").fadeOut('slow');
	});
	valForm();
});
function valForm()
{
	$('form[name="monto"]').on('submit',function(e)
	{	
		console.log("si la llama");	
		if($("input:checked").val()==undefined)
		{
			e.preventDefault();
			var html="<p>Debe seleccionar una opcion</p>";
			lightbox(html,true);
		}
		else
		{
			var html="<h1>CARGANDO ARCHIVOS</h1><p>Este proceso puede tardar unos minutos</p><p>Por favor espere</p><img src='/images/image_464753.gif'>";
			lightbox(html,false);
		}
	});
}
function lightbox(html,cerrar)
{
	if(cerrar)
	{
		$('body').append("<div class='transparente oculta'></div>")
		$('div.transparente').append("<div class='cerrar oculta'>"+html+"</div>");
		setTimeout(elimina,3000);
	}
	else
	{
		$('body').append("<div class='transparente oculta'></div>")
		$('div.transparente').append("<div class='loading oculta'>"+html+"</div>");
	}
	$("div.transparente, div.loading, div.cerrar").fadeIn('slow');
}
function elimina()
{
	$('div.cerrar').parent('div.transparente').remove();
}
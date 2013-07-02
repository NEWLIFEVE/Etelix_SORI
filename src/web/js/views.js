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
	$('form[name="monto"]').on('submit',function(e)
	{		
		if($("input:checked").val()==undefined)
		{
			e.preventDefault();
			alert("Debe seleccionar una opcion");
		}
		else
		{
			$("div.transparente, div.loading").fadeIn('slow').removeClass('oculta');
		}
	});
});
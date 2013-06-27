$(document).on('ready',function()
{
	$('input[value="dia"]').on('click',function()
	{
		$("div.diario").fadeIn("slow").css({'display':'block'});
		$("div.horas").fadeOut("slow");
		$("div.rerate").fadeOut("slow");
		console.log("Funciona dia");
	});
	$('input[value="hora"]').on('click',function()
	{
		$("div.horas").fadeIn("slow").css({'display':'block'});
		$("div.diario").fadeOut("slow");
		$("div.rerate").fadeOut("slow");
		console.log("Funciona hora");
	});
	$('input[value="rerate"]').on('click',function()
	{
		$("div.rerate").fadeIn("slow").css({'display':'block'});
		$("div.diario").fadeOut("slow");
		$("div.horas").fadeOut('slow');
		console.log("Funciona rerate");
	});
	$('form[name="monto"]').on('submit',function()
	{
		$("div.transparente, div.loading").fadeIn('slow').removeClass('oculta');
	});
});
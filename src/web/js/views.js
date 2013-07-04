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
		val=$(this).children('div#archivo').children('ul').children('li').children("input:checked").val();
		console.log(this);
		if(val==undefined)
		{
			e.preventDefault();
			$('body').lightbox({tiempo:3000,html:"CAmbiamos los mensajes"});
		}
	});
});














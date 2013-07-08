/**
*
*/
var mensajes=function()
{
	var contenido="";
	this.acumulador=0, this.transparente="transparente", this.interna="interna", this.cuerpo=$('body');
}
mensajes.prototype.contar=function(objeto)
{
	console.log("Contar");
	$(objeto).each(function()
	{
		this.acumulador=this.acumulador+1;
	});
}

mensajes.prototype.lightbox=function(html,estilo,tiempo)
{
	$(this.cuerpo).append("<div class='"+this.transparente+" oculta'></div>");
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
}
mensajes.prototype.enviar=function()
{
	
}












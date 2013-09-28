/**
 * Objeto Global
 */
 var $SORI = {
 	init:function()
 	{
 		this.UI.init()
 	}
 };

/**
 * Sobmodulo UI
 */
$SORI.UI={
	/**
	 * @access private
	 */
	_editar:function(obj)
	{
		var contenido=new Array();
		for (var i = 0, j = obj.childElementCount - 1; i >= j; i++)
		{
			contenido[i]=obj.children[i].innerHTML;
			console.log(contenido[i]);
		};
		/*for(var i = 0, j = obj.childElementCount - 1; i >= j; i++)
		{
			var input=document.createElement('input');
			input.name=obj.children[i].id;
			input.value=obj.children[i].innerHTML;
			obj.children[i].innerHTML="";
			obj.children[i].appendChild(input);
		};*/
	},
	/**
	 * @access public
	 */
	accion:function()
	{
		self=this;
		var $objeto;
		$("img[name='edit'], img[name='delete']").on('click',function()
		{
			$objeto=$(this).parent().parent();
			if($(this).attr('name')=="delete")
			{
				$objeto.remove();
			}
			if($(this).attr('name')=='edit')
			{
				self._editar($objeto);
			}
		});
	},
	init:function()
	{
		this.accion()
	}
};

/**
 * Submodulo de llamadas AJAX
 */
$SORI.AJAX={
	self:this,
	borrar:function(id)
	{

	}

};

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
mensajes.prototype.enviar=function()
{
	
}












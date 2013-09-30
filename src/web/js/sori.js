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
		self=this;
		for (var i=0, j=obj[0].childElementCount-2;i<=j;i++)
		{
			var input=document.createElement('input');
			input.name=obj[0].children[i].id;
			input.value=obj[0].children[i].innerHTML;
			obj[0].children[i].innerHTML="";
			obj[0].children[i].appendChild(input);
			input=null;
		}
		obj[0].children[10].innerHTML="";
		obj[0].children[10].innerHTML="<img name='save' alt='save' src='/images/icon_check.png'><img name='cancel' alt='cancel' src='/images/icon_arrow.gif'>";
		obj=null;
		self.accion();
	},
	/**
	 * @access private
	 */
	_revert:function(obj)
	{
		var contenido=new Array();
		for (var i=0, j=obj[0].childElementCount-2;i<=j;i++)
		{
			contenido[i]=obj[0].children[i].children[0].value;
			obj[0].children[i].children[0].remove();
			obj[0].children[i].innerHTML=contenido[i];
		}
		obj[0].children[10].innerHTML="";
		obj[0].children[10].innerHTML="<img name='edit' alt='edit' src='/images/icon_lapiz.jpg'><img name='delete' alt='delete' src='/images/icon_x.gif'>";
		self.accion();
	},
	/**
	 * @access public
	 */
	accion:function()
	{
		self=this;
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
				self._editar($fila);
			}
			if($(this).attr('name')=='save')
			{
				self._revert($fila);
			}
			if($(this).attr('name')=='cancel')
			{
				self._revert($fila);
			}
			console.dir($fila);
		});
		//$fila=null;
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
		$.ajax(
		{
			type:'GET',
			url:'delete/'+id,
			data:'',
			success:function(data)
			{
				console.log("eliminado");
			}
		});
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












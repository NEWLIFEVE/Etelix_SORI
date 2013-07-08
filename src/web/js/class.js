var mensajes=function(){
	this.acumulador=0;
}
mensajes.prototype.contar=function(objeto)
{
	$(objeto).each(function()
	{
		this.acumulador=this.acumulador+1;
	});
}





/*(function($)
{

	var methods = {
		init:function(options)
		{
			var settings = {
			    'clase':'alerta'
			    };
			return $(this).append("<div class='transparente oculta'></div>");
			/* this.each(function()
			{
			    if(options)
			    {
			        settings=$.extend(settings,options);
			    }
			    $(this).append("<div class='transparente oculta'></div>");
			});
		};,
		show:function()
		{
			return this.each(function()
			{
				$(this).show();
			});
		},
		hide:function()
		{
			return this.each(function()
			{
			   $(this).hide();
			});
		},
		update:function(options)
		{
			return this.each(function()
			{
				$(this).css(options);
			});
		}
	};
	$.fn.mensajes=function(method)
	{
		if(methods[method])
		{
			return methods[method].apply(this, Array.prototype.slice.call(arguments,1));
		}
		else if(typeof method === 'object' || ! method)
		{
			return methods.init.apply(this, arguments);
		}
		else
		{
			$.error('Este método '+method+' no existe en jQuery.estiloPropio');
		}
	};
})( jQuery );  
*/

/*mensajes.prototype.valForm=function()
{
	$('form[name="monto"]').on('submit',function(e)
	{	
		e.preventDefault();
		if($("input:checked").val()==undefined)
		{
			var opciones = {html:"<p>Debe seleccionar una opcion</p>",tipo:'error'};
			lightbox(opciones);
		}
		else
		{
			var opciones = {html:"<p>Esta seguro de la selección?</p><button id='aceptar'>Aceptar</button><button>Cancelar</button>",tipo:'alerta',objeto:this};
			lightbox(opciones);
		}
		/*
		else
		{
			var html="<h1>CARGANDO ARCHIVOS</h1><p>Este proceso puede tardar unos minutos</p><p>Por favor espere</p><img src='/images/image_464753.gif'>";
			lightbox(html,false);
		}
	});
}
function lightbox(html)
{
	
	if(html.tipo=="error")
	{
		$('body').append("<div class='transparente oculta'></div>")
		$('div.transparente').append("<div class='error oculta'>"+html.html+"</div>");
		setTimeout(elimina,3000);
	}
	if(html.tipo=="alerta")
	{
		$('body').append("<div class='transparente oculta'></div>")
		$('div.transparente').append("<div class='cerrar oculta'>"+html.html+"</div>");
		elimina(html.objeto);
	}
	/*else
	{
		$('body').append("<div class='transparente oculta'></div>")
		$('div.transparente').append("<div class='loading oculta'>"+html+"</div>");
	}
	$("div.transparente, div.loading, div.cerrar").fadeIn('slow');
}
function elimina(objeto)
{
	$('div.transparente').fadeOut('slow',function()
	{
		$('div.transparente').remove();
	});
	$('button#aceptar').on('click',function()
	{
		$(this).parent().fadeOut('slow',function()
		{
			$(this).parent().remove();
			$('div.transparente').append("<div class='loading oculta'>"+html+"</div>");
		});
	});
	
}
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
		e.preventDefault();
		$('body').lightbox();
	});
});*/














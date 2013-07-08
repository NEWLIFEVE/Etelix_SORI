$(document).on('ready',function()
{
	(function($)
	{
		/**
		* Metodos privados
		*/
		var _borrar=function()
		{
			$('div.transparente').fadeOut('slow',function()
			{
				$(this).remove();
			});
		},
		_interna=function(objeto,opciones)
		{
			var settings={
				css:{
					'margin':'15% auto',
					'display':'inline-block',
					'padding':'5em 5em',
					'background':'#FBE3E4',
					'border-radius':'10px',
					'border':'10px #FBC2C4 solid',
					'color':'#8a1f11',
				},
				tiempo:3000,
				html:'<p>Debe seleccionar una opcion</p>',
			};
			if(opciones)
			{
				settings=$.extend(settings,opciones);
			}
			$(objeto).append("<div class='interna'></div>");
			$('div.interna').css(settings.css).html(settings.html);
		},
		_transparente=function(objeto,opciones)
		{
			var settings={
				'width':'100%',
				'height':'100%',
				'background':'rgba(0,0,0,0.5)',
				'top':'0px',
				'position':'fixed',
				'text-align':'center'
			};
			if(opciones)
			{
				settings=$.extend(settings,opciones);
			}
			$(objeto).append("<div class='transparente oculta'></div>");
			$('div.transparente').css(settings).fadeIn('slow');
		};
		/**
		* Metodos publicos
		*/
		$.fn.lightbox=function(conf)
		{
			return this.each(function()
			{
				_transparente(this);
				_interna("div.transparente",conf);
				if(conf.tiempo)
				{
					setTimeout(_borrar,conf.tiempo);
				}
			});
		};
	})(jQuery);
});
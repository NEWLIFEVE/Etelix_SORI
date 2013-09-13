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
		if(msj.acumulador>=2)
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
		$('input[type="file"], input[type="submit"]').removeAttr('disabled');
	});
	//Muestra mensaje con el nombre de los archivos rerates guardados
	$('input[value="rerate"]').on('click',function()
	{
		$("div.rerate").fadeIn("slow").css({'display':'block'});
		$("div.diario").fadeOut("slow");
		$("div.horas").fadeOut('slow');
		$('input[type="file"], input[type="submit"]').removeAttr('disabled');
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
//				'background':'#FBE3E4',
//				'border':'10px #FBC2C4 solid',
//				'color':'#8a1f11'
			};
            objeto.interna="error";
			objeto.lightbox(html,estilo,2000);
		}
		else
		{
			var html="<p>Este este proceso es irreversible </br> ¿Esta seguro de los Archivos a Cargar?</p><button name='aceptar'>Aceptar</button><button name='cancelar'>Cancelar</button>";
			var estilo={
//				'background':'#FFF6BF',
//				'border':'10px #FFD324 solid',
//				'color':'#514721'
			};
            objeto.interna="confirm";
			objeto.lightbox(html,estilo);
			$('button').on('click',function()
			{
				if($(this).attr('name')=="aceptar")
				{
					if($('div.'+objeto.interna).remove())
					{
						$('div.transparente').fadeOut('slow');
						$('div.transparente2').fadeIn('slow');
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
//codigo para cambiar dist comercial 

  $("#botAsignar").on( "click",  function asignadosAnoasignados()
  {
   $("#carriers select option").prop("selected",true); 
   var manager = $("#CarrierManagers_id_managers").val();
   var asignados = $("#select_left").val();
   var noasignados = $("#select_right").val();  
   
        if(manager == "")
         {
          var aguanta = $("<div class='cargando'></div><div class='mensaje'><h3>Debe seleccionar un manager</h3><p><p><p><p><p><p><p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
               $("body").append(aguanta)
               aguanta.fadeIn('fast');
               setTimeout(function()
            {
                aguanta.fadeOut('fast');
            }, 3000);
         }     
    else
      { 
        $.ajax({           
                type: "GET",
                url: "BuscaNombres",
                data: "asignados="+asignados+"&noasignados="+noasignados+"&manager="+manager,
                success: function(data) 
                    {     var carriers=data.split("/");
                          var managerNames=carriers[0].split(",");
                          var asigname=carriers[2].split(",");
                          var noasigname=carriers[1].split(",");
                                  
                         if (asigname<="1" && noasigname<="1")
                            {
                            var nohaynada = $("<div class='cargando'></div><div class='mensaje'><h3>No hay carriers preselccionados <br> para asignar o \n\
                                                 desasignar</h3><p><p><p><p><p><p><p><p><img src='/images/aguanta.png'width='95px' height='95px'/></div>").hide();
                                        $("body").append(nohaynada)
                                        nohaynada.fadeIn('fast');
                                        setTimeout(function()
                                        {
                                            nohaynada.fadeOut('fast');
                                        }, 4000);  
                            }
                            else
                               {
                                if (asigname=="")
                                   {
                                    var asig="";
                                   }
                                   else
                                      {
                                          var asig='Asignarle: ';
                                      }
                                if (noasigname=="")
                                   {
                                    var desA="";
                                   }
                                   else
                                      {
                                       var desA = 'Dsasignarle:';
                                      }
                       var revisa = $("<div class='cargando'></div><div class='mensaje'><h4>Esta a punto de realizar los siguientes cambios en la Distribucion \n\
                                      \n\Comercial para el manager: <br><b>"+managerNames+"</b></h4>\n\<p><h6>"+asig+"<p>"+asigname+"</h6><p><p><h6>"+desA+"<p>\n\
                                      "+noasigname+"</h6><p>Si esta seguro presione Aceptar, de lo contrario Cancelar<p><p><p><p><p><p><p><div id='cancelar' class='cancelar'>\n\
                                      <img src='/images/cancelar.png'width='90px' height='50px'/>\n\&nbsp;</div><div id='confirma' class='confirma'><img src='/images/aceptar.png'\n\
                                      width='85px' height='45px'/></div></div>").hide();
                                  $("body").append(revisa);
                                  revisa.fadeIn('fast');
                               }
                                  
    $('#confirma,#cancelar').on('click', function()
    {
        var tipo=$(this).attr('id');
        if(tipo=="confirma"&& manager!="")
          { 
            $.ajax({           
                type: "GET",
                url: "UpdateDistComercial",
                data: "asignados="+asignados+"&noasignados="+noasignados+"&manager="+manager,

                success: function(data) 
                        {
                              var carriers=data.split("/");
                              var managerNames=carriers[0].split(",");
                              var asigname=carriers[2].split(",");
                              var noasigname=carriers[1].split(",");

                                 if (asigname=="")
                                    {
                                     var pudo="";
                                    }
                                    else
                                       {
                                        var pudo='Le fue asignado:';
                                       }
                                if (noasigname=="")
                                   {
                                   var nopudo="";
                                   }
                                   else
                                      {
                                      var nopudo = 'Desasignado:';
                                      }
                                var espere = $('.mensaje').html("<h5>Al manager: <b>" + managerNames + "</b><br>" + pudo + "<br><b>" + asigname + "</b></h5><p><h5>" + nopudo + "\
                                                             <br><b>" + noasigname + "</b></h5><p><p><img src='/images/si.png'width='95px' height='95px'/>").hide().fadeIn('fast');
                                setTimeout(function()
                                {
                                    espere.fadeOut('fast');
                                }, 4000);

                                setTimeout(function()
                                {
                                    $('.cargando').fadeOut('fast');
                                }, 4000);
                        }
                 });
          }
        else
            {
             revisa.fadeOut('fast');
            }
    });
                  }            
              });
      }
    $("#carriers select option").prop("selected",false);       
  });
          
          $("#options_right").on( "click",  function asignadosAnoasignados(){
                $('#select_left :selected').each(function(i,selected){                        
                    $("#select_left option[value='"+$(selected).val()+"']").remove();
                    $('#select_right').append("<option value='"+$(selected).val()+"'>"+$(selected).text()+"</option>");
                });
            });
            
          $("#options_left").on( "click",  function noasignadosAasignados(){ 
                $('#select_right :selected').each(function(i,selected){                        
                    $("#select_right option[value='"+$(selected).val()+"']").remove();
                    $('#select_left').append("<option value='"+$(selected).val()+"'>"+$(selected).text()+"</option>");
                });
            });
   
            $("#CarrierManagers_id_managers").change(function(){
                    $.ajax({
                        type:'POST',
                        url: "DynamicNoAsignados",
                        success: function(data){
                            $("#select_right").empty();
                            $("#select_right").append(""+data+"");
                        }                   
                  });
            });
//fin de cambio en dist comercial
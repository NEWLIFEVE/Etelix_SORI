<?php
echo CHtml::beginForm('/balance/guardar','post',array('name'=>'monto'));
?>
<head>
<meta charset="utf-8" />
  <title>jQuery UI Datepicker - Format date</title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css" />
<script>
$(function() {
        
        $.noConflict();
        $( ".datepicker" ).datepicker();
        $( ".datepicker" ).datepicker( "option", "dateFormat", "mm-dd-yy" );
        $( ".datepicker" ).datepicker( "option", "showAnim", "drop" );
        
    });
  
    $(function($){
        $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '<Ant',
            nextText: 'Sig>',
            currentText: 'Hoy',
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
            dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
            dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 0,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['es']);
    });
    
</script>
</head>
<div id="archivo">
  <ul>
    <li><input type="radio" name="tipo" value="dia"/>Por Día</li>
    <li><input type="radio" name="tipo" value="hora" />Por Hora</li>
    <li><input type="radio" name="tipo" value="rerate" />Re-Rate</li>
  </ul>
</div>
<?php
$this->widget('ext.EAjaxUpload.EAjaxUpload',
array(
        'id'=>'uploadFile',
        'config'=>array(
               'action'=>Yii::app()->createUrl('site/upload'),
               'allowedExtensions'=>array("xls", "xlsx"),//array("jpg","jpeg","gif","exe","mov" and etc...
               'sizeLimit'=>9*1024*1024,// maximum file size in bytes
               'minSizeLimit'=>1*1024,// minimum file size in bytes
               //'onComplete'=>"js:function(id, fileName, responseJSON){ alert(fileName); }",
               //'messages'=>array(
               //                  'typeError'=>"{file} has invalid extension. Only {extensions} are allowed.",
               //                  'sizeError'=>"{file} is too large, maximum file size is {sizeLimit}.",
               //                  'minSizeError'=>"{file} is too small, minimum file size is {minSizeLimit}.",
               //                  'emptyError'=>"{file} is empty, please select files again without it.",
               //                  'onLeave'=>"The files are being uploaded, if you leave now the upload will be cancelled."
               //                 ),
               //'showMessage'=>"js:function(message){ alert(message); }"
              )
));
?>
<div class="diario oculta">
  
  <p>Archivos Cargados:</p>
  <ul>
      <?php
      if(Log::existe(1))
      {
        echo "<li class='cargados' name='diario'>Ruta Compra</li>";
      }
      if(Log::existe(2))
      {
        echo "<li class='cargados' name='diario'>Ruta Venta</li>";
      }
      if(Log::existe(3))
      {
        echo "<li class='cargados' name='diario'>Ruta Compra Internal</li>";
      }
      if(Log::existe(4))
      {
        echo "<li class='cargados' name='diario'>Ruta Venta Internal</li>";
      }
      ?>
  </ul>
  <p>Archivos Faltantes:</p>
  <ul>
      <?php
      if(!Log::existe(1))
      {
        echo "<li class='nocargados'>Ruta Compra</li>";
      }
      if(!Log::existe(2))
      {
        echo "<li class='nocargados'>Ruta Venta</li>";
      }
      if(!Log::existe(3))
      {
        echo "<li class='nocargados'>Ruta Compra Internal</li>";
      }
      if(!Log::existe(4))
      {
        echo "<li class='nocargados'>Ruta Venta Internal</li>";
      }
      ?>
  </ul>
</div>
<div class="horas oculta">
  <p>Archivos Cargados:</p>
  <ul>
      <?php
      $existe=false;
      for ($i=5; $i<=52; $i++)
      { 
        if(Log::existe($i))
        {
          echo "<li class='cargados'>".LogAction::getName($i)."</li>";
          $existe=true;
        }
      }
      if(!$existe)
      {
        echo "<li class='nocargados'>No se han cargado archivos</li>";
      }
      ?>
  </ul>
</div>
<div class="rerate oculta">
  <p>Rango del Re-Rate:</p>
  <ul>
    <li  class='cargados rangoDesde'>Desde</li>
    <input type="text" class="datepicker" id="desde" name="fechaInicio" readonly/>
    <li  class='cargados rangoHasta'>Hasta</li>
    <input type="text" class="datepicker" id="hasta" name="fechaFin" size="30" readonly/>
    <li class='nocargados'></li>
  </ul>
  <p>Ultimo Rango Cargado:</p>
  <ul>
      <?php
      $existe=false;
      for ($i=5; $i<=52; $i++)
      { 
        if(Log::existe($i))
        {
          echo "<li class='cargados'>".LogAction::getName($i)."</li>";
          $existe=true;
        }
      }
      if(!$existe)
      {
        echo "<li class='nocargados'>No se ha cargado ningun rango</li>";
      }
      ?>
  </ul>
</div>
<?php
echo "<div class='row buttons'><input type='submit' value='Grabar en Base de Datos' name='grabar'></div>";
echo CHtml::endForm();
?>

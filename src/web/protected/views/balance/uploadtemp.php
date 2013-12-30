<?php
echo CHtml::beginForm('/balance/guardartemp','post',array('name'=>'monto'));
?>
<h1>Carga de Archivos</h1>
<div id="archivo">
  <ul>
    <li><input type="radio" name="tipo" value="dia"/>Por Día</li>
    <!-- <li><input type="radio" name="tipo" value="hora" />Por Hora</li>
    <li><input type="radio" name="tipo" value="rerate" />Re-Rate</li> -->
  </ul>
</div>
<?php
$this->widget('ext.EAjaxUpload.EAjaxUpload',
array(
        'id'=>'uploadFile',
        'config'=>array(
               'action'=>Yii::app()->createUrl('balance/cargatemp'),
               'allowedExtensions'=>array("xls", "xlsx"),//array("jpg","jpeg","gif","exe","mov" and etc...
               'sizeLimit'=>20*1024*1024,// maximum file size in bytes
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
  <?php
  echo Log::logDiario();
  ?>
</div>
<div class="horas oculta">
  <h3>ESTATUS CARGA</h3>
  <p>Archivos Cargados:</p>
  <ul>
      <?php
      $existe=false;
      for ($i=5; $i<=28; $i++)
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
  <h3>ESTATUS CARGA</h3>
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
      $fechas=Log::getRerate();
      if($fechas)
      {
        echo "<li class='cargados'>".$fechas."</li>";
      }
      else
      {
        echo "<li class='nocargados'>No se ha cargado ningun rango</li>";
      }
      ?>
      <li><?php
      $listo=Log::getListo();
      /*if($listo == "procesando")
      {
        echo "Procesando <img src='/images/gif-load.gif'>";
      }*/
      ?></li>
  </ul>
</div>
<?php
echo "<div class='row buttons'><input type='submit' value='Grabar en Base de Datos' name='grabartemp'></div>";
echo CHtml::endForm();
?>
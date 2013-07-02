<?php
echo CHtml::beginForm('/balance/guardar','post',array('name'=>'monto'));
?>
<div id="archivo">
  <ul>
    <li><input type="radio" name="tipo" value="dia" <?php echo Log::disabledDiario(date("Y-m-d")); ?>/>Por Día</li>
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
        echo "<li class='cargados'>Ruta Compra</li>";
      }
      if(Log::existe(2))
      {
        echo "<li class='cargados'>Ruta Venta</li>";
      }
      if(Log::existe(3))
      {
        echo "<li class='cargados'>Ruta Compra Internal</li>";
      }
      if(Log::existe(4))
      {
        echo "<li class='cargados'>Ruta Venta Internal</li>";
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
      for ($i=5; $i<=52; $i++)
      { 
        if(Log::existe($i))
        {
          echo "<li class='cargados'>".LogAction::getName($i)."</li>";
        }
      }
      ?>
  </ul>
</div>
<div class="rerate oculta">
  <p>Archivos Cargados:</p>
  <ul>
      <?php
      if(Log::existe(9))
      {
        echo "<li class='cargados'>Ruta Compra ReRate</li>";
      }
      if(Log::existe(10))
      {
        echo "<li class='cargados'>Ruta Venta ReRate</li>";
      }
      if(Log::existe(11))
      {
        echo "<li class='cargados'>Ruta Compra Internal ReRate</li>";
      }
      if(Log::existe(12))
      {
        echo "<li class='cargados'>Ruta Venta Internal ReRate</li>";
      }
      ?>
  </ul>
  <p>Archivos Faltantes:</p>
  <ul>
      <?php
      if(!Log::existe(9))
      {
        echo "<li class='nocargados'>Ruta Compra ReRate</li>";
      }
      if(!Log::existe(10))
      {
        echo "<li class='nocargados'>Ruta Venta ReRate</li>";
      }
      if(!Log::existe(11))
      {
        echo "<li class='nocargados'>Ruta Compra Internal ReRate</li>";
      }
      if(!Log::existe(12))
      {
        echo "<li class='nocargados'>Ruta Venta Internal ReRate</li>";
      }
      ?>
  </ul>
</div>
<?php
echo "<div class='row buttons'><input type='submit' value='Grabar en Base de Datos'></div>";
echo CHtml::endForm();
?>

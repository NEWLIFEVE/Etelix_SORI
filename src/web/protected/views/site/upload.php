<?php
echo CHtml::beginForm('/site/guardar','post',array('name'=>'monto'));
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
               'sizeLimit'=>8*1024*1024,// maximum file size in bytes
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
echo "<div class='row buttons'><input type='submit' value='Grabar en Base de Datos'></div>";
echo CHtml::endForm();
?>
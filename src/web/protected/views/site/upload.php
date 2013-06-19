<?php
echo CHtml::radioButtonList('Archivo','',array('Venta', 'Venta Internal', 'Compra', 'Compra Internal'));
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
?>
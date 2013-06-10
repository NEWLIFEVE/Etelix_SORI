<?php
$this->widget('application.extensions.EAjaxUpload.EAjaxUpload',array(
	'id'=>'uploadFile',
	'config'=>array(
		'action'=>Yii::app()->createUrl('/site/upload'),
		'allowedExtensions'=>array('xls', 'xlsx'),
		'sizeLimit'=>1*1024*1024,
		'minSizeLimit'=>1024,
		'onComplete'=>'js:function(id, fileName, responseJSON){$("#archivo").val(fileName); $("#botones").css("display","inline");}',
		)
	)
);
?>
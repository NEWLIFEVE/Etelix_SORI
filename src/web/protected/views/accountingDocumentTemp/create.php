<?php
/* @var $this AccountingDocumentTempController */
/* @var $model AccountingDocumentTemp */

//$this->breadcrumbs=array(
//	'Accounting Document Temps'=>array('index'),
//	'Create',
//);

//$this->menu=array(
//	array('label'=>'List AccountingDocumentTemp', 'url'=>array('index')),
//	array('label'=>'Manage AccountingDocumentTemp', 'url'=>array('admin')),
//);
?>

<h1>Documentos Contables</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'lista'=>$lista)); ?>
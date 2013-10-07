<?php
/* @var $this AccountingDocumentTempController */
/* @var $model AccountingDocumentTemp */

$this->breadcrumbs=array(
	'Accounting Document Temps'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AccountingDocumentTemp', 'url'=>array('index')),
	array('label'=>'Create AccountingDocumentTemp', 'url'=>array('create')),
	array('label'=>'View AccountingDocumentTemp', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AccountingDocumentTemp', 'url'=>array('admin')),
);
?>

<h1>Update AccountingDocumentTemp <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
/* @var $this AccountingDocumentController */
/* @var $model AccountingDocument */

$this->breadcrumbs=array(
	'Accounting Documents'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List AccountingDocument', 'url'=>array('index')),
	array('label'=>'Manage AccountingDocument', 'url'=>array('admin')),
);
?>

<h1>Create AccountingDocument</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
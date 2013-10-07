<?php
/* @var $this AccountingDocumentController */
/* @var $model AccountingDocument */

$this->breadcrumbs=array(
	'Accounting Documents'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AccountingDocument', 'url'=>array('index')),
	array('label'=>'Create AccountingDocument', 'url'=>array('create')),
	array('label'=>'View AccountingDocument', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage AccountingDocument', 'url'=>array('admin')),
);
?>

<h1>Update AccountingDocument <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
/* @var $this AccountingDocumentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Accounting Documents',
);

$this->menu=array(
	array('label'=>'Create AccountingDocument', 'url'=>array('create')),
	array('label'=>'Manage AccountingDocument', 'url'=>array('admin')),
);
?>

<h1>Accounting Documents</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

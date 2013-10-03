<?php
/* @var $this AccountingDocumentTempController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Accounting Document Temps',
);

$this->menu=array(
	array('label'=>'Create AccountingDocumentTemp', 'url'=>array('create')),
	array('label'=>'Manage AccountingDocumentTemp', 'url'=>array('admin')),
);
?>

<h1>Accounting Document Temps</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

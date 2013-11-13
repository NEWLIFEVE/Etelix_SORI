<?php
/* @var $this DestinationSupplierController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Destination Suppliers',
);

$this->menu=array(
	array('label'=>'Create DestinationSupplier', 'url'=>array('create')),
	array('label'=>'Manage DestinationSupplier', 'url'=>array('admin')),
);
?>

<h1>Destination Suppliers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

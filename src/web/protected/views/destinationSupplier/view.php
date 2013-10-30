<?php
/* @var $this DestinationSupplierController */
/* @var $model DestinationSupplier */

$this->breadcrumbs=array(
	'Destination Suppliers'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List DestinationSupplier', 'url'=>array('index')),
	array('label'=>'Create DestinationSupplier', 'url'=>array('create')),
	array('label'=>'Update DestinationSupplier', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete DestinationSupplier', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DestinationSupplier', 'url'=>array('admin')),
);
?>

<h1>View DestinationSupplier #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'id_carrier',
	),
)); ?>

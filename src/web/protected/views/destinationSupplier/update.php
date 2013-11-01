<?php
/* @var $this DestinationSupplierController */
/* @var $model DestinationSupplier */

$this->breadcrumbs=array(
	'Destination Suppliers'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List DestinationSupplier', 'url'=>array('index')),
	array('label'=>'Create DestinationSupplier', 'url'=>array('create')),
	array('label'=>'View DestinationSupplier', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage DestinationSupplier', 'url'=>array('admin')),
);
?>

<h1>Update DestinationSupplier <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
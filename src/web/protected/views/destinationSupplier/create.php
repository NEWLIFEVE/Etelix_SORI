<?php
/* @var $this DestinationSupplierController */
/* @var $model DestinationSupplier */

$this->breadcrumbs=array(
	'Destination Suppliers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DestinationSupplier', 'url'=>array('index')),
	array('label'=>'Manage DestinationSupplier', 'url'=>array('admin')),
);
?>

<h1>Create DestinationSupplier</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
/* @var $this DestinationIntController */
/* @var $model DestinationInt */

$this->breadcrumbs=array(
	'Destination Ints'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List DestinationInt', 'url'=>array('index')),
	array('label'=>'Create DestinationInt', 'url'=>array('create')),
	array('label'=>'View DestinationInt', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage DestinationInt', 'url'=>array('admin')),
);
?>

<h1>Update DestinationInt <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
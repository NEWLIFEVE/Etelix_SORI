<?php
/* @var $this DestinationIntController */
/* @var $model DestinationInt */

$this->breadcrumbs=array(
	'Destination Ints'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List DestinationInt', 'url'=>array('index')),
	array('label'=>'Create DestinationInt', 'url'=>array('create')),
	array('label'=>'Update DestinationInt', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete DestinationInt', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DestinationInt', 'url'=>array('admin')),
);
?>

<h1>View DestinationInt #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>

<?php
/* @var $this MonetizableController */
/* @var $model Monetizable */

$this->breadcrumbs=array(
	'Monetizables'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Monetizable', 'url'=>array('index')),
	array('label'=>'Create Monetizable', 'url'=>array('create')),
	array('label'=>'Update Monetizable', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Monetizable', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Monetizable', 'url'=>array('admin')),
);
?>

<h1>View Monetizable #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>

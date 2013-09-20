<?php
/* @var $this ManagersController */
/* @var $model Managers */

$this->breadcrumbs=array(
	'Managers'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Managers', 'url'=>array('index')),
	array('label'=>'Create Managers', 'url'=>array('create')),
	array('label'=>'Update Managers', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Managers', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Managers', 'url'=>array('admin')),
);
?>

<h1>View Managers #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'address',
		'record_date',
		'position',
		'lastname',
	),
)); ?>

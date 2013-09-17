<?php
/* @var $this LimitesController */
/* @var $model Limites */

$this->breadcrumbs=array(
	'Limites'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Limites', 'url'=>array('index')),
	array('label'=>'Create Limites', 'url'=>array('create')),
	array('label'=>'Update Limites', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Limites', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Limites', 'url'=>array('admin')),
);
?>

<h1>View Limites #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>

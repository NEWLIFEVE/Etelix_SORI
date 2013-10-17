<?php
/* @var $this CarrierGroupsController */
/* @var $model CarrierGroups */

$this->breadcrumbs=array(
	'Carrier Groups'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CarrierGroups', 'url'=>array('index')),
	array('label'=>'Create CarrierGroups', 'url'=>array('create')),
	array('label'=>'Update CarrierGroups', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CarrierGroups', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CarrierGroups', 'url'=>array('admin')),
);
?>

<h1>View CarrierGroups #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nombre',
	),
)); ?>

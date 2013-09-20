<?php
/* @var $this GeographicZoneController */
/* @var $model GeographicZone */

$this->breadcrumbs=array(
	'Geographic Zones'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List GeographicZone', 'url'=>array('index')),
	array('label'=>'Create GeographicZone', 'url'=>array('create')),
	array('label'=>'Update GeographicZone', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete GeographicZone', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage GeographicZone', 'url'=>array('admin')),
);
?>

<h1>View GeographicZone #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name_zona',
		'color_zona',
	),
)); ?>

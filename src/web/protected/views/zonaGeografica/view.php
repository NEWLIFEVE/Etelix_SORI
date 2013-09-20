<?php
/* @var $this ZonaGeograficaController */
/* @var $model ZonaGeografica */

$this->breadcrumbs=array(
	'Zona Geograficas'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ZonaGeografica', 'url'=>array('index')),
	array('label'=>'Create ZonaGeografica', 'url'=>array('create')),
	array('label'=>'Update ZonaGeografica', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ZonaGeografica', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ZonaGeografica', 'url'=>array('admin')),
);
?>

<h1>View ZonaGeografica #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name_zona',
		'color_zona',
	),
)); ?>

<?php
/* @var $this CarrierController */
/* @var $model Carrier */

$this->breadcrumbs=array(
	'Carriers'=>array('index'),
	$model->name,
);

//$this->menu=array(
//	array('label'=>'List Carrier', 'url'=>array('index')),
//	array('label'=>'Create Carrier', 'url'=>array('create')),
//	array('label'=>'Update Carrier', 'url'=>array('update', 'id'=>$model->id)),
//	array('label'=>'Delete Carrier', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
//	array('label'=>'Manage Carrier', 'url'=>array('admin')),
//);
?>

<!--<h1>Carrier #<?php //echo $model->id; ?></h1>-->
<h1><?php echo $nombre; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'address',
		'fecha_registro',
	),
)); ?>

<?php
/* @var $this TerminoPagoController */
/* @var $model TerminoPago */

$this->breadcrumbs=array(
	'Termino Pagos'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List TerminoPago', 'url'=>array('index')),
	array('label'=>'Create TerminoPago', 'url'=>array('create')),
	array('label'=>'Update TerminoPago', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TerminoPago', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TerminoPago', 'url'=>array('admin')),
);
?>

<h1>View TerminoPago #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>

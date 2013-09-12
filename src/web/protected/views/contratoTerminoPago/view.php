<?php
/* @var $this ContratoTerminoPagoController */
/* @var $model ContratoTerminoPago */

$this->breadcrumbs=array(
	'Contrato Termino Pagos'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ContratoTerminoPago', 'url'=>array('index')),
	array('label'=>'Create ContratoTerminoPago', 'url'=>array('create')),
	array('label'=>'Update ContratoTerminoPago', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ContratoTerminoPago', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ContratoTerminoPago', 'url'=>array('admin')),
);
?>

<h1>View ContratoTerminoPago #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'start_date',
		'end_date',
		'id_contrato',
		'id_termino_pago',
	),
)); ?>

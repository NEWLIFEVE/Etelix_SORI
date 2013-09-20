<?php
/* @var $this ContratoLimitesController */
/* @var $model ContratoLimites */

$this->breadcrumbs=array(
	'Contrato Limites'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ContratoLimites', 'url'=>array('index')),
	array('label'=>'Create ContratoLimites', 'url'=>array('create')),
	array('label'=>'Update ContratoLimites', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ContratoLimites', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ContratoLimites', 'url'=>array('admin')),
);
?>

<h1>View ContratoLimites #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'start_date',
		'end_date',
		'id_contrato',
		'id_limites',
		'monto',
	),
)); ?>

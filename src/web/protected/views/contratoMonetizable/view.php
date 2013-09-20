<?php
/* @var $this ContratoMonetizableController */
/* @var $model ContratoMonetizable */

$this->breadcrumbs=array(
	'Contrato Monetizables'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List ContratoMonetizable', 'url'=>array('index')),
	array('label'=>'Create ContratoMonetizable', 'url'=>array('create')),
	array('label'=>'Update ContratoMonetizable', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ContratoMonetizable', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ContratoMonetizable', 'url'=>array('admin')),
);
?>

<h1>View ContratoMonetizable #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'start_date',
		'end_date',
		'id_contrato',
		'id_monetizable',
	),
)); ?>

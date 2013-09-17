<?php
/* @var $this ContratoController */
/* @var $model Contrato */

$this->breadcrumbs=array(
	'Contratos'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Contrato', 'url'=>array('index')),
	array('label'=>'Create Contrato', 'url'=>array('create')),
	array('label'=>'Update Contrato', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Contrato', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Contrato', 'url'=>array('admin')),
);
?>

<h1>View Contrato #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'sign_date',
		'production_date',
		'end_date',
		'id_carrier',
		'id_company',
	),
)); ?>

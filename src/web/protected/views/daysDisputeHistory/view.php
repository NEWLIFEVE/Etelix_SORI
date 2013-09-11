<?php
/* @var $this DaysDisputeHistoryController */
/* @var $model DaysDisputeHistory */

$this->breadcrumbs=array(
	'Days Dispute Histories'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List DaysDisputeHistory', 'url'=>array('index')),
	array('label'=>'Create DaysDisputeHistory', 'url'=>array('create')),
	array('label'=>'Update DaysDisputeHistory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete DaysDisputeHistory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DaysDisputeHistory', 'url'=>array('admin')),
);
?>

<h1>View DaysDisputeHistory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'start_date',
		'end_date',
		'id_contrato',
		'days',
	),
)); ?>

<?php
/* @var $this DaysDisputeHistoryController */
/* @var $model DaysDisputeHistory */

$this->breadcrumbs=array(
	'Days Dispute Histories'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List DaysDisputeHistory', 'url'=>array('index')),
	array('label'=>'Create DaysDisputeHistory', 'url'=>array('create')),
	array('label'=>'View DaysDisputeHistory', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage DaysDisputeHistory', 'url'=>array('admin')),
);
?>

<h1>Update DaysDisputeHistory <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
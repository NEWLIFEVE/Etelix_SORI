<?php
/* @var $this DaysDisputeHistoryController */
/* @var $model DaysDisputeHistory */

$this->breadcrumbs=array(
	'Days Dispute Histories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DaysDisputeHistory', 'url'=>array('index')),
	array('label'=>'Manage DaysDisputeHistory', 'url'=>array('admin')),
);
?>

<h1>Create DaysDisputeHistory</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
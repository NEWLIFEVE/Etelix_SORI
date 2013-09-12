<?php
/* @var $this DaysDisputeHistoryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Days Dispute Histories',
);

$this->menu=array(
	array('label'=>'Create DaysDisputeHistory', 'url'=>array('create')),
	array('label'=>'Manage DaysDisputeHistory', 'url'=>array('admin')),
);
?>

<h1>Days Dispute Histories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

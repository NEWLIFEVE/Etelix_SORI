<?php
/* @var $this CarrierGroupsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Carrier Groups',
);

$this->menu=array(
	array('label'=>'Create CarrierGroups', 'url'=>array('create')),
	array('label'=>'Manage CarrierGroups', 'url'=>array('admin')),
);
?>

<h1>Carrier Groups</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

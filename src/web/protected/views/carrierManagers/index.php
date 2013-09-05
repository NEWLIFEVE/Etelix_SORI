<?php
/* @var $this CarrierManagersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Carrier Managers',
);

$this->menu=array(
	array('label'=>'Create CarrierManagers', 'url'=>array('create')),
	array('label'=>'Manage CarrierManagers', 'url'=>array('admin')),
);
?>

<h1>Carrier Managers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

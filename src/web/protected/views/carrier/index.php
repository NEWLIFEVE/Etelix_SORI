<?php
/* @var $this CarrierController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Carriers',
);

$this->menu=array(
	array('label'=>'Create Carrier', 'url'=>array('create')),
	array('label'=>'Manage Carrier', 'url'=>array('admin')),
);
?>

<h1>Carriers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

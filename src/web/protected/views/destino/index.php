<?php
/* @var $this DestinationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Destinations',
);

$this->menu=array(
	array('label'=>'Create Destination', 'url'=>array('create')),
	array('label'=>'Manage Destination', 'url'=>array('admin')),
);
?>

<h1>Destinations</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

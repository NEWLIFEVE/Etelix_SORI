<?php
/* @var $this DestinationIntController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Destination Ints',
);

$this->menu=array(
	array('label'=>'Create DestinationInt', 'url'=>array('create')),
	array('label'=>'Manage DestinationInt', 'url'=>array('admin')),
);
?>

<h1>Destination Ints</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

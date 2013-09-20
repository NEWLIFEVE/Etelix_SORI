<?php
/* @var $this GeographicZoneController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Geographic Zones',
);

$this->menu=array(
	array('label'=>'Create GeographicZone', 'url'=>array('create')),
	array('label'=>'Manage GeographicZone', 'url'=>array('admin')),
);
?>

<h1>Geographic Zones</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

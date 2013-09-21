<?php
/* @var $this GeographicZoneController */
/* @var $model GeographicZone */

$this->breadcrumbs=array(
	'Geographic Zones'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List GeographicZone', 'url'=>array('index')),
	array('label'=>'Manage GeographicZone', 'url'=>array('admin')),
);
?>

<h1>Create GeographicZone</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
/* @var $this GeographicZoneController */
/* @var $model GeographicZone */

$this->breadcrumbs=array(
	'Geographic Zones'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GeographicZone', 'url'=>array('index')),
	array('label'=>'Create GeographicZone', 'url'=>array('create')),
	array('label'=>'View GeographicZone', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GeographicZone', 'url'=>array('admin')),
);
?>

<h1>Update GeographicZone <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
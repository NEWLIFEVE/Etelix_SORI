<?php
/* @var $this ZonaGeograficaController */
/* @var $model ZonaGeografica */

$this->breadcrumbs=array(
	'Zona Geograficas'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ZonaGeografica', 'url'=>array('index')),
	array('label'=>'Manage ZonaGeografica', 'url'=>array('admin')),
);
?>

<h1>Dist. Geografica</h1>

<?php echo $this->renderPartial('_ZonaGeoDestination', array('model'=>$model)); ?>
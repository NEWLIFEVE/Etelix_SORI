<?php
/* @var $this ZonaGeograficaController */
/* @var $model ZonaGeografica */

$this->breadcrumbs=array(
	'Zona Geograficas'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ZonaGeografica', 'url'=>array('index')),
	array('label'=>'Create ZonaGeografica', 'url'=>array('create')),
	array('label'=>'View ZonaGeografica', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ZonaGeografica', 'url'=>array('admin')),
);
?>

<h1>Update ZonaGeografica <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
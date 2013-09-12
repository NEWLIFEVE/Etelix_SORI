<?php
/* @var $this LimitesController */
/* @var $model Limites */

$this->breadcrumbs=array(
	'Limites'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Limites', 'url'=>array('index')),
	array('label'=>'Create Limites', 'url'=>array('create')),
	array('label'=>'View Limites', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Limites', 'url'=>array('admin')),
);
?>

<h1>Update Limites <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
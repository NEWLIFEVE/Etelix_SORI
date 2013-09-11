<?php
/* @var $this LimitesController */
/* @var $model Limites */

$this->breadcrumbs=array(
	'Limites'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Limites', 'url'=>array('index')),
	array('label'=>'Manage Limites', 'url'=>array('admin')),
);
?>

<h1>Create Limites</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
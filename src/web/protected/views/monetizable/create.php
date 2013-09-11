<?php
/* @var $this MonetizableController */
/* @var $model Monetizable */

$this->breadcrumbs=array(
	'Monetizables'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Monetizable', 'url'=>array('index')),
	array('label'=>'Manage Monetizable', 'url'=>array('admin')),
);
?>

<h1>Create Monetizable</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
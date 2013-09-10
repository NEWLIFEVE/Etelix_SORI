<?php
/* @var $this MonetizableController */
/* @var $model Monetizable */

$this->breadcrumbs=array(
	'Monetizables'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Monetizable', 'url'=>array('index')),
	array('label'=>'Create Monetizable', 'url'=>array('create')),
	array('label'=>'View Monetizable', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Monetizable', 'url'=>array('admin')),
);
?>

<h1>Update Monetizable <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
/* @var $this ManagersController */
/* @var $model Managers */

$this->breadcrumbs=array(
	'Managers'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Managers', 'url'=>array('index')),
	array('label'=>'Create Managers', 'url'=>array('create')),
	array('label'=>'View Managers', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Managers', 'url'=>array('admin')),
);
?>

<h1>Update Managers <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
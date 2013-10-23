<?php
/* @var $this CarrierGroupsController */
/* @var $model CarrierGroups */

$this->breadcrumbs=array(
	'Carrier Groups'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CarrierGroups', 'url'=>array('index')),
	array('label'=>'Create CarrierGroups', 'url'=>array('create')),
	array('label'=>'View CarrierGroups', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CarrierGroups', 'url'=>array('admin')),
);
?>

<h1>Update CarrierGroups <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
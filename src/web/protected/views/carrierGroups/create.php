<?php
/* @var $this CarrierGroupsController */
/* @var $model CarrierGroups */

$this->breadcrumbs=array(
	'Carrier Groups'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CarrierGroups', 'url'=>array('index')),
	array('label'=>'Manage CarrierGroups', 'url'=>array('admin')),
);
?>

<h1>Create CarrierGroups</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
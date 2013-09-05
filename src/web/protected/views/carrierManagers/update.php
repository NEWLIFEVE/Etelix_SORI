<?php
/* @var $this CarrierManagersController */
/* @var $model CarrierManagers */

$this->breadcrumbs=array(
	'Carrier Managers'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CarrierManagers', 'url'=>array('index')),
	array('label'=>'Create CarrierManagers', 'url'=>array('create')),
	array('label'=>'View CarrierManagers', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CarrierManagers', 'url'=>array('admin')),
);
?>

<h1>Update CarrierManagers <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
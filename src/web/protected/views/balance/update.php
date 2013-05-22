<?php
/* @var $this BalanceController */
/* @var $model Balance */

$this->breadcrumbs=array(
	'Balances'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Balance', 'url'=>array('index')),
	array('label'=>'Create Balance', 'url'=>array('create')),
	array('label'=>'View Balance', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Balance', 'url'=>array('admin')),
);
?>

<h1>Update Balance <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
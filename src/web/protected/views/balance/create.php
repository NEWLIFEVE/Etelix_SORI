<?php
/* @var $this BalanceController */
/* @var $model Balance */

$this->breadcrumbs=array(
	'Balances'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Balance', 'url'=>array('index')),
	array('label'=>'Manage Balance', 'url'=>array('admin')),
);
?>

<h1>Create Balance</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
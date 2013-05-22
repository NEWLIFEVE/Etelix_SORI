<?php
/* @var $this BalanceController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Balances',
);

$this->menu=array(
	array('label'=>'Create Balance', 'url'=>array('create')),
	array('label'=>'Manage Balance', 'url'=>array('admin')),
);
?>

<h1>Balances</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

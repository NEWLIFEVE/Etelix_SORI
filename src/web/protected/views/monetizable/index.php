<?php
/* @var $this MonetizableController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Monetizables',
);

$this->menu=array(
	array('label'=>'Create Monetizable', 'url'=>array('create')),
	array('label'=>'Manage Monetizable', 'url'=>array('admin')),
);
?>

<h1>Monetizables</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

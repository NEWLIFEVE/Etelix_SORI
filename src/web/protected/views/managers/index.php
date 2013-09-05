<?php
/* @var $this ManagersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Managers',
);

$this->menu=array(
	array('label'=>'Create Managers', 'url'=>array('create')),
	array('label'=>'Manage Managers', 'url'=>array('admin')),
);
?>

<h1>Managers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

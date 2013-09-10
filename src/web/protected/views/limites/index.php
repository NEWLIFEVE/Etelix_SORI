<?php
/* @var $this LimitesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Limites',
);

$this->menu=array(
	array('label'=>'Create Limites', 'url'=>array('create')),
	array('label'=>'Manage Limites', 'url'=>array('admin')),
);
?>

<h1>Limites</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

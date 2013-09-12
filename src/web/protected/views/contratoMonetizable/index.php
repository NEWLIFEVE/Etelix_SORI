<?php
/* @var $this ContratoMonetizableController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Contrato Monetizables',
);

$this->menu=array(
	array('label'=>'Create ContratoMonetizable', 'url'=>array('create')),
	array('label'=>'Manage ContratoMonetizable', 'url'=>array('admin')),
);
?>

<h1>Contrato Monetizables</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

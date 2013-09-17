<?php
/* @var $this TerminoPagoController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Termino Pagos',
);

$this->menu=array(
	array('label'=>'Create TerminoPago', 'url'=>array('create')),
	array('label'=>'Manage TerminoPago', 'url'=>array('admin')),
);
?>

<h1>Termino Pagos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

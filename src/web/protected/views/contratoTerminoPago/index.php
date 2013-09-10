<?php
/* @var $this ContratoTerminoPagoController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Contrato Termino Pagos',
);

$this->menu=array(
	array('label'=>'Create ContratoTerminoPago', 'url'=>array('create')),
	array('label'=>'Manage ContratoTerminoPago', 'url'=>array('admin')),
);
?>

<h1>Contrato Termino Pagos</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

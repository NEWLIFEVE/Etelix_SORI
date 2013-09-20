<?php
/* @var $this ContratoTerminoPagoController */
/* @var $model ContratoTerminoPago */

$this->breadcrumbs=array(
	'Contrato Termino Pagos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ContratoTerminoPago', 'url'=>array('index')),
	array('label'=>'Manage ContratoTerminoPago', 'url'=>array('admin')),
);
?>

<h1>Create ContratoTerminoPago</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
/* @var $this ContratoTerminoPagoController */
/* @var $model ContratoTerminoPago */

$this->breadcrumbs=array(
	'Contrato Termino Pagos'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ContratoTerminoPago', 'url'=>array('index')),
	array('label'=>'Create ContratoTerminoPago', 'url'=>array('create')),
	array('label'=>'View ContratoTerminoPago', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ContratoTerminoPago', 'url'=>array('admin')),
);
?>

<h1>Update ContratoTerminoPago <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
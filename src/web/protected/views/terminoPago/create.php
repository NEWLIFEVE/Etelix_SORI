<?php
/* @var $this TerminoPagoController */
/* @var $model TerminoPago */

$this->breadcrumbs=array(
	'Termino Pagos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TerminoPago', 'url'=>array('index')),
	array('label'=>'Manage TerminoPago', 'url'=>array('admin')),
);
?>

<h1>Create TerminoPago</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
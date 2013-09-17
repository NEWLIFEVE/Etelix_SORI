<?php
/* @var $this TerminoPagoController */
/* @var $model TerminoPago */

$this->breadcrumbs=array(
	'Termino Pagos'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TerminoPago', 'url'=>array('index')),
	array('label'=>'Create TerminoPago', 'url'=>array('create')),
	array('label'=>'View TerminoPago', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage TerminoPago', 'url'=>array('admin')),
);
?>

<h1>Update TerminoPago <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
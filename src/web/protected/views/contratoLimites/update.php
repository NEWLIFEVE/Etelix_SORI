<?php
/* @var $this ContratoLimitesController */
/* @var $model ContratoLimites */

$this->breadcrumbs=array(
	'Contrato Limites'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ContratoLimites', 'url'=>array('index')),
	array('label'=>'Create ContratoLimites', 'url'=>array('create')),
	array('label'=>'View ContratoLimites', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ContratoLimites', 'url'=>array('admin')),
);
?>

<h1>Update ContratoLimites <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
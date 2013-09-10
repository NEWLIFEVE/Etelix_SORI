<?php
/* @var $this ContratoLimitesController */
/* @var $model ContratoLimites */

$this->breadcrumbs=array(
	'Contrato Limites'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ContratoLimites', 'url'=>array('index')),
	array('label'=>'Manage ContratoLimites', 'url'=>array('admin')),
);
?>

<h1>Create ContratoLimites</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
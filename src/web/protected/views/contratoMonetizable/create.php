<?php
/* @var $this ContratoMonetizableController */
/* @var $model ContratoMonetizable */

$this->breadcrumbs=array(
	'Contrato Monetizables'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ContratoMonetizable', 'url'=>array('index')),
	array('label'=>'Manage ContratoMonetizable', 'url'=>array('admin')),
);
?>

<h1>Create ContratoMonetizable</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
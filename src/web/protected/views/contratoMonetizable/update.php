<?php
/* @var $this ContratoMonetizableController */
/* @var $model ContratoMonetizable */

$this->breadcrumbs=array(
	'Contrato Monetizables'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ContratoMonetizable', 'url'=>array('index')),
	array('label'=>'Create ContratoMonetizable', 'url'=>array('create')),
	array('label'=>'View ContratoMonetizable', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ContratoMonetizable', 'url'=>array('admin')),
);
?>

<h1>Update ContratoMonetizable <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
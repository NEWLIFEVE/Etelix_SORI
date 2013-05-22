<?php
/* @var $this CarrierController */
/* @var $model Carrier */

$this->breadcrumbs=array(
	'Carriers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Carrier', 'url'=>array('index')),
	array('label'=>'Manage Carrier', 'url'=>array('admin')),
);
?>

<h1>Create Carrier</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
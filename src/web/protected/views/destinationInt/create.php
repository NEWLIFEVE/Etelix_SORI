<?php
/* @var $this DestinationIntController */
/* @var $model DestinationInt */

$this->breadcrumbs=array(
	'Destination Ints'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List DestinationInt', 'url'=>array('index')),
	array('label'=>'Manage DestinationInt', 'url'=>array('admin')),
);
?>

<h1>Create DestinationInt</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php
/* @var $this ZonaGeograficaController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Zona Geograficas',
);

$this->menu=array(
	array('label'=>'Create ZonaGeografica', 'url'=>array('create')),
	array('label'=>'Manage ZonaGeografica', 'url'=>array('admin')),
);
?>

<h1>Zona Geograficas</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

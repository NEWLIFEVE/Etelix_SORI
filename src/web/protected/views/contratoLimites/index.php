<?php
/* @var $this ContratoLimitesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Contrato Limites',
);

$this->menu=array(
	array('label'=>'Create ContratoLimites', 'url'=>array('create')),
	array('label'=>'Manage ContratoLimites', 'url'=>array('admin')),
);
?>

<h1>Contrato Limites</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

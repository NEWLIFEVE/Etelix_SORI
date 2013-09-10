<?php
/* @var $this ContratoTerminoPagoController */
/* @var $model ContratoTerminoPago */

$this->breadcrumbs=array(
	'Contrato Termino Pagos'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List ContratoTerminoPago', 'url'=>array('index')),
	array('label'=>'Create ContratoTerminoPago', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#contrato-termino-pago-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Contrato Termino Pagos</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'contrato-termino-pago-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'start_date',
		'end_date',
		'id_contrato',
		'id_termino_pago',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

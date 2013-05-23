<?php
/* @var $this BalanceController */
/* @var $model Balance */

$this->breadcrumbs=array(
	'Balances'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Balance', 'url'=>array('index')),
	array('label'=>'Create Balance', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#balance-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Balances</h1>

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
	'id'=>'balance-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
                array(
                    'name' => 'id_carrier',
                    'value' => '$data->idCarrier->name',
                    'type' => 'text',
//                    'filter' => Cabina::getListCabina(),
//                    'htmlOptions' => array(
//                    'style' => 'text-align: center;',
                    ),
                ),
		'date',
		'minutes',
		'acd',
		'asr',
		'margin_percentage',
		/*
		'margin_per_minute',
		'cost_per_minute',
		'revenue_per_min',
		'pdd',
		'incomplete_calls',
		'complete_calls_ner',
		'complete_calls',
		'calls_attempts',
		'duration_real',
		'duration_cost',
		'ner02_efficient',
		'ner02_seizure',
		'pdd_calls',
		'revenue',
		'cost',
		'margin',
		'date_change',
		'id_carrier',
		'id_destination',
		*/
		array(
			'class'=>'CButtonColumn',
		),

)); ?>

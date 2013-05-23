<?php
/* @var $this BalanceController */

$this->breadcrumbs=array(
	'Ventas',
);
?>
<h1><?php echo $this->id . ' de ' . $this->action->id; ?></h1>
<?php
$this->widget('zii.widgets.grid.CGridView',array(
	'dataProvider'=>$model->search('ventas'),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'date',
			),
            
             array(
                    'name' => 'id_carrier',
                    'value' => '$data->idCarrier->name',
                    'type' => 'text',
//                    'filter' => Cabina::getListCabina(),
//                    'htmlOptions' => array(
//                    'style' => 'text-align: center;',
                    ),
		array(
			'name'=>'minutes',
			),
		array(
			'name'=>'acd',
			),
		array(
			'name'=>'asr',
			),
		array(
			'name'=>'margin_percentage',
			),
		array(
			'name'=>'margin_per_minute',
			),
		array(
			'name'=>'cost_per_minute',
			),
		array(
			'name'=>'revenue_per_min',
			),
//		array(
//			'name'=>'pdd',
//			),
//		array(
//			'name'=>'incomplete_calls',
//			),
//		array(
//			'name'=>'complete_calls_ner',
//			),
//		array(
//			'name'=>'complete_calls',
//			),
//		array(
//			'name'=>'calls_attempts',
//			),
//		array(
//			'name'=>'duration_real',
//			),
		
		array(
			'header'=>'Detalle',
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			),
		),
	));
?>

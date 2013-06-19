<?php
/* @var $this BalanceController */

$this->breadcrumbs=array(
	'Compras',
);
?>
<h1><?php echo $this->id . ' de ' . $this->action->id; ?></h1>
<?php
$this->widget('zii.widgets.grid.CGridView',array(
	'dataProvider'=>$model->search('compras'),
	'htmlOptions'=>array(
		'class'=>'grid-view gridviewmod'
		),
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
                    'name' => 'id_destination',
                    'value' => '$data->idDestination->nombre',
                    'type' => 'text',
//                    'filter' => Cabina::getListCabina(),
//                    'htmlOptions' => array(
//                    'style' => 'text-align: center;',
                    ),
		array(
			'name'=>'minutes',
			'value'=>'Formatter::formatDecimal($data->minutes)',
			),
		array(
			'name'=>'acd',
			'value'=>'Formatter::formatDecimal($data->acd)',
			),
		array(
			'name'=>'asr',
			'value'=>'Formatter::formatDecimal($data->asr)',
			),
		array(
			'name'=>'margin_percentage',
			'value'=>'Formatter::formatDecimal($data->margin_percentage)',
			),
		array(
			'name'=>'margin_per_minute',
			'value'=>'Formatter::formatDecimal($data->margin_per_minute)',
			),
		array(
			'name'=>'cost_per_minute',
			'value'=>'Formatter::formatDecimal($data->cost_per_minute)',
			),
		array(
			'name'=>'revenue_per_min',
			'value'=>'Formatter::formatDecimal($data->revenue_per_min)',
			),
		array(
			'name'=>'pdd',
			'value'=>'Formatter::formatDecimal($data->pdd)',
			),
		array(
			'name'=>'incomplete_calls',
			'value'=>'Formatter::formatDecimal($data->incomplete_calls)',
			),
		array(
			'name'=>'complete_calls_ner',
			'value'=>'Formatter::formatDecimal($data->complete_calls_ner)',
			),
		array(
			'name'=>'complete_calls',
			'value'=>'Formatter::formatDecimal($data->complete_calls)',
			),
		array(
			'name'=>'calls_attempts',
			'value'=>'Formatter::formatDecimal($data->calls_attempts)',
			),
		array(
			'name'=>'duration_real',
			'value'=>'Formatter::formatDecimal($data->duration_real)',
			),
		array(
			'name'=>'duration_cost',
			'value'=>'Formatter::formatDecimal($data->duration_cost)',
			),
//		array(
//			'name'=>'ner02_efficient',
//			'value'=>'Formatter::formatDecimal($data->ner02_efficient)',
//			),
//		array(
//			'name'=>'ner02_seizure',
//			'value'=>'Formatter::formatDecimal($data->ner02_seizure)',
//			),
//		array(
//			'name'=>'pdd_calls',
//			'value'=>'Formatter::formatDecimal($data->pdd_calls)',
//			),
		array(
			'name'=>'revenue',
			'value'=>'Formatter::formatDecimal($data->revenue)',
			),
		array(
			'header'=>'Detalle',
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			),
		),
	));
?>

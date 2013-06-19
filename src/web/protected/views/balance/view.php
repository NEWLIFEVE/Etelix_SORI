<?php
/* @var $this BalanceController */
/* @var $model Balance */

$this->breadcrumbs=array(
	'Balances'=>array('index'),
	$model->id,
);

//$this->menu=array(
//	array('label'=>'List Balance', 'url'=>array('index')),
//	array('label'=>'Create Balance', 'url'=>array('create')),
//	array('label'=>'Update Balance', 'url'=>array('update', 'id'=>$model->id)),
//	array('label'=>'Delete Balance', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
//	array('label'=>'Manage Balance', 'url'=>array('admin')),
//);
?>


<h1><?php echo $nombre." #".$model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
                array(
                'name'=>'Carrier',
                'value'=>$model->idCarrier->name,
                ),
                array(
                'name'=>'Destination',
                'value'=>$model->idDestination->nombre,
                ),
		
		'date',
		array(
			'name'=>'minutes',
                    'value'=>Formatter::formatDecimal(
                    $model->minutes
                    ),
                    ),
		array(
			'name'=>'acd',
                    'value'=>Formatter::formatDecimal(
                    $model->acd
                    ),
                    ),
		array(
			'name'=>'asr',
                    'value'=>Formatter::formatDecimal(
                    $model->asr
                    ),
                    ),
		array(
			'name'=>'margin_percentage',
                    'value'=>Formatter::formatDecimal(
                    $model->margin_percentage
                    ),
                    ),
		array(
			'name'=>'margin_per_minute',
                    'value'=>Formatter::formatDecimal(
                    $model->margin_per_minute
                    ),
                    ),
		array(
			'name'=>'cost_per_minute',
                    'value'=>Formatter::formatDecimal(
                    $model->cost_per_minute
                    ),
                    ),
		array(
			'name'=>'revenue_per_min',
                    'value'=>Formatter::formatDecimal(
                    $model->revenue_per_min
                    ),
                    ),
		array(
			'name'=>'pdd',
                    'value'=>Formatter::formatDecimal(
                    $model->pdd
                    ),
			),
		array(
			'name'=>'incomplete_calls',
                    'value'=>Formatter::formatDecimal(
                    $model->incomplete_calls
                    ),
			),
		array(
			'name'=>'complete_calls_ner',
                    'value'=>Formatter::formatDecimal(
                    $model->complete_calls_ner
                    ),
			),
		array(
			'name'=>'complete_calls',
                    'value'=>Formatter::formatDecimal(
                    $model->complete_calls
                    ),
			),
		array(
			'name'=>'calls_attempts',
                    'value'=>Formatter::formatDecimal(
                    $model->calls_attempts
                    ),
			),
		array(
			'name'=>'duration_real',
                    'value'=>Formatter::formatDecimal(
                    $model->duration_real
                    ),
			),
		array(
			'name'=>'duration_cost',
                    'value'=>Formatter::formatDecimal(
                    $model->duration_cost
                    ),
			),
		array(
			'name'=>'ner02_efficient',
                    'value'=>Formatter::formatDecimal(
                    $model->ner02_efficient
                    ),
			),
		array(
			'name'=>'ner02_seizure',
                    'value'=>Formatter::formatDecimal(
                    $model->ner02_seizure
                    ),
			),
		array(
			'name'=>'pdd_calls',
                    'value'=>Formatter::formatDecimal(
                    $model->pdd_calls
                    ),
			),
		array(
			'name'=>'revenue',
                    'value'=>Formatter::formatDecimal(
                    $model->revenue
                    ),
			),
		array(
			'name'=>'cost',
                    'value'=>Formatter::formatDecimal(
                    $model->cost
                    ),
			),
		array(
			'name'=>'margin',
                    'value'=>Formatter::formatDecimal(
                    $model->margin
                    ),
			),
		'date_change',
              

		
	),
                    
)); ?>

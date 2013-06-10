<?php
/* @var $this CarrierController */
/* @var $model Carrier */

$this->breadcrumbs=array(
	'Carriers'=>array('index'),
	'Administrar',
);

//$this->menu=array(
//	array('label'=>'List Carrier', 'url'=>array('index')),
//	array('label'=>'Create Carrier', 'url'=>array('create')),
//);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#carrier-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Administrar Carriers</h1>

<!--<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>-->

<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<!--<div class="search-form" style="display:none">-->
<?php //$this->renderPartial('_search',array(
	//'model'=>$model,
//)); ?>
<!--</div> --><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'carrier-grid',
	'dataProvider'=>$model->search(),
	'htmlOptions'=>array(
		'class'=>'grid-view gridviewmod'
		),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'name',
		'address',
		'record_date',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

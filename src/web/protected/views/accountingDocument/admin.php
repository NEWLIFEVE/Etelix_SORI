<?php
/* @var $this AccountingDocumentController */
/* @var $model AccountingDocument */

$this->breadcrumbs=array(
	'Accounting Documents'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List AccountingDocument', 'url'=>array('index')),
	array('label'=>'Create AccountingDocument', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#accounting-document-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Accounting Documents</h1>

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
	'id'=>'accounting-document-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'issue_date',
		'from_date',
		'to_date',
		'received_date',
		'sent_date',
		/*
		'doc_number',
		'minutes',
		'amount',
		'note',
		'id_type_accounting_document',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

<?php
/* @var $this AccountingDocumentController */
/* @var $model AccountingDocument */

$this->breadcrumbs=array(
	'Accounting Documents'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List AccountingDocument', 'url'=>array('index')),
	array('label'=>'Create AccountingDocument', 'url'=>array('create')),
	array('label'=>'Update AccountingDocument', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete AccountingDocument', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AccountingDocument', 'url'=>array('admin')),
);
?>

<h1>View AccountingDocument #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'issue_date',
		'from_date',
		'to_date',
		'received_date',
		'sent_date',
		'doc_number',
		'minutes',
		'amount',
		'note',
		'id_type_accounting_document',
	),
)); ?>

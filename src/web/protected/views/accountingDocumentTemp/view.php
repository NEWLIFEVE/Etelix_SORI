<?php
/* @var $this AccountingDocumentTempController */
/* @var $model AccountingDocumentTemp */

$this->breadcrumbs=array(
	'Accounting Document Temps'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List AccountingDocumentTemp', 'url'=>array('index')),
	array('label'=>'Create AccountingDocumentTemp', 'url'=>array('create')),
	array('label'=>'Update AccountingDocumentTemp', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete AccountingDocumentTemp', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AccountingDocumentTemp', 'url'=>array('admin')),
);
?>

<h1>View AccountingDocumentTemp #<?php echo $model->id; ?></h1>

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

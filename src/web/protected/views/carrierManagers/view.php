<?php
/* @var $this CarrierManagersController */
/* @var $model CarrierManagers */

$this->breadcrumbs=array(
	'Carrier Managers'=>array('index'),
	$model->id,
);

//$this->menu=array(
//	array('label'=>'List CarrierManagers', 'url'=>array('index')),
//	array('label'=>'Create CarrierManagers', 'url'=>array('create')),
//	array('label'=>'Update CarrierManagers', 'url'=>array('update', 'id'=>$model->id)),
//	array('label'=>'Delete CarrierManagers', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
//	array('label'=>'Manage CarrierManagers', 'url'=>array('admin')),
//);
//?>

<h1>id #<?php echo $model->id; ?></h1>
<h1>Manager #<?php echo $_GET['id_managers']; ?></h1>
<h1>Carrier#<?php echo $_GET['id_carrier']; ?></h1>

<?php //$this->widget('zii.widgets.CDetailView', array(
//	'data'=>$model,
//	'attributes'=>array(
//		'start_date',
//		'end_date',
//		'id_carrier',
//		'id_managers',
//		'id',
//	),
//)); ?>

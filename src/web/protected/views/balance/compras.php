<?php
/* @var $this BalanceController */

$this->breadcrumbs=array(
	'Balance',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>
<?php
$this->widget('zii.widgets.grid.CGridView',array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
			'name'=>'date',
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
		),
	));
?>

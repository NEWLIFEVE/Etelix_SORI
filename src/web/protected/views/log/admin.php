<?php
/* @var $this LogController */
/* @var $model Log */

//$this->breadcrumbs=array(
//	'Logs'=>array('index'),
//	'Manage',
//);
//
//$this->menu=array(
//	array('label'=>'List Log', 'url'=>array('index')),
//	array('label'=>'Create Log', 'url'=>array('create')),
//);

//Yii::app()->clientScript->registerScript('search', "
//$('.search-button').click(function(){
//	$('.search-form').toggle();
//	return false;
//});
//$('.search-form form').submit(function(){
//	$('#log-grid').yiiGridView('update', {
//		data: $(this).serialize()
//	});
//	return false;
//});
//");
?>

<h1>Logs</h1>

<!--<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>-->

<?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div> 
<!--search-form--> 

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'log-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
    'ajaxUpdate'=>true,
    'htmlOptions'=>array(
		'class'=>'grid-view gridviewmod'
		),
	'columns'=>array(
		//'id',
		//'date',
            array(
                   'name' => 'date',
                   'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                               'model'=>$model, 
                               'attribute'=>'date', 
                               'language' => 'ja',
                               'i18nScriptFile' => 'jquery.ui.datepicker-ja.js', // (#2)
                               'htmlOptions' => array(
                                                'id' => 'datepicker_for_Fecha',
                                                'size' => '10',
                                             ),
                               'defaultOptions' => array(  // (#3)
                                                   'showOn' => 'focus', 
                                                   'dateFormat' => 'yy-mm-dd',
                                                   'showOtherMonths' => true,
                                                   'selectOtherMonths' => true,
                                                   'changeMonth' => true,
                                                   'changeYear' => true,
                                                   'showButtonPanel' => true,
                                                   )
                                           ), 
                               true),
                   'htmlOptions'=>array(
                                  'style'=>'text-align: center;',
                       'width'=>'180px'
                                  ),
                   ),
		//'hour',
                   array(
        'name'=>'hour',
        'htmlOptions'=>array(
          'style'=>'text-align: center;',
             'width'=>'180px'
          ),
        ),
		//'id_log_action',
		
             array(
            'header'=>'Accion',
            'name'=>'id_log_action',
            'value'=>'$data->idLogAction->name',
            'htmlOptions'=>array(
                'style'=>'text-align: center',
                'width'=>'280px'
                ),
            //'filter' => User::getListNombre(),
            ),
             array(
            'header'=>'Usuario',
            'name'=>'id_users',
            'value'=>'$data->idUsers->username',
            'htmlOptions'=>array(
                'style'=>'text-align: center',
                'width'=>'180px'
                ),
            //'filter' => User::getListNombre(),
            ),
		//'description_date',
                               array(
        'name'=>'description_date',
        'htmlOptions'=>array(
          'style'=>'text-align: center;',
             'width'=>'180px'
          ),
        ),
		/*
		'id_esp',
		*/
		array(
			'class'=>'CButtonColumn',
                    'template'=>'{view}{update}',
		),
	),
)); ?>

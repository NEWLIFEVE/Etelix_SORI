<?php
/* @var $this ManagersController */
/* @var $model Managers */

$this->breadcrumbs=array(
	'CarrierManagers'=>array('index'),
	'Manage',
);

//$this->menu=array(
//	array('label'=>'List Managers', 'url'=>array('index')),
//	array('label'=>'Create Managers', 'url'=>array('create')),
//);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#managers-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
	                           <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/otros.css" />
<h1>Admin Managers</h1>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'Managers',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<div class='divVendedor'>
	<?php echo $form->labelEx($model,'Managers'); ?>
	<?php echo $form->dropDownList($model,'id',
                CHtml::listData(Managers::model()->findAll(),'id','name'),
                array(
                    'ajax'=>array(
                        'type'=>'POST',
                        'url'=>CController::createUrl('carrier/DynamicAsignados'),
                        'update'=>'#select_left',
                    ),'prompt'=>'Seleccione'
                     )
                ); ?>
	<?php echo $form->error($model,'id'); ?>
</div>

<div class="row divCarrier">
    <?php
    $this->widget('ext.widgets.multiselects.XMultiSelects', array(
        'leftTitle' => 'Carriers Asignados',
        'leftName' => 'Asignados',
        'leftList' => array(),
        'rightTitle' => 'Carriers No Asignados',
        'rightName' => 'No Asignados',
        'rightList' =>Managers::model()->getListCarriersNOAsignados(),
        'size' => 15,  
        'width' => '400px',
    ));
    ?>
		<?php echo $form->error($model,'lastname'); ?>  
</div>
<?php $this->endWidget(); ?>
<br>
        <div id='botAsignar' class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
</div>

<script>
          $("#options_right").on( "click",  function asignadosAnoasignados()
            {
                var valor= $("#select_left").val();
                var texto = $("#select_left option:selected").text();
                    if (texto!="")
                    { 
                        $("#select_left option[value='"+valor+"']").remove().callback(
                        $('#select_right').append("<option value='"+valor+"'>"+texto+"</option>"));  
                    }
            });
          $("#options_left").on( "click",  function noasignadosAasignados()
            { 
                var valor= $("#select_right").val();
                var texto = $("#select_right option:selected").text();
                    if (texto!="")
                    {  
                        $("#select_right option[value='"+valor+"']").remove().callback(
                        $('#select_left').append("<option value='"+valor+"'>"+texto+"</option>"));  
                    }
            });
//           $("#options_right_all").on( "click",  function todosasignadosAnoasignados()
//            {
//                var valor= $("#select_left").val();
//                var texto = $("#select_left option:selected").text();
//                var llenar = "<option value='"+valor+"'>"+texto+"</option>";
//                var vaciar="#select_left option[value='"+valor+"']";
//                   for (var texto=0; texto<"#select_left".options.length; texto++)
//                    { 
//                        $(vaciar).remove().callback(
//                        $('#select_right').append(llenar));  
//                    }
//            }); 
</script>
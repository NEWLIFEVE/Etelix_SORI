<?php
/* @var $this ManagersController */
/* @var $model Managers */

//$this->breadcrumbs=array(
//	'CarrierManagers'=>array('index'),
//	'Manage',
//);

//$this->menu=array(
//	array('label'=>'List Managers', 'url'=>array('index')),
//	array('label'=>'Create Managers', 'url'=>array('create')),
//);
?>

<h1>Distribucion Comercial</h1>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'carrier-managers-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<div class='divVendedor'>
	<?php echo $form->labelEx($model,'id_managers'); ?>
	<?php echo $form->dropDownList($model,'id_managers',
                CHtml::listData(Managers::model()->findAll(array('order'=>'lastname')),'id','lastname'),
                array(
                    'ajax'=>array(
                        'type'=>'POST',
                        'url'=>CController::createUrl('DynamicAsignados'),
                        'update'=>'#select_left',
                    ),'prompt'=>'Seleccione'
                     )
                ); ?>
	<?php echo $form->error($model,'id_managers'); ?>
</div>

<div class="row divCarrier" id="carriers">
    <?php
    $this->widget('ext.widgets.multiselects.XMultiSelects', array(
        'leftTitle' => 'Carriers Asignados',
        'leftName' => 'Asignados[]',
        'leftList' => array(),
        'rightTitle' => 'Carriers No Asignados',
        'rightName' => 'No_Asignados[]',
        'rightList' =>Managers::model()->getListCarriersNOAsignados(),
        'size' => 15,  
        'width' => '400px',
    ));
    ?>
		<?php echo $form->error($model,'lastname'); ?>  
</div>
    
        
<?php $this->endWidget(); ?>
  <div id='botAsignar' class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Guardar' : 'Save'); ?>
	</div>
</div><!-- form -->
<div id="contenedor">
</div>
<script>
          

  $("#botAsignar").on( "click",  function asignadosAnoasignados(){
      
   $("#carriers select option").prop("selected",true); 
   var manager = $("#CarrierManagers_id_managers").val();
   var asignados = $("#select_left").val();
   var noasignados = $("#select_right").val();   
      $.ajax({
          type: "GET",
          url: "UpdateDistComercial",
          data: "asignados="+asignados+"&noasignados="+noasignados+"&manager="+manager,
          success: function(data) {
              alert(data);
          }
      });
    $("#carriers select option").prop("selected",false);       
  });
          
          $("#options_right").on( "click",  function asignadosAnoasignados(){
                $('#select_left :selected').each(function(i,selected){                        
                    $("#select_left option[value='"+$(selected).val()+"']").remove();
                    $('#select_right').append("<option value='"+$(selected).val()+"'>"+$(selected).text()+"</option>");
                });
            });
            
          $("#options_left").on( "click",  function noasignadosAasignados(){ 
                $('#select_right :selected').each(function(i,selected){                        
                    $("#select_right option[value='"+$(selected).val()+"']").remove();
                    $('#select_left').append("<option value='"+$(selected).val()+"'>"+$(selected).text()+"</option>");
                });
            });
   
            $("#CarrierManagers_id_managers").change(function(){
                    $.ajax({
                        type:'POST',
                        url: "DynamicNoAsignados",
                        success: function(data){
                            $("#select_right").empty();
                            $("#select_right").append(""+data+"");
                        }                   
                  });
            });
</script>
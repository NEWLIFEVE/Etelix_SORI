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
        <?php echo $form->dropDownList($model,'id_managers', Managers::getNameList(),
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
//        'width' => '400px',
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
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>
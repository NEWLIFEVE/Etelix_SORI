<?php
/* @var $this AccountingDocumentController */
/* @var $model AccountingDocument */

//$this->breadcrumbs=array(
//	'Accounting Documents'=>array('index'),
//	'Create',
//);
//
//$this->menu=array(
//	array('label'=>'List AccountingDocument', 'url'=>array('index')),
//	array('label'=>'Manage AccountingDocument', 'url'=>array('admin')),
//);
?>

<h1>Confirmar Facturas Enviadas</h1>

<?php echo $this->renderPartial('FacEnvConfirm', array('model'=>$model,'lista'=>$lista)); ?>
<?php // echo $this->renderPartial('_form', array('model'=>$model)); ?>
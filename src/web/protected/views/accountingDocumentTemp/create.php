<?php
/* @var $this AccountingDocumentTempController */
/* @var $model AccountingDocumentTemp */

//$this->breadcrumbs=array(
//	'Accounting Document Temps'=>array('index'),
//	'Create',
//);

//$this->menu=array(
//	array('label'=>'List AccountingDocumentTemp', 'url'=>array('index')),
//	array('label'=>'Manage AccountingDocumentTemp', 'url'=>array('admin')),
//);
?>

<h1>Documentos Contables</h1>

<?php // echo $this->renderPartial('_form', array('model'=>$model,'lista'=>$lista)); ?>

<?php echo $this->renderPartial('_form', array('model'=>$model,
                                'lista_FacEnv'=>$lista_FacEnv,
                                'lista_FacRec'=>$lista_FacRec,
                                'lista_Pagos'=>$lista_Pagos,
                                'lista_Cobros'=>$lista_Cobros,
                                'lista_DispRec'=>$lista_DispRec,
                                'lista_DispEnv'=>$lista_DispEnv,
                                'lista_NotCredEnv'=>$lista_NotCredEnv,
                                'lista_NotCredRec'=>$lista_NotCredRec)
        ); ?>

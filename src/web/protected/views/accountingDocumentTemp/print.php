<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width">
    <meta name="description" content="Designa Studio, a HTML5 / CSS3 template.">
    <meta name="author" content="Sylvain Lafitte, Web Designer, sylvainlafitte.com">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <link rel="shortcut icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" type="image/png" href="favicon.png">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/style.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/divs.css">
    <link href="<?php echo Yii::app()->baseUrl; ?>/images/apple-touch-icon-114x114-precomposed.png" rel="apple-touch-icon-precomposed" sizes="114x114" />
    <link href="<?php echo Yii::app()->baseUrl; ?>/images/apple-touch-icon-144x144-precomposed.png" rel="apple-touch-icon-precomposed" sizes="144x144" />
    <!--<link rel="stylesheet" href="/resources/demos/style.css" />-->
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<?php
/* @var $this AccountingDocumentTempController */
/* @var $model AccountingDocumentTemp */
$this->layout=$this->getLayoutFile('print');
?>
<label class="Label_F_Rec" <?php if($lista_FacRec==null){echo "style='display:none;'";}?>>Facturas Recibidas:</label>
<table border="1" class="tablaVistDocTemporales lista_FacRec" <?php if($lista_FacRec==null){echo "style='display:none;'";}?>>
	<tr>
		<td> Carrier </td>
		<td> Fecha de Emisión </td>
		<td> Inicio Periodo a Facturar </td>
		<td> Fin Periodo a Facturar </td>
		<td> Fecha Recep(Email)</td>
		<td> Fecha Recep Valida</td>
		<td> Hora Recep (Email)</td>
		<td> Hora Recep Valida</td>
		<td> N°Doc </td>
		<td> Minutos </td>
		<td> Cantidad </td>
		<td> Moneda </td>
	</tr>
<?php
	if($lista_FacRec!=null)
	{
		foreach ($lista_FacRec as $key => $value)
		{
			echo "<tr class='vistaTemp' id='".$value->id."'>
					<td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
					<td id='AccountingDocumentTemp[issue_date]'>".$value->issue_date."</td>
					<td id='AccountingDocumentTemp[from_date]'>".$value->from_date."</td>
					<td id='AccountingDocumentTemp[to_date]'>".$value->to_date."</td>
					<td id='AccountingDocumentTemp[email_received_date]'>".$value->email_received_date."</td>
					<td id='AccountingDocumentTemp[valid_received_date]'>".$value->valid_received_date."</td>
					<td id='AccountingDocumentTemp[email_received_hour]'>".$value->email_received_hour."</td>
					<td id='AccountingDocumentTemp[valid_received_hour]'>".$value->valid_received_hour."</td>
					<td id='AccountingDocumentTemp[doc_number]'>".$value->doc_number."</td>
					<td id='AccountingDocumentTemp[minutes]'>".$value->minutes."</td>
					<td id='AccountingDocumentTemp[amount]'>".$value->amount."</td>
					<td id='AccountingDocumentTemp[id_currency]'>".$value->id_currency."</td>
				  </tr>";
		}
	}
?>
</table>
<br>
<label class="Label_F_Env" <?php if($lista_FacEnv==null){echo "style='display:none;'";}?>>Facturas Enviadas:</label>
<table border="1" class="tablaVistDocTemporales lista_FacEnv" <?php if($lista_FacEnv==null){echo "style='display:none;'";}?>>
	<tr>
		<td> Carrier </td>
		<td> Fecha de Emisión </td>
		<td> Inicio Periodo a Facturar </td>
		<td> Fin Periodo a Facturar </td>
		<td> Fecha Envio </td>
		<td> N°Documento </td>
		<td> Minutos </td>
		<td> Cantidad </td>
		<td> Moneda </td>
	</tr>
<?php
	if($lista_FacEnv!=null)
	{
		foreach ($lista_FacEnv as $key => $value)
		{
			echo "<tr class='vistaTemp' id='".$value->id."'>
					<td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
                    <td id='AccountingDocumentTemp[issue_date]'>".$value->issue_date."</td>
                    <td id='AccountingDocumentTemp[from_date]'>".$value->from_date."</td>
                    <td id='AccountingDocumentTemp[to_date]'>".$value->to_date."</td>
                    <td id='AccountingDocumentTemp[sent_date]'>".$value->sent_date."</td>
                    <td id='AccountingDocumentTemp[doc_number]'>".$value->doc_number."</td>
                    <td id='AccountingDocumentTemp[minutes]'>".$value->minutes."</td>
                    <td id='AccountingDocumentTemp[amount]'>".$value->amount."</td>
                    <td id='AccountingDocumentTemp[id_currency]'>".$value->id_currency."</td>
                  </tr>";
        }
    }
?>
</table>
<br>
<label class='LabelCobros' <?php if($lista_Cobros==null){echo "style='display:none;'";}?>>Cobros:</label>
<table border="1" class="tablaVistDocTemporales lista_Cobros" <?php if($lista_Cobros==null){echo "style='display:none;'";}?>>
	<tr>
		<td> Grupo </td>
        <td> Fecha Recep Valida</td>
        <td> N°Documento </td>
        <td> Cantidad </td>
        <td> Moneda </td>
    </tr>
<?php
	if($lista_Cobros!=null)
	{
		foreach ($lista_Cobros as $key => $value)
        { 
            echo "<tr class='vistaTemp' id='".$value->id."'>
                    <td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
                    <td id='AccountingDocumentTemp[valid_received_date]'>".$value->valid_received_date."</td>
                    <td id='AccountingDocumentTemp[doc_number]'>".$value->doc_number."</td>
                    <td id='AccountingDocumentTemp[amount]'>".$value->amount."</td>
                    <td id='AccountingDocumentTemp[id_currency]'>".$value->id_currency."</td>
                  </tr>";  
        }
    }
?>
</table>  
<br>
<label class="LabelPagos" <?php if($lista_Pagos==null){echo "style='display:none;'";}?>>Pagos:</label>
<table border="1" class="tablaVistDocTemporales lista_Pagos" <?php if($lista_Pagos==null){echo "style='display:none;'";}?>>
	<tr>
	    <td> Grupo </td>
	    <td> Fecha de Emisión </td>
	    <td> N°Documento </td>
	    <td> Cantidad </td>
	    <td> Moneda </td>
    </tr>
<?php
    if($lista_Pagos!=null)
    {
        foreach ($lista_Pagos as $key => $value)
        { 
            echo "<tr class='vistaTemp' id='".$value->id."'>
                    <td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
                    <td id='AccountingDocumentTemp[issue_date]'>".$value->issue_date."</td>
                    <td id='AccountingDocumentTemp[doc_number]'>".$value->doc_number."</td>
                    <td id='AccountingDocumentTemp[amount]'>".$value->amount."</td>
                    <td id='AccountingDocumentTemp[id_currency]'>".$value->id_currency."</td>
                  </tr>";  
        }
    }
?>
</table>
<br>
<label class="Label_DispRec" <?php if($lista_DispRec==null){echo "style='display:none;'";}?>>Disputas Recibidas:</label>
<table border="1" class="tablaVistDocTemporales lista_DispRec" <?php if($lista_DispRec==null){echo "style='display:none;'";}?>>
    <tr>
       <td> Carrier </td>
       <td> Destino </td>
       <td> Num. Factura </td>
       <td> Min Etx </td>
       <td> Min Prov </td>
       <td> Tarifa Etx </td>
       <td> Tarifa Prov </td>
       <td> Monto Etx</td>
       <td> Monto Prov</td>
       <td> Disputa</td>
    </tr>
<?php
    if($lista_DispRec!=null)
    {
        foreach ($lista_DispRec as $key => $value)
        { 
            echo "<tr class='vistaTemp' id='".$value->id."'>
                    <td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
                    <td id='AccountingDocumentTemp[id_destination]'>".$value->id_destination."</td>
                    <td id='AccountingDocumentTemp[id_accounting_document]'>".$value->id_accounting_document."</td>
                    <td id='AccountingDocumentTemp[min_etx]'>".$value->min_etx."</td>
                    <td id='AccountingDocumentTemp[min_carrier]'>".$value->min_carrier."</td>
                    <td id='AccountingDocumentTemp[rate_etx]'>".$value->rate_etx."</td>
                    <td id='AccountingDocumentTemp[rate_carrier]'>".$value->rate_carrier."</td>
                    <td id='AccountingDocumentTemp[amount_etx]'>".$value->amount_etx."</td>
                    <td id='AccountingDocumentTemp[amount]'>".$value->amount."</td>
                    <td id='AccountingDocumentTemp[dispute]'>".Utility::format_decimal($value->dispute)."</td>
                  </tr>";     
        }
    }
?>
</table>
<br>
<label class="Label_DispEnv" <?php if($lista_DispEnv==null){echo "style='display:none;'";}?>>Disputas Enviadas:</label>
<table border="1" class="tablaVistDocTemporales lista_DispEnv" <?php if($lista_DispEnv==null){echo "style='display:none;'";}?>>
    <tr>
		<td> Carrier </td>
		<td> Destino Supp </td>
		<td> Num. Factura </td>
		<td> Min Etx </td>
		<td> Min Prov </td>
		<td> Tarifa Etx </td>
		<td> Tarifa Prov </td>
		<td> Monto Etx</td>
		<td> Monto Prov</td>
		<td> Disputa</td>
    </tr>
<?php
    if($lista_DispEnv!=null)
    {
        foreach ($lista_DispEnv as $key => $value)
        { 
            echo "<tr class='vistaTemp' id='".$value->id."'>
                    <td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
                    <td id='AccountingDocumentTemp[id_destination]'>".$value->id_destination_supplier."</td>
                    <td id='AccountingDocumentTemp[id_accounting_document]'>".$value->id_accounting_document."</td>
                    <td id='AccountingDocumentTemp[min_etx]'>".$value->min_etx."</td>
                    <td id='AccountingDocumentTemp[min_carrier]'>".$value->min_carrier."</td>
                    <td id='AccountingDocumentTemp[rate_etx]'>".$value->rate_etx."</td>
                    <td id='AccountingDocumentTemp[rate_carrier]'>".$value->rate_carrier."</td>
                    <td id='AccountingDocumentTemp[amount_etx]'>".$value->amount_etx."</td>
                    <td id='AccountingDocumentTemp[amount]'>".$value->amount."</td>
                    <td id='AccountingDocumentTemp[dispute]'>".Utility::format_decimal($value->dispute)."</td>
                  </tr>";     
        }
    }
?>
</table>
  </table>
         <br>
         <label class="Label_NotCredEnv"<?php if($lista_NotCredEnv==null){echo "style='display:none;'";}?>>Notas de Crédito Enviadas:</label>
         <table border="1" class="tablaVistDocTemporales lista_NotCredEnv"<?php if($lista_NotCredEnv==null){echo "style='display:none;'";}?>>
                <tr> 
                   <td> Carrier </td>
                   <td> Num. Factura</td>
                   <td> Fecha de Emisión</td>
                   <td> Numero de Nota</td>
                   <td> Monto de Nota</td>
                </tr>
                <?php
                    if($lista_NotCredEnv!=null)
                    {
                        foreach ($lista_NotCredEnv as $key => $value)
                        { 
                            echo "<tr class='vistaTemp' id='".$value->id."'>
                                    <td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
                                    <td id='AccountingDocumentTemp[id_accounting_document]'>".$value->id_accounting_document."</td>
                                    <td id='AccountingDocumentTemp[issue_date]'>".$value->issue_date."</td> 
                                    <td id='AccountingDocumentTemp[doc_number]'>".$value->doc_number."</td>
                                    <td id='AccountingDocumentTemp[amount]'>".$value->amount."</td>    
                                    </tr>";     
                        }
                    }
                    ?>
         </table>
         <br>
         <label class="Label_NotCredRec"<?php if($lista_NotCredRec==null){echo "style='display:none;'";}?>>Notas de Crédito Recibidas:</label>
         <table border="1" class="tablaVistDocTemporales lista_NotCredRec"<?php if($lista_NotCredRec==null){echo "style='display:none;'";}?>>
                <tr>
                   <td> Carrier </td>
                   <td> Num. Factura</td>
                   <td> Fecha de Emisión</td>
                   <td> Numero de Nota</td>
                   <td> Monto de Nota</td>
                </tr>
                <?php
                    if($lista_NotCredRec!=null)
                    {
                        foreach ($lista_NotCredRec as $key => $value)
                        { 
                            echo "<tr class='vistaTemp' id='".$value->id."'>
                                    <td id='AccountingDocumentTemp[id_carrier]'>".$value->id_carrier."</td>
                                    <td id='AccountingDocumentTemp[id_accounting_document]'>".$value->id_accounting_document."</td>
                                    <td id='AccountingDocumentTemp[issue_date]'>".$value->issue_date."</td> 
                                    <td id='AccountingDocumentTemp[doc_number]'>".$value->doc_number."</td>
                                    <td id='AccountingDocumentTemp[amount]'>".$value->amount."</td>    
                                    </tr>";     
                        }
                    }
                    ?>
         </table>
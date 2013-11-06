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
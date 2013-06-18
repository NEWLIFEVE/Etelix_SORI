<?php
Class Utility{
	public static function formatDate($fecha=null)
	{
        if($fecha==NULL)
        {
        	$fecha=date("Y-m-d");
        	return $fecha;
        }
        else
        {   
            $arrayFecha=explode("/", $fecha);
            if(strlen($arrayFecha[0])==1)
            {
                $arrayFecha[0]="0".$arrayFecha[0];
            }
            if(strlen($arrayFecha[1])==1)
            {
                $arrayFecha[1]="0".$arrayFecha[1];
            }
            $fechaFinal=$arrayFecha[2]."-".$arrayFecha[0]."-".$arrayFecha[1];
            return $fechaFinal;
        }
    }
}
?>
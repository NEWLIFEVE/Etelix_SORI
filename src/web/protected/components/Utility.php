<?php
/**
 * @package components
 */
Class Utility
{
    /**
     * @param $fecha date la fecha a formatear
     * @return $fechaFinal string fecha formateada para base de datos
     */
	public static function formatDate($fecha=null)
	{
        if($fecha==NULL)
        {
        	$fechaFinal=date("Y-m-d");
        }
        else
        {   
            if(strpos($fecha,"-"))
            {
                $arrayFecha=explode("-", $fecha);
            }
            elseif(strpos($fecha,"/"))
            {
                $arrayFecha=explode("/", $fecha);
            }
            if(strlen($arrayFecha[0])==1)
            {
                $arrayFecha[0]="0".$arrayFecha[0];
            }
            if(strlen($arrayFecha[1])==1)
            {
                $arrayFecha[1]="0".$arrayFecha[1];
            }
            $fechaFinal=strval($arrayFecha[2]."-".$arrayFecha[0]."-".$arrayFecha[1]);
        }
        return $fechaFinal;
    }

    /**
     *
     */
    public static function notNull($valor)
    {
        if($valor===null)
            $valor="0.00";

        return $valor;
    }
    
    /**
    * Retorna el numero de dias entre una fecha y otra
    */
    public static function dias($fechainicio,$fechafin)
    {
        if(!empty($fechainicio))
        {
            if(strpos($fechainicio,"-"))
            {
                $arrayFechaInicio=explode("-", $fechainicio);
            }
            elseif(strpos($fechainicio,"/"))
            {
                $arrayFechaInicio=explode("/", $fechainicio);
            }
        }
        if(!empty($fechafin))
        {
            if(strpos($fechafin,"-"))
            {
                $arrayFechaFin=explode("-", $fechafin);
            }
            elseif(strpos($fechafin,"/"))
            {
                $arrayFechaFin=explode("/", $fechafin);
            }
        }
        if(!empty($arrayFechaInicio))
        {
            $unixInicio=mktime(0, 0, 0, $arrayFechaInicio[1], $arrayFechaInicio[2], $arrayFechaInicio[0]);
        }
        if(!empty($arrayFechaFin))
        {
            $unixFin=mktime(0, 0, 0, $arrayFechaFin[1], $arrayFechaFin[2], $arrayFechaFin[0]);
        }
        if($unixFin>=$unixInicio)
        {
            $segundos_diferencia=$unixFin-$unixInicio;
            $dias_diferencia=$segundos_diferencia / (60 * 60 * 24);
            return $dias_diferencia;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * retorna NULL si la variable viene vacia...
     * @access public
     * @param type $var Variable de entrada.
     * @return type Null si la vaiable de entrada es vacia, de lo contrario, la variable.
     */
    public static function snull($var)
    {
        if($var==NULL || $var=='')
        {
            return NULL;
        }else{
            return $var;
        }
    }
}
?>
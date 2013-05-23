<?php class Formatter extends CFormatter
{
    /**
     * @var array the format used to format a number with PHP number_format() function.
     * Three elements may be specified: "decimals", "decimalSeparator" and 
     * "thousandSeparator". They correspond to the number of digits after 
     * the decimal point, the character displayed as the decimal point,
     * and the thousands separator character.
     * new: override default value: 2 decimals, a comma (,) before the decimals 
     * and no separator between groups of thousands
    */
    public $numberFormat=array('decimals'=>2, 'decimalSeparator'=>',', 'thousandSeparator'=>'');
 
    /**
     * Formats the value as a number using PHP number_format() function.
     * new: if the given $value is null/empty, return null/empty string
     * @param mixed $value the value to be formatted
     * @return string the formatted result
     * @see numberFormat
     */
    public static function formatDecimal($value) {
        if($value === null) return '0.00';    // new
        if($value === '') return '0.00';        // new
        
        if(strpos($value,'.')==true)
                return substr($value, 0 , strpos($value,".")).",".substr($value, strpos($value,'.')+1 , 2);
        
        if(strpos($value,'.')==false)

                //return $value;
                return $value.",".'00';
        
    }
 
    /*
     * new function unformatNumber():
     * turns the given formatted number (string) into a float
     * @param string $formatted_number A formatted number 
     * (usually formatted with the formatNumber() function)
     * @return float the 'unformatted' number
     */
    public function unformatNumber($formatted_number) {
        if($formatted_number === null) return null;
        if($formatted_number === '') return '';
        if(is_float($formatted_number)) return $formatted_number; // only 'unformat' if parameter is not float already
 
        $value = str_replace($this->numberFormat['thousandSeparator'], '', $formatted_number);
        $value = str_replace($this->numberFormat['decimalSeparator'], '.', $value);
        return (float) $value;
    }
    
    /*public static function formatearFecha($fecha, $tipo=NULL) {

        if($tipo==NULL){
            
            $arrayFecha = explode("/", $fecha);

            if (strlen($arrayFecha[0]) == 1) {
                $arrayFecha[0] = "0" . $arrayFecha[0];
            }
            if (strlen($arrayFecha[1]) == 1) {
                $arrayFecha[1] = "0" . $arrayFecha[1];
            }

            $fechaFinal = $arrayFecha[2] . "-" . $arrayFecha[0] . "-" . $arrayFecha[1];
            return $fechaFinal;
        }
        
        if($tipo=='etelixPeru'){
            
            $arrayFecha = explode(" ", $fecha);
            return $arrayFecha[0];
            
        }
        
    }*/
    public function formatearNombreCabina($nombreCabina,$tipo){
        
        if($tipo=="captura"){
            
            if(stripos($nombreCabina, "-")!==FALSE && stripos($nombreCabina, ".")===FALSE){

                if ($nombreCabina{stripos($nombreCabina, "-")+1} == " "){
                    
                    return substr($nombreCabina, stripos($nombreCabina, "-")+2);
                
                    
                }else{
                    return substr($nombreCabina, stripos($nombreCabina, "-")+1);
                }
                

            }
            
            elseif(stripos($nombreCabina, "-")!==FALSE && stripos($nombreCabina, ".")!==FALSE){

                return "ETELIX - PERU";

            }
            else{
                
                return "Valor Desconocido";
                
            }

        }
        
        elseif($tipo=="brightstar"){
            
            return substr($nombreCabina, 7);
            
        }
    }
    public static function date($fecha=null,$tipo=null)
    {
        if($tipo == 'captura')
        {
            if($fecha)
            {
                $arrayFecha = explode("/", $fecha);
                if(strlen($arrayFecha[0]) == 1)
                {
                    $arrayFecha[0] = "0" . $arrayFecha[0];
                }
                if(strlen($arrayFecha[1]) == 1)
                {
                    $arrayFecha[1] = "0" . $arrayFecha[1];
                }
                $fechaFinal = $arrayFecha[2] . "-" . $arrayFecha[0] . "-" . $arrayFecha[1];
            }
        }
        elseif($tipo == 'etelix')
        {
            if($fecha)
            {
                $fechaFinal = substr($fecha, 0, -9);
            }
        }
        elseif($tipo == 'post')
        {
            if($fecha)
            {
                $arrayFecha = explode("/", $fecha);
                if(strlen($arrayFecha[0]) == 1)
                {
                    $arrayFecha[0] = "0" . $arrayFecha[0];
                }
                if(strlen($arrayFecha[1]) == 1)
                {
                    $arrayFecha[1] = "0" . $arrayFecha[1];
                }
                $fechaFinal = $arrayFecha[2] . "-" . $arrayFecha[1] . "-" . $arrayFecha[0];
            }
        }
        else
        {
            $fechaFinal = $fecha;
        }
        return $fechaFinal;
    }
}
?>
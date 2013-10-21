<?php
  function traeFechaValida($EmailfechaRecepcion,$dia)
        {    
            switch ($dia) {
                
                case 1:
                      return date('Y-m-d', strtotime('+1 day', strtotime ( $EmailfechaRecepcion ))) ;
                      break;
                case 2:
                      return date('Y-m-d', strtotime('+6 day', strtotime ( $EmailfechaRecepcion ))) ;
                      break;
                case 3:
                      return date('Y-m-d', strtotime('+5 day', strtotime ( $EmailfechaRecepcion ))) ;
                      break;
                case 4:
                      return date('Y-m-d', strtotime('+4 day', strtotime ( $EmailfechaRecepcion ))) ;
                      break;
                case 5:
                      return date('Y-m-d', strtotime('+3 day', strtotime ( $EmailfechaRecepcion ))) ;
                      break;
                case 6:
                      return date('Y-m-d', strtotime('+2 day', strtotime ( $EmailfechaRecepcion ))) ;
                      break;
                case 7:
                      return date('Y-m-d', strtotime('+1 day', strtotime ( $EmailfechaRecepcion ))) ;
                      break;
             }                                                             
        }
        $EmailfechaRecepcion='2013-10-14';
        $EmailHoraRecepcion='17:59';
        echo $EmailfechaRecepcion.'--'.$EmailHoraRecepcion;
        echo '</br>';
        $fecha = strtotime($EmailfechaRecepcion);
            $dia = date("N", $fecha);
                if ($dia == 1 || $dia == 2) {
                    if ($EmailHoraRecepcion >= '08:00' && $EmailHoraRecepcion <= '17:00') {
                        echo $EmailfechaRecepcion;
                        echo '--';
                        echo $EmailHoraRecepcion;
                      
                    } else {
                        if($EmailHoraRecepcion < '08:00' ){
                            echo $EmailfechaRecepcion;
                        }else
                            {
                            echo traeFechaValida($EmailfechaRecepcion, $dia);
                        }
                        echo '--';
                        echo '08:00';
                    }
                } else {
                    echo traeFechaValida($EmailfechaRecepcion, $dia);
                    echo '--';
                    echo '08:00';
                }
            
?>

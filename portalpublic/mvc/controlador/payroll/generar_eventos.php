<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$fecha_pago = '2016-08-26';
    $tipo_empleado = 'Administrative';
    $cant_dias_1 = 25;
    $cant_dias_2 = 14;
    $i=0;
    $sql = "INSERT INTO tbl_event VALUES ";
    while($i<=100){        
    
    if($i > 0){
        if($tipo_empleado == 'Administrative'){
            $tipo_empleado = 'Therapist';
            $cant_dias_1 = 32;
            $cant_dias_2 = 21;
        }else{
            $tipo_empleado = 'Administrative';
            $cant_dias_1 = 25;
            $cant_dias_2 = 14;
        }    
        $fecha_pago = strtotime ( '+7 day' , strtotime ( $fecha_pago ) ) ;
        $fecha_pago = date ('Y-m-d', $fecha_pago);
    }
    //echo '<br>';

    $fe_inicio = strtotime ( '-'.$cant_dias_1.' day' , strtotime ( $fecha_pago ) ) ;
    $fe_inicio = date ('Y-m-d', $fe_inicio);

    //echo '<br>';
    $fe_fin = strtotime ( '-'.$cant_dias_2.' day' , strtotime ( $fecha_pago ) ) ;
    $fe_fin = date ('Y-m-d', $fe_fin);    
    //echo '<br>';
    //echo $tipo_empleado;
    
    $sql .= "(".($i+1).",'".$fecha_pago."','".$fecha_pago."','Pay ".$tipo_empleado."s \\\\n ".$fe_inicio." to \\\\n ".$fe_fin."'),";
    $sql .= '<br>';
    $i++;
    if($i == 150) break;
    }
    echo $sql;

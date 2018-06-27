<?php

class CalculosFecha{

    function lunes($cantidad,$type){
        
        $j=1;        
        for($i=0;$i<$cantidad;$i++){
            
            if($type==1){
                if($j==1){
                    $fecha=date('Y-m-d');
                    $date = new DateTime($fecha);  
                    $date->modify('Next Monday');
                    $arreglo[$j]=$date->format('Y-m-d');                   
                }else {
                    
                    $date->modify('+7 day');
                    $arreglo[$j]=$date->format('Y-m-d');
                }
            }
            if($type==2){
                if($j==1){
                    $fecha=date('Y-m-d');
                    $date = new DateTime($fecha);  
                    $date->modify('Next Monday');
                    $date->modify('+7 day');
                    $arreglo[$j]=$date->format('Y-m-d');                   
                }else {
                    
                    $date->modify('+14 day');
                    $arreglo[$j]=$date->format('Y-m-d');
                }
            }
            $j++;
        }
       
        return $arreglo;
        

    }
    function martes($cantidad,$type){

        $j=1;        
        for($i=0;$i<$cantidad;$i++){
            
            if($type==1){
                if($j==1){
                    $fecha=date('Y-m-d');
                    $date = new DateTime($fecha);  
                    $date->modify('Next Tuesday');
                    $arreglo[$j]=$date->format('Y-m-d');                   
                }else {
                    
                    $date->modify('+7 day');
                    $arreglo[$j]=$date->format('Y-m-d');
                }
            }
            if($type==2){
                if($j==1){
                    $fecha=date('Y-m-d');
                    $date = new DateTime($fecha);  
                    $date->modify('Next Tuesday');
                    $date->modify('+7 day');
                    $arreglo[$j]=$date->format('Y-m-d');                   
                }else {
                    
                    $date->modify('+14 day');
                    $arreglo[$j]=$date->format('Y-m-d');
                }
            }
            $j++;
        }
       
        return $arreglo;

    }
    function miercoles($cantidad,$type){

        $j=1;        
        for($i=0;$i<$cantidad;$i++){
            
            if($type==1){
                if($j==1){
                    $fecha=date('Y-m-d');
                    $date = new DateTime($fecha);  
                    $date->modify('Next Wednesday');
                    $arreglo[$j]=$date->format('Y-m-d');                   
                }else {
                    
                    $date->modify('+7 day');
                    $arreglo[$j]=$date->format('Y-m-d');
                }
            }
            if($type==2){
                if($j==1){
                    $fecha=date('Y-m-d');
                    $date = new DateTime($fecha);  
                    $date->modify('Next Wednesday');
                    $date->modify('+7 day');
                    $arreglo[$j]=$date->format('Y-m-d');                   
                }else {
                    
                    $date->modify('+14 day');
                    $arreglo[$j]=$date->format('Y-m-d');
                }
            }
            $j++;
        }
       
        return $arreglo;

    }
    function jueves($cantidad,$type){

        $j=1;        
        for($i=0;$i<$cantidad;$i++){
            
            if($type==1){
                if($j==1){
                    $fecha=date('Y-m-d');
                    $date = new DateTime($fecha);  
                    $date->modify('Next Thursday');
                    $arreglo[$j]=$date->format('Y-m-d');                   
                }else {
                    
                    $date->modify('+7 day');
                    $arreglo[$j]=$date->format('Y-m-d');
                }
            }
            if($type==2){
                if($j==1){
                    $fecha=date('Y-m-d');
                    $date = new DateTime($fecha);  
                    $date->modify('Next Thursday');
                    $date->modify('+7 day');
                    $arreglo[$j]=$date->format('Y-m-d');                   
                }else {
                    
                    $date->modify('+14 day');
                    $arreglo[$j]=$date->format('Y-m-d');
                }
            }
            $j++;
        }
       
        return $arreglo;

    }
    function viernes($cantidad,$type){

        $j=1;        
        for($i=0;$i<$cantidad;$i++){
            
            if($type==1){
                if($j==1){
                    $fecha=date('Y-m-d');
                    $date = new DateTime($fecha);  
                    $date->modify('Next Friday');
                    $arreglo[$j]=$date->format('Y-m-d');                   
                }else {
                    
                    $date->modify('+7 day');
                    $arreglo[$j]=$date->format('Y-m-d');
                }
            }
            if($type==2){
                if($j==1){
                    $fecha=date('Y-m-d');
                    $date = new DateTime($fecha);  
                    $date->modify('Next Friday');
                    $date->modify('+7 day');
                    $arreglo[$j]=$date->format('Y-m-d');                   
                }else {
                    
                    $date->modify('+14 day');
                    $arreglo[$j]=$date->format('Y-m-d');
                }
            }
            $j++;
        }
       
        return $arreglo;

    }
    function sabado($cantidad,$type){

        $j=1;        
        for($i=0;$i<$cantidad;$i++){
            
            if($type==1){
                if($j==1){
                    $fecha=date('Y-m-d');
                    $date = new DateTime($fecha);  
                    $date->modify('Next Saturday');
                    $arreglo[$j]=$date->format('Y-m-d');                   
                }else {
                    
                    $date->modify('+7 day');
                    $arreglo[$j]=$date->format('Y-m-d');
                }
            }
            if($type==2){
                if($j==1){
                    $fecha=date('Y-m-d');
                    $date = new DateTime($fecha);  
                    $date->modify('Next Saturday');
                    $date->modify('+7 day');
                    $arreglo[$j]=$date->format('Y-m-d');                   
                }else {
                    
                    $date->modify('+14 day');
                    $arreglo[$j]=$date->format('Y-m-d');
                }
            }
            $j++;
        }
       
        return $arreglo;

    }
    function domingo($cantidad,$type){

        $j=1;        
        for($i=0;$i<$cantidad;$i++){
            
            if($type==1){
                if($j==1){
                    $fecha=date('Y-m-d');
                    $date = new DateTime($fecha);  
                    $date->modify('Next Sunday');
                    $arreglo[$j]=$date->format('Y-m-d');                   
                }else {
                    
                    $date->modify('+7 day');
                    $arreglo[$j]=$date->format('Y-m-d');
                }
            }
            if($type==2){
                if($j==1){
                    $fecha=date('Y-m-d');
                    $date = new DateTime($fecha);  
                    $date->modify('Next Sunday');
                    $date->modify('+7 day');
                    $arreglo[$j]=$date->format('Y-m-d');                   
                }else {
                    
                    $date->modify('+14 day');
                    $arreglo[$j]=$date->format('Y-m-d');
                }
            }
            $j++;
        }
       
        return $arreglo;

    }
    function Hora($hora){
        
        $hour=explode(' ',$hora);        
        $type=$hour[1];               
        if($type=='pm'){            
            $detail_hour=  explode(':', $hour[0]);
            switch ($detail_hour[0]){
                
                case '01':
                    $hour_new='13';
                break;
                
                case '02':
                    $hour_new='14';
                break;
            
                case '03':
                    $hour_new='15';
                break;
            
                case '04':
                    $hour_new='16';
                break;
            
                case '05':
                    $hour_new='17';
                break;
            
                case '06':
                    $hour_new='18';
                break;
            
                case '07':
                    $hour_new='19';
                break;
                
                case '08':
                    $hour_new='20';
                break;
            
                case '09':
                    $hour_new='21';
                break;
            
                case '10':
                    $hour_new='22';
                break;
            
                case '11':
                    $hour_new='23';
                break;            
                
            }
            $hour_seteada=$hour_new.':'.$detail_hour[1].':00';
        }else{
            $detail_hour=  explode(':', $hour[0]);
            $hour_seteada=$detail_hour[0].':'.$detail_hour[1].':00';
        }
        return $hour_seteada;
        
    }   
   
}
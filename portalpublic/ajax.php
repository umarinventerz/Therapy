<?php

header('Content-Type: application/json');

// Set up the ORM library
//require_once('setup.php');
require_once 'conex.php';

//if (isset($_GET['start']) AND isset($_GET['end'])) {
	
  
   
    $start = $_GET['start'];
    $end = $_GET['end'];
        
     

   $start = '2016-01-01';
    $end = '2016-12-31';
    $conexion = conectar();
  $sql  = "
SELECT 
count(MONTHNAME(STR_TO_DATE(tbl_treatments.campo_1,'%m/%d/%Y'))) as total,
MONTHNAME(STR_TO_DATE(tbl_treatments.campo_1,'%m/%d/%Y')) as month

from tbl_treatments

where YEAR(STR_TO_DATE(tbl_treatments.campo_1,'%m/%d/%Y')) = '2016' and 
STR_TO_DATE(tbl_treatments.campo_1,'%m/%d/%Y') >= '".$start."' AND STR_TO_DATE(tbl_treatments.campo_1,'%m/%d/%Y') <='".$end."'

group by  month
order by STR_TO_DATE(tbl_treatments.campo_1,'%m/%d/%Y') asc 

        ";




        $resultado = ejecutar($sql,$conexion);

        $reporte = array();

        $i = 0;      
        while($datos = mysqli_fetch_assoc($resultado)) {            
            $reporte[$i]['label'] = $datos['month'];
            $reporte[$i]['value'] = $datos['total'];
            $i++;
        }	
        /*echo '<pre>';
        print_r($reporte);*/
	echo json_encode($reporte);
//}

<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();
$diciplina=$_POST['discipline'];
$explode=$diciplina;
if($diciplina !=null){
    $string=implode(',',$diciplina);
    
    if(count($explode)==1 && $explode[0]=='office'){
        $sql="SELECT id FROM employee where discipline_id is null";
    }
    if(count($explode)==1 && $explode[0]==null){
        $sql="SELECT id FROM employee";
    }
    if(count($explode)==1 && $explode[0]!='office'){
        $sql="SELECT id FROM employee where discipline_id IN (".$string.")";
    }
    if(count($explode)>1 && $explode[0]=='office'){
       $diciplinas=str_replace("office", "0", $string);
       $sql="SELECT id FROM employee where discipline_id IN (".$diciplinas.") OR discipline_id is null";
    }
    if(count($explode)>1 && $explode[0]!='office'){
        $sql="SELECT id FROM employee where discipline_id IN (".$string.")";
    }
    
    $json = ejecutar($sql,$conexion);

    while ($row=mysqli_fetch_array($json)) {
        $arreglo[] = $row['id'];                    

    }

    
       echo implode(',', $arreglo); 
    
}

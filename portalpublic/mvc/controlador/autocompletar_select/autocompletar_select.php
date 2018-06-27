<?php


// Sample use
// Just pass your array or string and the UTF8 encode will be fixed


         session_start();
require_once '../../../conex.php';

if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="home.php";</script>';
	}
}


        $sql = $_GET['sql'];
        $id_campo = $_GET['id_campo'];
        $texto_campo = $_GET['texto_campo'];

        $conexion = conectar();									

        
                $resultado = ejecutar($sql,$conexion);
               
                $i=0;
                while ($row=mysqli_fetch_array($resultado)) 
                {	
                    
                    $reporte[$i][$id_campo] =mb_convert_encoding($row[$id_campo], 'UTF-8', 'UTF-8') ;
                    $reporte[$i][$texto_campo] = mb_convert_encoding($row[$texto_campo], 'UTF-8', 'UTF-8') ;
                    
                $i++;        
                }                 
                     
                
                if(!isset($reporte)){
                     $reporte[0][$id_campo] = '';
                     $reporte[0][$texto_campo] = '';
                }



//$reporte1=mb_convert_encoding($reporte, 'UTF-8', 'UTF-8');



                $json=json_encode($reporte);

 if ($json)

   //  return response->json(["message" => "Model status successfully updated!", "data" => $json], 200);
    echo $json;
 else
    echo json_last_error_msg();

                ?>
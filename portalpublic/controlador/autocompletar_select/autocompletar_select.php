<?php  
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
                    
                    $reporte[$i][$id_campo] = $row[$id_campo];
                    $reporte[$i][$texto_campo] = $row[$texto_campo];                            
                    
                $i++;        
                }
                
                echo json_encode($reporte);                    
                
                ?>
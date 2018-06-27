<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="../../home/home.php";</script>';
	}
}

$conexion = conectar();
$sql  = "SELECT * FROM tbl_departaments ";
$resultado = ejecutar($sql,$conexion);

$reporte = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($resultado)) {            
    $reporte[$i] = $datos;
    $i++;
}


?>

<script>
                $(document).ready(function() {
                        $('#departaments').DataTable({

                                dom: 'Bfrtip',
                                buttons: [
                                        'copyHtml5',
                                        'excelHtml5',
                                        'csvHtml5',
                                        'pdfHtml5'
                                ]
                        } );
                } );
</script>
<br>
        <div class="row">
            <div class="col-lg-12 text-center"><b><h4>DEPARTAMENTS</h4></b></div>    
        </div>

<div class="col-lg-12">

    <table id="departaments" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                            <th>ID DEPARTAMENTS</th>
                            <th>DEPARTAMENTS</th>
                            <th style="width: 50px">UPDATE</th>
                </tr>
            </thead>


            <tbody>
            <?php		
            $i=0;						
            while (isset($reporte[$i])){ 				
           
                echo '<tr>';
                echo '<td>'.$reporte[$i]['id_departaments'].'</td>';
                echo '<td><div id="div_departaments'.$i.'">'.$reporte[$i]['departaments'].'</div></td>';
                echo '<td align="center"><div id="div_image_dep'.$i.'"><a onclick="cambiar_campo('.$reporte[$i]['id_departaments'].',\'departaments\',\'id_departaments\',\'div_departaments'.$i.'\',\'div_image_dep'.$i.'\',\''.$reporte[$i]['departaments'].'\',\'../../controlador/departaments/modificar_departaments.php\',\'tbl_departaments\',\'departaments'.$i.'\');"><img src="../../../images/save.png" style="width:20px"></a></div></td>';
                echo '</tr>';
                $i++;		
            }			
             ?>				
            <tbody/>
            </table> 

</div>
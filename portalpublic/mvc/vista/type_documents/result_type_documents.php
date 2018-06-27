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
$sql  = "SELECT * FROM tbl_doc_type_documents td "
        . " WHERE id_type_documents NOT IN ( SELECT distinct(id_type_documents) FROM tbl_doc_type_persons_documents )";
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
                        $('#type_documents').DataTable({

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
            <div class="col-lg-12 text-center"><b><h4>TYPE DOCUMENTS</h4></b></div>    
        </div>

<div class="col-lg-12">

    <table id="type_documents" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                            <th >ID TYPE DOCUMENTS</th>
                            <th>TYPE DOCUMENTS</th>
                            <th style="width: 50px">UPDATE</th>

                </tr>
            </thead>


            <tbody>
            <?php		
            $i=0;						
            while (isset($reporte[$i])){ 				                         
            
                   
                echo '<tr>';
                echo '<td>'.$reporte[$i]['id_type_documents'].'</td>';
                echo '<td><div id="div_type_documents'.$i.'">'.$reporte[$i]['type_documents'].'</div></td>';
                echo '<td align="center"><div id="div_image'.$i.'"><a onclick="cambiar_campo('.$reporte[$i]['id_type_documents'].',\'type_documents\',\'id_type_documents\',\'div_type_documents'.$i.'\',\'div_image'.$i.'\',\''.$reporte[$i]['type_documents'].'\',\'../../controlador/type_documents/modificar_type_documents.php\',\'tbl_doc_type_documents\',\'type_documents'.$i.'\');"><img src="../../../images/save.png" style="width:20px"></a></div></td>';
                echo '</tr>';
                $i++;		
            }			
             ?>				
            <tbody/>
            </table> 

</div>
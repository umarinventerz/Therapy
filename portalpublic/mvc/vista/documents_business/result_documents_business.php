<?php

session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}else{
	if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
		echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
		echo '<script>window.location="../home/home.php";</script>';
	}
}

$conexion = conectar();



$sql  = "SELECT * FROM tbl_documents_business td "
        . " LEFT JOIN tbl_departaments using(id_departaments) "
        . " LEFT JOIN tbl_doc_type_documents using(id_type_documents) "
        . " LEFT join tbl_user_system_departaments_company tdc on td.id_departaments=tdc.id_departaments_company
        WHERE tdc.id_user_system='".$_SESSION['user_id']."'    ";




$resultado = ejecutar($sql,$conexion);



$reporte = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($resultado)) {            
    $reporte[$i] = $datos;
    $i++;
}


?>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
<script type="text/javascript" language="javascript">

function eliminar_registro_business(id_document_business,ruta_archivo){
        
swal({
        title: "Confirmation",
        text: "You want to unregister n° "+id_document_business+"?",
        type: "warning",
        showCancelButton: true,   
        confirmButtonColor: "#3085d6",   
        cancelButtonColor: "#d33",   
        confirmButtonText: "Delete",   
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {                                 
                
                   var campos_formulario = '&id_document_business='+id_document_business+'&ruta_archivo='+ruta_archivo;
                    
                        $.post(
                                "../../controlador/documents_business/eliminar_documents_business.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                    
                                    if(resultado_controlador.resultado == 'eliminado') {
                                    
                                        swal({
                                          title: "<h4><b>DOCUMENT DELETED</h4>",          
                                          type: "success",              
                                          showCancelButton: false,              
                                          closeOnConfirm: true,
                                          showLoaderOnConfirm: false,
                                        }); 
                                        
                                        $("#resultado_documents_business").load("result_documents_business.php");                                        
                                        
                                 
                                    }
                                    
                                    
                                },
                                "json" 
                                ); 
            
          }
        });    
    
}




                $(document).ready(function() {
                        $('#d_business').DataTable({
                            
                                dom: 'Bfrtip',
                                "scrollX": true,
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
       




     


            <div class="col-lg-12 text-center"><b><h4>DOCUMENTS UPLOADED</h4></b></div>    
        

 <div class="col-lg-12">

    <table id="d_business"  class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                            <th>ID</th>
                            <th class="col-lg-2">DEPARTAMENT</th>
                            <th class="col-lg-2">TYPE DOCUMENT</th>
                            <th class="col-lg-2">VERSION DOCUMENT</th>
                            <th class="col-lg-2">DESCRIPTION</th>
                            <th class="col-lg-2">NAME DOCUMENT</th>
                            <th class="col-lg-2">DATE</th>
                           
                           <?php  if($_SESSION['user_type'] > '8'  ){ ?>
                            <th >ACTION</th>
                             <?php } ?>
                           

                </tr>
            </thead>


            <tbody>
            <?php		
            $i=0;						
            while (isset($reporte[$i])){ 				
                
            $sql_consulta  = "SELECT ruta FROM tbl_documents_business WHERE id_documents_business = ".$reporte[$i]['id_documents_business'];
                    
            $resultado_consulta = ejecutar($sql_consulta,$conexion);

            $reporte_ruta = array();

            $u = 0;      
            while($dato_ruta = mysqli_fetch_assoc($resultado_consulta)) {            
                $reporte_ruta[$u] = $dato_ruta;                              
                $u++;
            }                                          
            
            $ruta_archivo = $reporte_ruta[0]['ruta'];
            
            $nombre_archivo_array = explode('/',$reporte_ruta[0]['ruta']);
            $nombre_archivo = end($nombre_archivo_array);
            
            
            
                   
                echo '<tr>';
                echo '<td>'.$reporte[$i]['id_documents_business'].'</td>';
                echo '<td>'.$reporte[$i]['departaments'].'</td>';
                echo '<td>'.$reporte[$i]['type_documents'].'</td>';
                echo '<td>'.$reporte[$i]['version_document'].'</td>';  
                echo '<td>'.$reporte[$i]['description'].'</td>';  
                //echo '<td>'.$nombre_archivo.'</td>';
                echo '<td><a onclick="window.open(\'../../../'.$ruta_archivo.'\',\'\',\'width=900px,height=700px,noresize\');">'.$nombre_archivo.'</a></td>';
               echo '<td>'.$reporte[$i]['date'].'</td>';
               if($_SESSION['user_type'] > '8' ){
                echo '<td align="center">';
            ?>
            <a onclick="eliminar_registro_business(<?php echo $reporte[$i]['id_documents_business']; ?>,'<?php echo $ruta_archivo; ?>')" style="cursor: pointer"><img style="width:30px" src="../../../images/papelera.png"></a>
            <?php
                echo '</td>';
            }
                echo '</tr>';
                $i++;		
            }			
             ?>				
            <tbody/>
            </table> 

</div>

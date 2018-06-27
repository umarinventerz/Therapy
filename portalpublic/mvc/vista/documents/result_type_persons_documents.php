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
        . " JOIN tbl_doc_type_persons_documents tpd ON tpd.id_type_documents = td.id_type_documents "
        . " LEFT JOIN tbl_doc_type_persons tp ON tp.id_type_persons = tpd.id_type_persons "; //die();
$resultado = ejecutar($sql,$conexion);

$reporte = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($resultado)) {            
    $reporte[$i] = $datos;
    $i++;
}
?>
<br><br>
        <div class="row">
            <div class="col-lg-12 text-center"><b><h4>TYPE PERSONS DOCUMENTS</h4></b></div>    
        </div>
<script>
                $(document).ready(function() {
                        $('#type_persons_documents').DataTable({
                            dom: 'Bfrtip',
                            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                            buttons: [
                                'pageLength',
                                'copyHtml5',
                                'excelHtml5',
                                'csvHtml5',
                                'pdfHtml5'
                            ],
                            "pageLength": 25
                        } );
                } );
</script>
<div class="col-lg-12">

    <table id="type_persons_documents" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                            <th>ID</th>
                            <th>TYPE DOCUMENT</th>
                            <th>TYPE PERSON</th>
                            <th>MODIFY</th>

                </tr>
            </thead>


            <tbody>
            <?php		
            $i=0;						
            while (isset($reporte[$i])){ 				
                echo '<tr>';
                echo '<td>'.$reporte[$i]['id_type_documents'].'</td>';
                echo '<td>'.$reporte[$i]['type_documents'].'</td>';
                echo '<td>'.$reporte[$i]['type_persons'].'</td>';
                echo '<td align="center">';
            ?>
                <!--'<form  id=\'form_type_documents\' name=\'form_type_documents\'><table align=\'center\'><tr><td align=\'left\'>Type Person: <select class=\'populate placeholder\' id=\'tipo_persona_agregar\' name=\'tipo_persona_agregar\'><option value=\'\'>--- SELECT ---</option></select></td></tr><tr><td>&nbsp;</td></tr><tr><td align=\'left\'>Type Document: <input class=\'form-control\' id=\'valor_tipo_documento\' name=\'valor_tipo_documento\'><input id=\'id_type_documents_persons\' name=\'id_type_documents_persons\' type=\'hidden\' value=\'<?php echo $reporte[$i]['id_type_documents_persons'];?>\'></td></tr></table></form>'-->
            <a onclick="agregar_nuevo_registro('form_type_documents','<form  id=\'form_type_documents\' name=\'form_type_documents\'><table align=\'center\'><tr><td align=\'left\'>Type Person: <select class=\'populate placeholder\' id=\'tipo_persona_agregar\' name=\'tipo_persona_agregar\'><option value=\'\'>--- SELECT ---</option></select></td></tr><tr><td>&nbsp;</td></tr><tr><td align=\'left\'>Type Document: <input class=\'form-control\' id=\'valor_tipo_documento\' name=\'valor_tipo_documento\'><input id=\'id_type_documents_persons\' name=\'id_type_documents_persons\' type=\'hidden\' value=\'<?php echo $reporte[$i]['id_type_documents_persons'];?>\'></td></tr></table></form>','../../controlador/type_persons_documents/insertar_type_persons_documents.php'); autocompletar_select('id_type_persons','type_persons','tipo_persona_agregar','Select id_type_persons, type_persons from tbl_doc_type_persons order by type_persons',<?php echo $reporte[$i]['id_type_persons']; ?>);setTimeout( function (){ LoadSelect2ScriptExt(function(){$('#tipo_persona_agregar').select2();}); },300);$('#valor_tipo_documento').val('<?php echo $reporte[$i]['type_documents']; ?>')" style="cursor: pointer"><img style="height:35px;" src="../../../images/save.png"></a>
            <?php
                echo '</td>';
                echo '</tr>';
                $i++;		
            }			
             ?>				
            <tbody/>
            </table> 

</div>
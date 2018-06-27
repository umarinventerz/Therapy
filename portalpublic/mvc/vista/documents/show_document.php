<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=2.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>.: THERAPY AID :.</title>
    
    <link href="../../../css/bootstrap.min.css" rel="stylesheet">    
    <link href="../../../css/portfolio-item.css" rel="stylesheet">
<?php

$where = ' WHERE true ';
$tipo_documento = $_GET['tipo_documento'];
if(isset($tipo_documento) && $tipo_documento!= ''){
    $where .= " AND id_type_document = '".$tipo_documento."'";
}

$tipo_persona = $_GET['tipo_persona'];
if(isset($tipo_persona) && $tipo_persona!= ''){   
    $where .= " AND id_type_person = ".$tipo_persona;
}


$Pat_id = $_GET['Pat_id'];
if(isset($Pat_id) && $Pat_id != ''){    
    $where .= " AND id_person = '".$Pat_id."'";
}

$id_employee = $_GET['id_employee'];
if(isset($id_employee) && $id_employee!= ''){
    $where .= " AND id_person = '".$id_employee."'";
}

$id_insurance = $_GET['id_insurance'];
if(isset($id_insurance) && $id_insurance!= ''){
    $where .= " AND id_person = '".$id_insurance."'";
}

$Phy_id = $_GET['Phy_id'];
if(isset($Phy_id) && $Phy_id!= ''){
    $where .= " AND id_person = '".$Phy_id."'";
}

$id_referral = $_GET['id_referral'];
if(isset($id_referral) && $id_referral!= ''){
    $where .= " AND id_person = '".$id_referral."'";
}

$id_persona_contacto= $_GET['id_persona_contacto'];
if(isset($id_persona_contacto) && $id_persona_contacto!= ''){
    $where .= " AND id_person = '".$id_persona_contacto."'";
}

$input_date_start = $_GET['input_date_start'];
$input_date_end = $_GET['input_date_end'];

if(isset($input_date_start) && isset($input_date_end) && ($input_date_start != '' && $input_date_end != '')){
    $where .= " AND date BETWEEN str_to_date('".$input_date_start."','%m/%d/%Y') AND str_to_date('".$input_date_end."','%m/%d/%Y')"; 
}


$conexion = conectar();
    $sql  = "SELECT *,trim(route_document),trim(type_documents), trim(type_persons) FROM tbl_documents ct LEFT JOIN tbl_doc_type_persons tp ON tp.id_type_persons = ct.id_type_person "
            . "LEFT JOIN tbl_doc_type_documents td ON td.id_type_documents = ct.id_type_document "
            . $where.";";
    
$resultado = ejecutar($sql,$conexion);

        $reporte = array();
        
        $i = 0;      
        while($datos = mysqli_fetch_assoc($resultado)) {            
            $reporte[$i] = $datos;
            $i++;
        }
        
?>
    <!-- Style Bootstrap-->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
    <script src="../../../js/funciones.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../../css/resources/shCore.css">	
    <!-- End Style-->
    <script type="text/javascript" language="javascript" class="init">
	
        function eliminar_registro_document(id_document,ruta_archivo){
        
        swal({
                title: "Confirmation",
                text: "You want to unregister n° "+id_document+"?",
                type: "warning",
                showCancelButton: true,   
                confirmButtonColor: "#3085d6",   
                cancelButtonColor: "#d33",   
                confirmButtonText: "Delete",   
                closeOnConfirm: false,
                closeOnCancel: false
                        }).then(function(isConfirm) {
                          if (isConfirm === true) {                                 

                           var campos_formulario = '&id_document='+id_document+'&ruta_archivo='+ruta_archivo;

                                $.post(
                                        "../../controlador/documents/eliminar_documents.php",
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
                                                
                                                var datos_formulario = $('#form_find_date').serialize();
                                                $("#resultado").load("show_document.php?"+datos_formulario);                                        


                                            }


                                        },
                                        "json" 
                                        ); 

                  }
                });    

        }
        
	$(document).ready(function() {
		$('#example').DataTable({

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
</head>
<br>
<br>
 <div class="col-md-12">

		
                <table class="table" id="example" width="100%" >
			
        <?php                         
        
            $datos_result = '';
            $datos_result .= '
                    <thead>
                        <tr>
                            <th>TYPE DOCUMENT</th>
                            <th>TYPE PERSON</th>
                            <th>NAME</th>
                            <th>TYPE DOCUMENT</th>
                            <th>DATE</th> 
                            <th>ACTION</th>
                        </tr>
                    </thead>

            <tbody>';
				
            $i=0;		
            $sum_total_pay_treatment = 0;
            $total_dur = 0;
            while (isset($reporte[$i])){ 

                    $datos_result .= '<tr>';				
                    $datos_result .= '<td>'.$reporte[$i]['type_documents'].'</td>';
                    $datos_result .= '<td>'.$reporte[$i]['type_persons'].'</td>';
                    if($reporte[$i]['id_type_person'] == '1'){
                        $sql_person  = "SELECT trim(concat(First_name,' ',Last_name)) as name FROM patients p WHERE Pat_id = '".$reporte[$i]['id_person']."';";
                    }else{
                        if($reporte[$i]['id_type_person'] == '2'){
                        $sql_person  = "SELECT trim(concat(first_name,' ',last_name)) as name FROM employee WHERE id = '".$reporte[$i]['id_person']."';";
                    }
                    else{
                        if($reporte[$i]['id_type_person'] == '3'){
                            $sql_person  = "SELECT trim(insurance) as name FROM seguros WHERE ID = '".$reporte[$i]['id_person']."';";
                        }
                        else{
                             if($reporte[$i]['id_type_person'] == '4'){
                                $sql_person  = "SELECT trim(Name) as name FROM physician WHERE Phy_id = '".$reporte[$i]['id_person']."';";
                             }else{
                                 
                                 if($reporte[$i]['id_type_person'] == '5'){
                                    $sql_person  = "Select id_referral, First_name from tbl_referral WHERE id_referral = '".$reporte[$i]['id_person']."';";
                                 }else{
                                     
                                     if($reporte[$i]['id_type_person'] == '6'){
                                        $sql_person  = "Select id_persona_contacto, persona_contacto from tbl_contacto_persona WHERE id_persona_contacto = '".$reporte[$i]['id_person']."';";
                                     }
                                 }
                             }

                    }
                    }
                    }

                    $resultado_person = ejecutar($sql_person,$conexion);
                    $reporte_datos = array();        
                    $j = 0;      
                    while($datos = mysqli_fetch_assoc($resultado_person)) {            
                        $reporte_datos[$j] = $datos;
                        $j++;
                    }
                    $datos_result .= '<td>'.$reporte_datos[0]['name'].'</td>';                    
$datos_result .= '<td><a href="../../../'.$reporte[$i]['route_document'].'" target="_blank">'.$reporte[$i]['type_documents'].'</a></td>';          
                      
                    $datos_result .= '<td>'.$reporte[$i]['date'].'</td>';                    
                    $datos_result .= '<td align="center">';
                    $datos_result .= '<a onclick="eliminar_registro_document(\''.$reporte[$i]['id_document'].'\',\''.$reporte[$i]['route_document'].'\')" style="cursor: pointer"><img style="width:30px" src="../../../images/papelera.png"></a>';
                    
                    $datos_result .= '</td>';
                    $datos_result .= '</tr>';
                    $sum_total_pay_treatment += $total_pay_treatment;
                    $i++;		
            }			
            
            $datos_result .= '</tbody>';                         
                        
                        echo $datos_result;                                                
                        
                        ?>
			</table>
		 
            </div>


<?php

        $encabezado_datos_result = '<br><br><table><tr><td><img src="images/LOGO_1.png"></td></tr><tr><td align="center"><font size="1" color="#BDBDBD"><b>THERAPIES '.$date_start.' - '.$date_end.'</b></td></tr></table><br><br><table border="1" width="100%" bordercolor="#A4A4A4" cellspacing="0" cellspacing="0">';
        $inferior_datos_result = '</table>'; 

        $imprimir_datos_result = $encabezado_datos_result.$datos_result.$inferior_datos_result;
?>


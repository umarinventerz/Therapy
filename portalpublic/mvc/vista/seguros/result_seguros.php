<?php
error_reporting(0);
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
 
                        
    if(isset($_GET["name"]) && $_GET["name"] != null){ $name = " and name = '".$_GET["name"]."' "; } else { $name = null; }
    if(isset($_GET["address"]) && $_GET["address"] != null){ $address = " and address = '".$_GET["address"]."' "; } else { $address = null; }
    if(isset($_GET["city"]) && $_GET["city"] != null){ $city = " and city = '".$_GET["city"]."' "; } else { $city = null; }
    if(isset($_GET["state"]) && $_GET["state"] != null){ $state = " and state = '".$_GET["state"]."' "; } else { $state = null; }
    if(isset($_GET["zip"]) && $_GET["zip"] != null){ $zip = " and zip = '".$_GET["zip"]."' "; } else { $zip = null; }
    if(isset($_GET["phone"]) && $_GET["phone"] != null){ $phone = " and phone = '".$_GET["phone"]."' "; } else { $phone = null; }
    if(isset($_GET["fax"]) && $_GET["fax"] != null){ $fax = " and fax = '".$_GET["fax"]."' "; } else { $fax = null; }
//    if(isset($_GET["id_reporting_system"]) && $_GET["id_reporting_system"] != null){ $id_reporting_system = " and id_reporting_system = '".$_GET["id_reporting_system"]."' "; } else { $id_reporting_system = null; }
    if(isset($_GET["provider"]) && $_GET["provider"] != null){ $provider = " and provider = '".$_GET["provider"]."' "; } else { $provider = null; }
    if(isset($_GET["id_type_provider"]) && $_GET["id_type_provider"] != null){ $id_type_provider = " and id_type_provider = '".$_GET["id_type_provider"]; } else { $id_type_provider = null; }
    if(isset($_GET["id_claim_ind"]) && $_GET["id_claim_ind"] != null){ $id_claim_ind = " and id_claim_ind = '".$_GET["id_claim_ind"]."' "; } else { $id_claim_ind = null; }
    if(isset($_GET["submitter_id"]) && $_GET["submitter_id"] != null){ $submitter_id = " and submitter_id = '".$_GET["submitter_id"]."' "; } else { $submitter_id = null; }
    if(isset($_GET["id_edi_gateway"]) && $_GET["id_edi_gateway"] != null){ $id_edi_gateway = " and id_edi_gateway = '".$_GET["id_edi_gateway"]."' "; } else { $id_edi_gateway = null; }
    if(isset($_GET["payer_id"]) && $_GET["payer_id"] != null){ $payer_id = " and payer_id = '".$_GET["payer_id"]."' "; } else { $payer_id = null; }                    

   
$conexion = conectar();
$sql  = "SELECT * FROM seguros WHERE true ".$name.$address.$city.$state.$zip.$phone.$fax.$provider.$id_type_provider.$id_claim_ind.$submitter_id.$id_edi_gateway.$payer_id.';'; 
$resultado = ejecutar($sql,$conexion);

$reporte = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($resultado)) {            
    $reporte[$i] = $datos;
    $i++;
}   


                
   
                
                
?>




        <script  language="JavaScript" type="text/javascript">

                $(document).ready(function() {
                        $('#table_seguros').DataTable({                                
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
                
                
                function Modificar_seguros(
                id_seguros,
                name,
                address,
                city,
                state,
                zip,
                phone,
                fax,
                //id_reporting_system,
                provider,
                id_type_provider,
                id_claim_ind,
                submitter_id,
                id_edi_gateway,
                payer_id,
                id_carriers,
                tipo_seguros){ 
            
id_seguros = replaceAll(id_seguros,' ','|');
name = replaceAll(name,' ','|');
address = replaceAll(address,' ','|');
city = replaceAll(city,' ','|');
state = replaceAll(state,' ','|');
zip = replaceAll(zip,' ','|');
phone = replaceAll(phone,' ','|');
fax = replaceAll(fax,' ','|');
//id_reporting_system = replaceAll(id_reporting_system,' ','|');
provider = replaceAll(provider,' ','|');
id_type_provider = replaceAll(id_type_provider,' ','|');
id_claim_ind = replaceAll(id_claim_ind,' ','|');
submitter_id = replaceAll(submitter_id,' ','|');
id_edi_gateway = replaceAll(id_edi_gateway,' ','|');
payer_id = replaceAll(payer_id,' ','|');
                    
/*tablas_dinamicas_javascript_2*/

                window.location.href = "../seguros/registrar_seguros.php?&id_seguros="+id_seguros+"&name="+name+"&address="+address+"&city="+city+"&state="+state+"&zip="+zip+"&phone="+phone+"&fax="+fax+"&provider="+provider+"&id_type_provider="+id_type_provider+"&id_claim_ind="+id_claim_ind+"&submitter_id="+submitter_id+"&id_edi_gateway="+id_edi_gateway+"&payer_id="+payer_id+"&tipo_seguros="+tipo_seguros+"&id_carriers="+id_carriers;
/*tablas_dinamicas_javascript*/            
                
             
                }


        function Eliminar_seguros(id_seguros,incrementador,accion){

                       swal({
          title: "Confirmación",
          text: "Desea Eliminar el Registro N° "+id_seguros+"?",
          type: "warning",
          showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Eliminar",
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {

                   var campos_formulario = '&id_seguros='+id_seguros+'&accion='+accion;
                    
                        $.post(
                                "../../controlador/seguros/gestionar_seguros.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                    //confirmacion_almacenamiento('Registro N° '+Pat_id+' Eliminado');
                                    $('#resultado_'+incrementador).html(resultado_controlador.mensaje_data_table);

                                },
                                "json"
                                );

          }
        });


                }

            $('html,body').animate({scrollTop: $("#bajar_aqui").offset().top}, 800);

        </script>    
    <div id="bajar_aqui"></div>
            <table align="center" border="0">
                <tr>
                    <td align="center"><b><font size="3">Resultado de la Consulta</font></b></td>
                </tr>
            </table>
        
<div class="col-lg-12">

    <table id="table_seguros" class="table table-striped table-bordered" cellspacing="0" width="100%"> 
                    <thead>
                        <tr>

                                <th style="width:10px;" >ID SEGUROS  </th>
                                <!--imagen_s-->
                                <th>NAME  </th>
                        <!--        <th>TYPE INSURANCE  </th>                                                            
                                <th>INSURANCE TABLE </th>                                                            
tablas_dinamicas_consulta-->
                                <th>ACCIÓN</th>

                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $i = 0;
                        $color = '<tr class="odd_gradeX">';
                        while (isset($reporte[$i])) { 
  
                        $sql = 'SELECT tipo_seguros,insurance,sr.id_tipo_seguros FROM tbl_seguros_rel_tipo_seguros sr '
                                . 'left join seguros s on s.ID = sr.id_seguros '
                                . 'left join tbl_seguros_tipo_seguros sts on sts.id_tipo_seguros = sr.id_tipo_seguros '
                                . 'WHERE ID = \''.$reporte[$i]['ID'].'\';';

                        $resultado_rel = ejecutar($sql,$conexion);

                            $reporte_tipo_seguro = null;                                                
                            
                            $row = null;
                    
                        $sql = 'SELECT * FROM tbl_seguros_table
                            left join tbl_seguros_type_task using(id_seguros_type_task)
                            left join tbl_seguros_type_person using(id_seguros_type_person)
                            left join tbl_seguros_progress using(id_seguros_table)
                            WHERE id_seguros = '.$reporte[$i]['ID'].';';                    
                        $resultado = ejecutar($sql,$conexion);
                    
                    $reporte_seguros_table = array();
                                        
                    $y = 0;      
                    while($datos = mysqli_fetch_assoc($resultado)) {            
                        $reporte_seguros_table[$y] = $datos;
                        $y++;
                    }
                    
                    
                    
                    echo $color;
                            

                        echo '<td align="center"><font size="2"><b>'.$reporte[$i]['ID'].'</b></font></td>';
                        /*imagen_i*/
                        echo '<td align="center"><font size="2">'.$reporte[$i]['insurance'].'</font></td>';
                      /*  echo '<td align="center"><table>';
                        
                            $tipo_seguros = null;
                            $s=0;
                            while ($row=mysqli_fetch_array($resultado_rel)) {	
                                                                                                
                                $tipo_seguros .= $row['id_tipo_seguros'].'|';
                                
                                echo '<tr>';
                                echo '<td>'.$row['tipo_seguros'].'</td>';
                                echo '</tr>';
                                
                            $s++;        
                            }
                            
                            $tipo_seguros = substr ($tipo_seguros, 0, strlen($tipo_seguros) - 1);
                        
                        echo '</table></td>';                                              
                        echo '<td align="center"><table border="1" width="100%">';

                        
                        if($reporte_seguros_table != null) {
                        
                                echo '<tr>';
                                echo '<td align="center"><font size="3" color="#BDBDBD"><b>Type Task</b></font></td>';
                                echo '<td align="center"><font size="3" color="#BDBDBD"><b>Type Person</b></font></td>';
                                echo '<td align="center"><font size="3" color="#BDBDBD"><b>Discipline</b></font></td>';
                                echo '<td align="center"><font size="3" color="#BDBDBD"><b>Visits</b></font></td>';
                                echo '</tr>';                        
                        
                            
                            $s=0;
                            while (isset($reporte_seguros_table[$s])) {	
                                            
                                
                                echo '<tr>';
                                echo '<td align="center"><font size="2">'.$reporte_seguros_table[$s]['seguros_type_task'].'</td>';
                                echo '<td align="center"><font size="2">'.$reporte_seguros_table[$s]['seguros_type_person'].'</td>';
                                echo '<td align="center"><font size="2">'.$reporte_seguros_table[$s]['discipline'].'</td>';
                                if($reporte_seguros_table[$s]['visits'] != null) { 
                                    echo '<td align="center"><font size="2">'.$reporte_seguros_table[$s]['visits'].'</td>';
                                } else {
                                    echo '<td align="center"><font size="2">N/A</td>';
                                }
                                echo '</tr>';
                                
                            $s++;        
                            }
                            
                            
                        }
                            
                        echo '</table></td>';                                              */

                        echo '<td align="center"><font size="2"><div id="resultado_'.$i.'"><a onclick="Modificar_seguros(\''.
                                $reporte[$i]['ID'].'\',\''.
                                $reporte[$i]['insurance'].'\',\''.
                                $reporte[$i]['address'].'\',\''.
                                $reporte[$i]['city'].'\',\''.
                                $reporte[$i]['state'].'\',\''.
                                $reporte[$i]['zip'].'\',\''.
                                $reporte[$i]['phone'].'\',\''.
                                $reporte[$i]['fax'].'\',\''.                                
                                $reporte[$i]['provider'].'\',\''.
                                $reporte[$i]['id_type_provider'].'\',\''.
                                $reporte[$i]['id_claim_ind'].'\',\''.
                                $reporte[$i]['submitter_id'].'\',\''.
                                $reporte[$i]['id_edi_gateway'].'\',\''.
                                $reporte[$i]['payer_id'].'\',\''.
                                $reporte[$i]['id_carriers'].'\',\''.
                                $tipo_seguros.'\');" style="cursor: pointer;" class="ruta"><img src="../../../images/sign-up.png" alt="Modificar Patients"  title="Modificar Patients" style="height: 25px; width: 25px" border="0" align="absmiddle"></a><br><br>
'
. '<a onclick="Eliminar_seguros(\''.$reporte[$i]['ID'].'\',\''.$i.'\',\'eliminar\');" style="cursor: pointer;" class="ruta"><img src="../../../images/papelera.png" alt="Eliminar Patients"  title="Eliminar Patients" style="height: 25px; width: 25px" border="0" align="absmiddle"></a>';

            echo '</div></font></td>';
            $color = ($color == '<tr class="even_gradeC">' ? '<tr class="odd_gradeX">' : '<tr class="even_gradeC">');

                            echo '</tr>';

                            $i++;
                        }
                        ?>

                    </tbody>
                </table>
            </div>        
      
               <div class="spacer"></div>
       
</html>
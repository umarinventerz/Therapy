<?php 
session_start(); 
require_once("../../../conex.php"); 
if(!isset($_SESSION['user_id'])){ 
    echo '<script>alert(\'Must LOG IN First\')</script>'; 
    echo '<script>window.location="../../../index.php";</script>'; 
} 


if(isset($_SESSION['name'])){ 
    $_POST['name'] = trim($_SESSION['name']); 
    $_POST['find'] = $_SESSION['find']; 
} 
 
if(isset($_GET['name'])){
  $_POST['name'] = $_GET['name'];
  $_POST['find'] = ' Find ';  
}
  
 
if($_POST['find'] == " Find " || $_POST['buttonReload'] == "RECARGADO"){ 


   
$conexion = conectar(); 
 
 $ID = $_POST['name']; 
 
 

 
if($_POST['name']==''){ 
   
     echo '
  <link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
<script src="../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<link rel="stylesheet" type="text/css" href="../../../css/sweetalert2.min.css"/>        
<script type="text/javascript" language="javascript" src="../../../js/sweetalert2.min.js"></script>
<script>

setTimeout(function() {
        swal({
            title: "NAME MISSING!",
            text: " CHOOSE INSURANCE!",
            closeOnConfirm: true,
            type: "warning"
        }, function() {
            window.location.href = "search.php";
        });
    }, 400);
</script>';
header("Refresh:2");
exit; 




}else{ 
list($ID,$company) = explode('-',$_POST['name']); 


/*if(isset($_GET['name'])){
  $_POST['name'] = $_GET['name'];
  $_POST['find'] = ' Find ';  
}
 
if($_POST['find'] == " Find "){ 

//if(isset($_POST)){
//print_r($_POST);
//}
	 
$conexion = conectar(); 
 
$_POST['name'] = $_GET['name']; 
 
 
 
if($_POST['name']==''){ 
    echo 'EMPTY NAME'; 
}else{ 
//list($Phy_id) = explode('-',$_POST['name']); 



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

 $ID = $_POST['name']; 
 $ID = $_GET['name'];*/


 echo $sql1="SELECT ID,insurance,address,city,state,zip,phone,fax,
provider,id_type_provider,id_claim_ind,submitter_id,id_edi_gateway,payer_id
FROM seguros 
where  ID = '".$ID."' "; 
 

 

$sql_notes_general="SELECT NG.id_notes_general,NG.notes_general,NG.date_notes,NG.id_person,DTP.type_persons,US.Last_name,US.First_name,S.insurance FROM tbl_notes_general NG LEFT JOIN tbl_doc_type_persons DTP ON (DTP.id_type_persons = NG.id_type_person)
                    LEFT JOIN seguros S ON (S.id=NG.id_person)
                    LEFT JOIN user_system US ON (US.user_id = NG.id_user) WHERE NG.table_reference='seguros' AND NG.id_person=".$ID." ORDER BY NG.id_notes_general ASC";






$sql_contacts  = "
 select 
genero,
relacion,
descripcion,
persona_contacto,
cargo_persona_contacto,
email,
telefono,
fax
 from tbl_contacto_persona 
left join tbl_contactos using(id_contactos)
 WHERE
   tabla_ref = 'seguros' and id_tabla_ref = '".$ID."' GROUP BY persona_contacto;

"; 
            $resultado = ejecutar($sql_contacts,$conexion);

            $reporte_contacts = array();

            $i = 0;      
            while($datos = mysqli_fetch_assoc($resultado)) {            
                $reporte_contacts[$i] = $datos;
                $i++;
            }        




$sqlDocuments = "
  SELECT *,trim(route_document),trim(type_documents), trim(type_persons) 
  FROM tbl_documents ct 
  LEFT JOIN tbl_doc_type_persons tp ON tp.id_type_persons = ct.id_type_person 
  LEFT JOIN tbl_doc_type_documents td ON td.id_type_documents = ct.id_type_document 
  LEFT JOIN seguros s on s.ID = ct.id_person
  WHERE true AND ct.id_type_person = 3 AND ct.id_person = '".$ID."';

  ";
$resultadoDocuments = ejecutar($sqlDocuments,$conexion);

        $reporteDocuments = array();
        
        $t = 0;      
        while($datosDocuments = mysqli_fetch_assoc($resultadoDocuments)) {            
            $reporteDocuments[$t] = $datosDocuments;
            $t++;
        }




 
} 
unset($_SESSION['name']); 
unset($_SESSION['find']); 
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
     
    <title>.: KIDWORKS THERAPY :.</title> 
    <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/> 
    <link href="../../../css/portfolio-item.css" rel="stylesheet"> 
    <script src="../../../js/jquery.min.js"></script> 
    <script src="../../../js/bootstrap.min.js"></script> 
    

<link href="../../../css/portfolio-item.css" rel="stylesheet">

<link href="../../../plugins/bootstrap/bootstrap.css" rel="stylesheet">
<link href="../../../plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">

<link href="../../../plugins/select2/select2.css" rel="stylesheet">
<link href="../../../css/style_v1.css" rel="stylesheet">


<script src="../../../plugins/jquery/jquery.min.js"></script>
<script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>


<!-- Include all compiled plugins (below), or include individual files as needed 
<script src="plugins/bootstrap/bootstrap.min.js"></script>


<!-- All functions for this theme + document.ready processing -->


<script src="../../../js/devoops_ext.js"></script>


    <!-- Style Bootstrap-->
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/dataTables.bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../../../css/dataTables/buttons.dataTables.css">
    <link rel="stylesheet" type="text/css" href="../../../css/resources/shCore.css"> 
       
    <!-- End Style -->
    <script type="text/javascript" language="javascript" class="init">  
         
    $(document).ready(function() { 
        $('#insurance_list').DataTable({
            dom: 'Bfrtip',
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
            buttons: [
                'pageLength',
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            "pageLength": 10
        } ); 
    } ); 
 
    </script> 
    <script> 
 
    function selectAllFields(){                                    
                    $("input:checkbox").each(function(){ 
                        if($("input[name=allFields]:checked").length == 1){ 
                            this.checked = true; 
                        }else{ 
                            this.checked = false; 
                        }             
                    }); 
           } 
     
    function findData(){         
        document.getElementById("myForm").submit(); 
    } 
    function loadOrderFieldsShow(valor){ 
        $("input:checkbox[name="+valor+"]").each(function(){             
            if(this.checked == true){ 
                if($("#fieldsShow").val() == '') 
                    $("#fieldsShow").val(valor);                     
                else 
                    $("#fieldsShow").val($("#fieldsShow").val()+','+valor); 
            }else{ 
                var str = $("#fieldsShow").val();                 
                var res = str.replace(valor,""); 
                res = res.replace(",,", ","); 
                $("#fieldsShow").val(res); 
            } 
            });         
    } 
        function blockCheckBox(){ 
     
        if($("#name").val() != ''){ 
            $("input:checkbox").each(function(){                                 
                $(this).attr('disabled','disabled'); 
                    });     
        }else{ 
            $("input:checkbox").each(function(){                                 
                this.disabled = false; 
                    }); 
        } 
    } 
 

     function showInsurance(id){
    window.open('../seguros/search_seguros.php?name='+id,'w','width=1300px,height=1000px,noresize');
    }


    function blockCheckBox1(){ 
        if($("#patient_id").val() != ''){ 
            $("input:checkbox").each(function(){                                 
                $(this).attr('disabled','disabled');                 
                    });     
        }else{ 
            $("input:checkbox").each(function(){                                                     
                this.disabled = false; 
                    }); 
        } 
    }    
    function updatePatient(pat_id,company,patient_name){         
        $("#result_u"+pat_id).load("../../controlador/patients/update_patients.php","&Patient_id="+pat_id+"&Company="+company+"&newInsurance="+$("#newInsurance_"+pat_id).val()+"&patient_name="+patient_name); 
        alert("Update Succefull"); 
        window.location="search.php"; 
    } 
    </script> 
</head> 
 
<body> 
 
    <!-- Navigation --> 
    
    <?php 
    if(!isset($_GET['name']))
    $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?> 
 
 
    <!-- Page Content --> 
    <div class="panel-collapse collapse in" style="padding-top: 30px;padding-bottom: 10px;padding-right: 30px;padding-left: 30px;">
 
        <!-- Portfolio Item Heading --> 

        <!-- /.row --> 
 
        <!-- Portfolio Item Row --> 
        <div class="row"> 
 	<?php if(!isset($_GET['name'])){?>
            <div class="col-md-12"> 
             <form class="form-horizontal" id="myForm" action="search_seguros.php" method="post"> 
            
            <div class="col-lg-12">
                <div class="col-lg-1"></div>
                <h3 class="page-header">Choose INSURANCE from List</h3>
            </div> 
            <div class="row">             


                    <div class="col-xs-1"></div>

                    <div class="col-xs-3">
                        <div class="input-group">
                <span  class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                <select style="width:250px" name='name' id='name'  onchange="blockCheckBox();">
                    <option value=''>--- SELECT ---</option>
                    <?php 
                  $sql  = "Select ID, insurance from seguros order by ID "; 
                    $conexion = conectar(); 
                    $resultado = ejecutar($sql,$conexion); 
                    while ($row=mysqli_fetch_array($resultado))  
                    {     
                    //  if($row["Phy_id"] == '')
                   //   print("<option value='".$row["Phy_id"].">".$row["Physician_name"] ." </option>"); 
                    //     else  
                      //    print("<option value='".$row["Phy_id"]." selected>".$row["Physician_name"] ." </option>"); 


      if((trim($row["insurance"])) == (trim($patient_id)."-".$company)) 
                            print("<option  value='".$row["ID"]."' selected>".$row["insurance"]." </option>");
                        else 
                            print("<option value='".$row["ID"]."'>".$row["insurance"]." </option>");




                    }       
             
                    ?> 
                       
                    </select>
                        </div>
                    </div>

<div class="col-lg-3">

                    <input id='find' style="height: 34px" name='find' type='submit' value=" Find " class="btn btn-success " onclick="findData();">
                    &nbsp&nbsp           <input name='reset' style="height: 34px" type='button' value=" Reset " onclick= "window.location.href = 'search_seguros.php';" class="btn btn-danger ">

                </div>



            </div> 
           


        </form>               
            </div>  
            <?php }?>       
        </div> 
        <!-- /.row --> 
    <hr> 
        <!-- Related Projects Row --> 
        <div class="row"> 
 
            <div class="col-lg-12"> 
        <?php    
    
        if($_POST['find'] == ' Find ') { 



             
        ?> 
                
         
 
<div class="panel-group" id="" role="tablist" aria-multiselectable="true"> 
  <div class="panel panel-default"> 
    <div class="panel-heading" role="tab" id="headingOne"> 
      <h4 class="panel-title"> 
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> 
          INSURANCE INFORMATION 
        </a> 
      </h4> 
    </div> 
    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne"> 
      <div class="panel-body"> 
         <table   class="table table-striped">
                  <thead>
            <tr>  
                      <th>ID</th> 
                      <th>Name</th> 
                      <th>Address</th>  
                      <th>City</th>
                      <th>State</th>
                      <th>Zip</th>
                      <th>Phone</th>
                          
            </tr>
                  </thead>



<tbody>
         <?php 
      $conexion = conectar(); 
        $result1 = mysqli_query($conexion, $sql1); 
 
            $i = 1; 
                    while($row = mysqli_fetch_array($result1,MYSQLI_ASSOC)){ 

 
                        if($_POST['name'] != ''){ 
 
echo '

        <tr>
 
                         <td>'.$row['ID'].'</td> 
                      <td>'.$row['insurance'].'</td> 
                      <td>'.$row['address'].'</td> 
                      <td>'.$row['city'].'</td> 
                      <td>'.$row['state'].'</td> 
                      <td>'.$row['zip'].'</td>  
                      <td>'.$row['phone'].'</td>
                     </tr>
  
                      '; 
                       
                       
                    $arregloPatient[$i] = $row['ID']; 
                    $i++; 
                    } 
                } 
     ?>  
          </tbody></table> 

 <table   class="table table-striped">
                  <thead>
            <tr>  
                       
                       
                      <th>Fax</th> 
                      <th>Provider</th> 
                      <th>Id Type Prov</th> 
                      <th>Id Claim</th> 
                      <th>Submitter ID</th> 
                      <th>Edi Gateway</th>    
                      <th>Payer ID</th>   
            </tr>
                  </thead>



<tbody>
         <?php 
      $conexion = conectar(); 
        $result1 = mysqli_query($conexion, $sql1); 
 
            $i = 1; 
                    while($row = mysqli_fetch_array($result1,MYSQLI_ASSOC)){ 

 
                        if($_POST['name'] != ''){ 
 
echo '

        <tr>
 
                    
                      
                      <td>'.$row['fax'].'</td> 
                      <td>'.$row['provider'].'</td> 
                      <td>'.$row['id_type_provider'].'</td> 
                      <td>'.$row['id_claim_ind'].'</td> 
                      <td>'.$row['id_edi_gateway'].'</td> 
                      <td>'.$row['payer_id'].'</td> 
                     </tr>
  
                      '; 
                       
                       
                    $arregloPatient[$i] = $row['ID']; 
                    $i++; 
                    } 
                } 
     ?>  
          </tbody></table> 




      </div> 
    </div> 
  </div> 
   

    
<div class="panel panel-default"> 
    <div class="panel-heading" role="tab" id="headingNine"> 
      <h4 class="panel-title"> 
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseNine" aria-expanded="false" aria-controls="collapseEight"> 
          CONTACTS
        </a> 
      </h4> 
</div> 
    <div id="collapseNine" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingNine"> 
      <div class="panel-body"> 
      


<!-- TABLA MIENTRAS -->

 <table id="table_physician" class="table table-striped table-bordered" cellspacing="0" width="100%"> 
                    <thead>
                        <tr>

                                <th style="width:10px;" >PERSONA CONTACTO</th>
                                <th style="width:10px;" >CARGO</th>
                                <th style="width:10px;" >GENERO</th>
                                <th style="width:10px;" >RELACIÓN</th>
                                <th style="width:10px;" >DESCRICIÓN</th>
                                <th style="width:10px;" >EMAIL</th>
                                <th style="width:10px;" >TELÉFONO</th>
                                <th style="width:10px;" >FAX</th>                                                                                                                            
                                <!--<th>ACCIÓN</th>-->

                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $i = 0;
                        $color = '<tr class="odd_gradeX">';
                        while (isset($reporte_contacts[$i])) {

                    echo $color;
                                                    
                        echo '<td align="center"><font size="2"><b>'.$reporte_contacts[$i]['persona_contacto'].'</b></font></td>';
                        
                        echo '<td align="center"><font size="2">'.strtoupper(utf8_encode($reporte_contacts[$i]['cargo_persona_contacto'])).'</font></td>';
                        echo '<td align="center"><font size="2">'.strtoupper(utf8_encode($reporte_contacts[$i]['genero'])).'</font></td>';
                        echo '<td align="center"><font size="2">'.strtoupper(utf8_encode($reporte_contacts[$i]['relacion'])).'</font></td>';
                        echo '<td align="center"><font size="2">'.strtoupper(utf8_encode($reporte_contacts[$i]['descripcion'])).'</font></td>';
                        echo '<td align="center"><font size="2">'.strtoupper(utf8_encode($reporte_contacts[$i]['email'])).'</font></td>';
                        echo '<td align="center"><font size="2">'.strtoupper(utf8_encode($reporte_contacts[$i]['telefono'])).'</font></td>';
                        echo '<td align="center"><font size="2">'.strtoupper(utf8_encode($reporte_contacts[$i]['fax'])).'</font></td>';
                        

                      /*  echo '<td align="center"><font size="2"><div id="resultado_'.$i.'">'
. '<a onclick="Modificar_patients(\''.$reporte[$i]['Pat_id'].'\',\''.$reporte[$i]['Last_name'].'\',\''.$reporte[$i]['First_name'].'\',\''.$reporte[$i]['Sex'].'\',\''.$reporte[$i]['DOB'].'\',\''.$reporte[$i]['Guardian'].'\',\''.$reporte[$i]['Social'].'\',\''.$reporte[$i]['Address'].'\',\''.$reporte[$i]['City'].'\',\''.$reporte[$i]['Street'].'\',\''.$reporte[$i]['Zip'].'\',\''.$reporte[$i]['county'].'\',\''.$reporte[$i]['E_mail'].'\',\''.$reporte[$i]['Phone'].'\',\''.$reporte[$i]['Cell'].'\',\''.$reporte[$i]['Ref_Physician'].'\',\''.$reporte[$i]['Phy_NPI'].'\',\''.$reporte[$i]['Pri_Ins'].'\',\''.$reporte[$i]['Auth'].'\',\''.$reporte[$i]['Sec_INS'].'\',\''.$reporte[$i]['Auth_2'].'\',\''.$reporte[$i]['Ter_Ins'].'\',\''.$reporte[$i]['Auth_3'].'\',\''.$reporte[$i]['Mem_N'].'\',\''.$reporte[$i]['Grp_N'].'\',\''.$reporte[$i]['Intake_Agmts'].'\',\''.$reporte[$i]['Table_name'].'\',\''.$reporte[$i]['Thi_Ins'].'\',\''.$reporte[$i]['active']
.'\');" style="cursor: pointer;" class="ruta"><img src="../../../images/sign-up.png" alt="Modificar Patients"  title="Modificar Patients" style="height: 25px; width: 25px" border="0" align="absmiddle"></a><br><br>
'
. '<a onclick="Eliminar_patients(\''.$reporte[$i]['Pat_id'].'\',\''.$i.'\',\'eliminar\');" style="cursor: pointer;" class="ruta"><img src="../../../images/papelera.png" alt="Eliminar Patients"  title="Eliminar Patients" style="height: 25px; width: 25px" border="0" align="absmiddle"></a>';

            echo '</div></font></td>';*/
            $color = ($color == '<tr class="even_gradeC">' ? '<tr class="odd_gradeX">' : '<tr class="even_gradeC">');

                            echo '</tr>';

                            $i++;
                        }
                        ?>


      <!--  <table   class="table table-striped table-bordered">
                  <thead>
            <tr>  
                <th style="width:10px;" >PERSONA CONTACTO</th>
                <th>DATE OF BIRD</th>                                
                <th>DATOS DE CONTACTO</th>
                <th>ADDRESS</th>
                <th>MODIFY</th>
            </tr>
                  </thead>
<tbody>   -->
         <?php
         
             
    /*                    $i = 0;
                        $color = '<tr class="odd_gradeX">';
                        while (isset($reporte_contacts[$i])) {

                    echo $color;
                                                    
                        echo '<td align="center"><font size="2"><b>'.$reporte_contacts[$i]['persona_contacto'].'</b></font></td>';
                        echo '<td align="center"><font size="2">'.$reporte_contacts[$i]['DOB'].'</font></td>';
                        echo '<td align="center">';
                        

                            
                        $cargo_persona_contacto_array = explode('|',str_replace(',','',$reporte_contacts[$i]['cargos_persona_contacto']));
                        $tipo_contacto_array = explode('|',str_replace(',','',$reporte_contacts[$i]['tipo_contacto']));
                        $descripcion_contacto_array = explode('|',str_replace(',','',$reporte_contacts[$i]['descripcion_contacto']));
                        
                            $r=0; 
                            $f=1; 
                            while (isset($cargo_persona_contacto_array[$r])){
                            
                            if($r==0){
                            echo '<table border="0" width="700px">';

                                echo '<tr>';
                                echo '<td align="left" width="40%"><font color="#585858" size="2"><b>Cargo</b></font><td>';
                                echo '<td align="left" width="30%"><font color="#585858" size="2"><b>Tipo de Contacto</b></font><td>';
                                echo '<td align="left" width="30%"><font color="#585858" size="2"><b>Descripci&oacute;n Contacto</b></font><td>';
                                echo '</tr>';
                                echo '<tr>';
                                echo '<td align="left" colspan="5"><hr><td>';
                                echo '</tr>'; 
                                
                                
                            }                                
                            
                            
                            if($cargo_persona_contacto_array[$r] != null && $tipo_contacto_array[$r] != null && $descripcion_contacto_array[$r] != null) {
                                echo '<tr>';
                                echo '<td align="left"><font size="2">'.$f.'&nbsp;&nbsp;&nbsp;'.$cargo_persona_contacto_array[$r].'</font><td>';
                                echo '<td align="left"><font size="2">'.$tipo_contacto_array[$r].'</font><td>';
                                echo '<td align="left"><font size="2">'.$descripcion_contacto_array[$r].'</font><td>';
                                echo '</tr>';                              
                            }
                            
                            if($cargo_persona_contacto_array[$r] == null){
                                echo '</table><br>';                            
                            }                            
                            
                            
                                $r++;
                                $f++;
                            }
                        
                        
                        echo '</td>';
                        echo '<td align="center"><font size="2">'.$reporte_contacts[$i]['Address'].'</font></td>';
                        
                        


                      echo '<td align="center"><font size="2"><div id="resultado_'.$i.'">'
. '<a onclick="Modificar_patients(\'hola\');" style="cursor: pointer;" class="ruta"><img src="../../../images/sign-up.png" alt="Modify Patients"  title="Modify Patients" style="height: 25px; width: 25px" border="0" align="absmiddle"></a>';
            echo '</div></font></td>';
            $color = ($color == '<tr class="even_gradeC">' ? '<tr class="odd_gradeX">' : '<tr class="even_gradeC">');

                            echo '</tr>';

                            $i++;
                        }*/
                        ?>
          </tbody></table>  
      </div> 
    </div> 
  </div> 

    <!--AGREGANDO GENERAL NOTES-->
    <div class="panel panel-default"> 
        <div class="panel-heading" role="tab" id="headingGeneral"> 
          <h4 class="panel-title"> 
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseGeneral" aria-expanded="false" aria-controls="collapseGeneral"> 
              GENERAL NOTES
            </a> 
          </h4> 
        </div> 
        <div id="collapseGeneral" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingGeneral"> 
            <div class="panel-body">
                <table class="table table-striped table-bordered">
                    <thead>
                            <tr>  
                                    <th>ID NOTES GENERAL </th>
                                    <th>TYPE PERSON </th>
                                    <th>PERSON </th>
                                    <th>USER</th>
                                    <th>NOTES GENERAL</th>
                                    <th>DATE NOTES</th>
                                  
                            </tr>
                   </thead>
                    <tbody>
                         <?php 
                            
                            $conexion = conectar(); 
                            $result3 = mysqli_query($conexion, $sql_notes_general);                            
                            $i = 1; 
                                    while($row = mysqli_fetch_array($result3,MYSQLI_ASSOC)){ 

                                        if($_POST['name'] != ''){  
                                            echo '
                                                    <tr>
                                                       <td>'.$row['id_notes_general'].'</td>                                                       
                                                       <td>'.$row['type_persons'].'</td>
                                                       <td><a onclick="showInsurance(\''.$row['id_person'].'\')">'.strtoupper($row['insurance']).'</a></td>
                                                       <td>'.strtoupper($row['Last_name'].', '.$row['First_name']).'</td>
                                                       <td>'.$row['notes_general'].'</td>
                                                       <td>'.$row['date_notes'].'</td>
                                                       
                                                       
                                                    </tr>'; 


                                            $arregloPatient[$i] = $row['Pat_id']; 
                                            $i++; 
                                    } 
                                } 
                        ?>  
                    </tbody>
                </table>
            </div> 
        </div> 
  </div>






             <!--AGREGANDO DOCUMENTS-->


<div class="panel panel-default"> 
    <div class="panel-heading" role="tab" id="headingEleven"> 
      <h4 class="panel-title"> 
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven"> 
          DOCUMENTS
        </a> 
      </h4> 
    </div> 
    <div id="collapseEleven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEleven"> 
      <div class="panel-body"> 
        <table   class="table table-striped table-bordered">
                  <?php                         
        $conexion = conectar();
            $datos_result = '';
            $datos_result .= '
                    <thead>
                        <tr>
                            <th>TYPE DOCUMENT</th>
                            <th>TYPE PERSON</th>
                            <th>NAME</th>
                            <th>ROUTE DOCUMENT</th>
                            <th>DATE</th> 
                            <th>ACTION</th>
                        </tr>
                    </thead>

            <tbody>';
      
            $t=0;   
            $sum_total_pay_treatment = 0;
            $total_dur = 0;
            while (isset($reporteDocuments[$t])){ 

                    $datos_result .= '<tr>';        
                    $datos_result .= '<td>'.$reporteDocuments[$t]['type_documents'].'</td>';
                    $datos_result .= '<td>'.$reporteDocuments[$t]['type_persons'].'</td>';
                    $datos_result .= '<td>'.$reporteDocuments[$t]['insurance'].'</td>';                    
                    $datos_result .= '<td><a href="../../../'.$reporteDocuments[$t]['route_document'].'" target="_blank">'.$reporteDocuments[$t]['route_document'].'</a></td>';                    
                    $datos_result .= '<td>'.$reporteDocuments[$t]['date'].'</td>';                    
                    $datos_result .= '<td align="center">';
                    $datos_result .= '<a onclick="eliminar_registro_document(\''.$reporteDocuments[$t]['id_document'].'\',\''.$reporteDocuments[$t]['route_document'].'\')" style="cursor: pointer"><img style="width:30px" src="../../../images/papelera.png"></a>';
                    
                    $datos_result .= '</td>';
                    $datos_result .= '</tr>';
                    $sum_total_pay_treatment += $total_pay_treatment;
                    $t++;   
            }     
            
            $datos_result .= '</tbody>';                         
                        
                        echo $datos_result;                                                
                        
                        ?></table>  
      </div> 
    </div> 
  </div> 








 
</div> 
 
 
                 
           
             
        <?php }  else{  ?>     
            </div>         
        </div> 

 <!-- /.row --> 

<!-- ########################################################################################################## --> 
<!-- ###############  TABLA DE PACIENTES ABAJO                                      ########################### --> 
<!-- ########################################################################################################## --> 
 


            <div class="col-lg-12">

    <table id="insurance_list" class="table table-striped table-bordered" cellspacing="0" width="100%">
           <thead>
            <tr>  
                      <th>ID</th> 
                      <th>Name</th> 
                      <th>Address</th> 
                      <th>City</th>     
                      <th>Phone</th>                   
            </tr>
                  </thead>
<tbody>
         <?php 
     
      $conexion = conectar(); 

      $sqlpatientslist = " SELECT ID, insurance, address, city , phone from seguros ";
       
        $resultpatientslist = mysqli_query($conexion, $sqlpatientslist); 
 
            $i = 0; 
                    while($row = mysqli_fetch_array($resultpatientslist,MYSQLI_ASSOC)){ 
 
                        //if($_POST['name'] != ''){  
echo '
        <tr>
                 
                      <td><a onclick="showInsurance(\''.$row['ID'].'\')">'.$row['ID'].'</td> 
                      <td><a onclick="showInsurance(\''.$row['ID'].'\')">'.$row['insurance'].'</td> 
                      <td>'.$row['address'].'</td> 
                      <td>'.$row['city'].'</td> 
                      <td>'.$row['phone'].'</td> 
                      
                     </tr>
  
                      ';                        
                    $i++; 
                   // } 
                } 
     ?>  
          </tbody>
          </table>  

</div>


        <?php }  ?> 
        <!-- /.row --> 
        <!-- Footer --> 
        <footer> 
            <div class="row"> 
                <div class="col-lg-12"> 
                    <p>&copy; Copyright &copy; THERAPY AID 2016</p> 
                </div> 
            </div> 
            <!-- /.row --> 
        </footer> 
 
    </div> 
    <!-- /.container --> 
</body> 


<script type="text/javascript">
// Run Select2 plugin on elements
function DemoSelect2(){
  $('#name').select2(); 
}
// Run timepicker

$(document).ready(function() {
  // Create Wysiwig editor for textare
  //TinyMCEStart('#wysiwig_simple', null);
  //TinyMCEStart('#wysiwig_full', 'extreme');
  // Add slider for change test input length
  //FormLayoutExampleInputLength($( ".slider-style" ));
  // Initialize datepicker
  //$('#input_date_licence').datepicker({setDate: new Date()});
  //$('#input_date_finger').datepicker({setDate: new Date()});
  //$('#dob').datepicker({setDate: new Date()});
  //$('#hireDate').datepicker({setDate: new Date()});
  //$('#terminationDate').datepicker({setDate: new Date()});
  // Load Timepicker plugin
  //LoadTimePickerScript(DemoTimePicker);
  // Add tooltip to form-controls
  $('.form-control').tooltip();
  LoadSelect2ScriptExt(DemoSelect2);
  // Load example of form validation
  //LoadBootstrapValidatorScript(DemoFormValidator);
  // Add drag-n-drop feature to boxes
  //WinMove();
  //ShowDivEdit();
  //enableField();
});
</script>


</html>

                                          
<?php


error_reporting(0);
session_start();
require_once '../../../conex.php';
if(!isset($_SESSION['user_id'])){
    echo '<script>alert(\'MUST LOG IN\')</script>';
    echo '<script>window.location="../../../index.php";</script>';
}else{
    if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
        echo '<script>alert(\'PERMISION DENIED FOR THIS USER\')</script>';
        echo '<script>window.location="../../../home/home.php";</script>';
    }
}
                $equidad=0;
                     $equidad1=0;
                if(isset($_GET["id_treatments"]) && $_GET["id_treatments"] != null){ $id_treatments = "id_treatments = '".strtolower($_GET["id_treatments"]."'"); } else { $id_treatments = 'true'; }
            if(isset($_GET["campo_1"]) && $_GET["campo_1"] != null){  $equidad=2;} else { $campo_1 = 'true'; }
if(isset($_GET["campo_90"]) && $_GET["campo_90"] != null){  $equidad1=1;} else { $campo_90 = 'true'; }
            if(isset($_GET["campo_2"]) && $_GET["campo_2"] != null){ $campo_2 = "campo_2 = '".strtolower($_GET["campo_2"]."'"); } else { $campo_2 = 'true'; }
            if(isset($_GET["campo_3"]) && $_GET["campo_3"] != null){ $campo_3 = "campo_3 = '".strtolower($_GET["campo_3"]."'"); } else { $campo_3 = 'true'; }
            if(isset($_GET["campo_4"]) && $_GET["campo_4"] != null){ $campo_4 = "campo_4 = '".strtolower($_GET["campo_4"]."'"); } else { $campo_4 = 'true'; }
            if(isset($_GET["campo_5"]) && $_GET["campo_5"] != null){ $campo_5 = "campo_5 = '".strtolower($_GET["campo_5"]."'"); } else { $campo_5 = 'true'; }
            if(isset($_GET["campo_6"]) && $_GET["campo_6"] != null){ $campo_6 = "campo_5 = '".strtolower($_GET["campo_6"]."'"); } else { $campo_6 = 'true'; }
            if(isset($_GET["campo_7"]) && $_GET["campo_7"] != null){ $campo_7 = "campo_7 = '".strtolower($_GET["campo_7"]."'"); } else { $campo_7 = 'true'; }
            if(isset($_GET["campo_8"]) && $_GET["campo_8"] != null){ $campo_8 = "campo_8 = '".strtolower($_GET["campo_8"]."'"); } else { $campo_8 = 'true'; }
            if(isset($_GET["campo_9"]) && $_GET["campo_9"] != null){ $campo_9 = "campo_9 = '".strtolower($_GET["campo_9"]."'"); } else { $campo_9 = 'true'; }
            if(isset($_GET["license_number"]) && $_GET["license_number"] != null){ $license_number = "license_number = '".strtolower($_GET["license_number"]."'"); } else { $license_number = 'true'; }
            if(isset($_GET["campo_10"]) && $_GET["campo_10"] != null){ $campo_10 = "campo_10 = '".strtolower($_GET["campo_10"]."'"); } else { $campo_10 = 'true'; }
            if(isset($_GET["campo_11"]) && $_GET["campo_11"] != null){ $campo_11 = "campo_11 = '".strtolower($_GET["campo_11"]."'"); } else { $campo_11 = 'true'; }
            if(isset($_GET["campo_12"]) && $_GET["campo_12"] != null){ $campo_12 = "campo_12 = '".strtolower($_GET["campo_12"]."'"); } else { $campo_12 = 'true'; }
            if(isset($_GET["campo_13"]) && $_GET["campo_13"] != null){ $campo_13 = "campo_13 = '".strtolower($_GET["campo_13"]."'"); } else { $campo_13 = 'true'; }
            if(isset($_GET["campo_14"]) && $_GET["campo_14"] != null){ $campo_14 = "campo_14 = '".strtolower($_GET["campo_14"]."'"); } else { $campo_14 = 'true'; }
            if(isset($_GET["campo_15"]) && $_GET["campo_15"] != null){ $campo_15 = "campo_15 = '".strtolower($_GET["campo_15"]."'"); } else { $campo_15 = 'true'; }
            if(isset($_GET["campo_16"]) && $_GET["campo_16"] != null){ $campo_16 = "campo_16 = '".strtolower($_GET["campo_16"]."'"); } else { $campo_16 = 'true'; }
            if(isset($_GET["campo_17"]) && $_GET["campo_17"] != null){ $campo_17 = "campo_17 = '".strtolower($_GET["campo_17"]."'"); } else { $campo_17 = 'true'; }
            if(isset($_GET["campo_18"]) && $_GET["campo_18"] != null){ $campo_18 = "campo_18 = '".strtolower($_GET["campo_18"]."'"); } else { $campo_18 = 'true'; }
            if(isset($_GET["campo_19"]) && $_GET["campo_19"] != null){ $campo_19 = "campo_19 = '".strtolower($_GET["campo_19"]."'"); } else { $campo_19 = 'true'; }
            if(isset($_GET["campo_20"]) && $_GET["campo_20"] != null){ $campo_20 = "campo_20 = '".strtolower($_GET["campo_20"]."'"); } else { $campo_20 = 'true'; }
            if(isset($_GET["campo_21"]) && $_GET["campo_21"] != null){ $campo_21 = "campo_21 = '".strtolower($_GET["campo_21"]."'"); } else { $campo_21 = 'true'; }
            if(isset($_GET["campo_22"]) && $_GET["campo_22"] != null){ $campo_22 = "campo_22 = '".strtolower($_GET["campo_22"]."'"); } else { $campo_22 = 'true'; }
            if(isset($_GET["campo_23"]) && $_GET["campo_23"] != null){ $campo_23 = "campo_23 = '".strtolower($_GET["campo_23"]."'"); } else { $campo_23 = 'true'; }
            if(isset($_GET["campo_24"]) && $_GET["campo_24"] != null){ $campo_24 = "campo_24 = '".strtolower($_GET["campo_24"]."'"); } else { $campo_24 = 'true'; }
            if(isset($_GET["pay"]) && $_GET["pay"] != null){ $pay = "pay = '".strtolower($_GET["pay"]."'"); } else { $pay = 'true'; }
            if(isset($_GET["adults_progress_notes"]) && $_GET["adults_progress_notes"] != null){ $adults_progress_notes = "adults_progress_notes = '".strtolower($_GET["adults_progress_notes"]."'"); } else { $adults_progress_notes = 'true'; }
            if(isset($_GET["pedriatics_progress_notes"]) && $_GET["pedriatics_progress_notes"] != null){ $pedriatics_progress_notes = "pedriatics_progress_notes = '".strtolower($_GET["pedriatics_progress_notes"]."'"); } else { $pedriatics_progress_notes = 'true'; }

$conexion = conectar();

            $info_pat="SELECT *,concat(last_name,', ',first_name) as employee_name FROM employee
                    WHERE  id =".$_SESSION['user_id'];
$info_pat_id = ejecutar($info_pat,$conexion);
while ($row=mysqli_fetch_array($info_pat_id)) {
    $salida['kind_employee'] = $row['kind_employee'];
    $salida['employee_name']=$row['employee_name'];

}
if($salida['kind_employee']!='Administrative') {

    $campo_9 = "campo_9 = '".strtolower($salida['employee_name']."'");

}



            if($equidad==2)
            {
                $sql_1 = strtolower($_GET["campo_1"]);


                $campo_1 = "STR_TO_DATE(campo_1 , '%m/%d/%Y') > STR_TO_DATE('".$sql_1."','%m/%d/%Y')";

            }
if($equidad1==1)
{
    $sql_1 = strtolower($_GET["campo_90"]);


    $campo_90 ="STR_TO_DATE(campo_1 , '%m/%d/%Y') <= STR_TO_DATE('".$sql_1."','%m/%d/%Y')";

}






                 $joins = '';
            
            $where = ' WHERE '.$id_treatments." and ".$campo_1." and ".$campo_90." and ".$campo_2." and ".$campo_3." and ".$campo_4." and ".$campo_5." and ".$campo_6." and ".$campo_7." and ".$campo_8." and ".$campo_9." and ".$license_number." and ".$campo_10." and ".$campo_11." and ".$campo_12." and ".$campo_13." and ".$campo_14." and ".$campo_15." and ".$campo_16." and ".$campo_17." and ".$campo_18." and ".$campo_19." and ".$campo_20." and ".$campo_21." and ".$campo_22." and ".$campo_23." and ".$campo_24." and ".$pay." and ".$adults_progress_notes." and ".$pedriatics_progress_notes.'';

            $sql  = "SELECT * FROM tbl_treatments tbl_".$joins.$where;

            $resultado = ejecutar($sql,$conexion);


            $reporte = array();

            $i = 0;      
            while($datos = mysqli_fetch_assoc($resultado)) {            
                $reporte[$i] = $datos;
                $i++;
            }
                
                
?>
    <script type="text/javascript" language="javascript" src="../../../js/jquery.dataTables.js"></script>        
        
        <script  language="JavaScript" type="text/javascript">
                                      
                $(document).ready(function() {
                        $('#table_kidswork_therapy').dataTable( {


                            order: [[ 1, "asc" ]],


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


                                    
                function Modificar_treatments(id_treatments,campo_1,campo_2,campo_3,campo_4,campo_5,campo_6,campo_7,campo_8,campo_9,license_number,campo_10,campo_11,campo_12,campo_13,campo_14,campo_15,campo_16,campo_17,campo_18,campo_19,campo_20,campo_21,campo_22,campo_23,campo_24,pay,adults_progress_notes,pedriatics_progress_notes){
                        campo_1 = replaceAll(campo_1,' ','|');
                            campo_2 = replaceAll(campo_2,' ','|');
                            campo_3 = replaceAll(campo_3,' ','|');
                            campo_4 = replaceAll(campo_4,' ','|');
                            campo_5 = replaceAll(campo_5,' ','|');
                            campo_6 = replaceAll(campo_6,' ','|');
                            campo_7 = replaceAll(campo_7,' ','|');
                            campo_8 = replaceAll(campo_8,' ','|');
                            campo_9 = replaceAll(campo_9,' ','|');
                            license_number = replaceAll(license_number,' ','|');
                            campo_10 = replaceAll(campo_10,' ','|');
                            campo_11 = replaceAll(campo_11,' ','|');
                            campo_12 = replaceAll(campo_12,' ','|');
                            campo_13 = replaceAll(campo_13,' ','|');
                            campo_14 = replaceAll(campo_14,' ','|');
                            campo_15 = replaceAll(campo_15,' ','|');
                            campo_16 = replaceAll(campo_16,' ','|');
                            campo_17 = replaceAll(campo_17,' ','|');
                            campo_18 = replaceAll(campo_18,' ','|');
                            campo_19 = replaceAll(campo_19,' ','|');
                            campo_20 = replaceAll(campo_20,' ','|');
                            campo_21 = replaceAll(campo_21,' ','|');
                            campo_22 = replaceAll(campo_22,' ','|');
                            campo_23 = replaceAll(campo_23,' ','|');
                            campo_24 = replaceAll(campo_24,' ','|');
                            pay = replaceAll(pay,' ','|');
                            adults_progress_notes = replaceAll(adults_progress_notes,' ','|');
                            pedriatics_progress_notes = replaceAll(pedriatics_progress_notes,' ','|');
                            
                    
                window.location.href = "../treatments/registrar_treatments.php?&id_treatments="+id_treatments+"&campo_1="+campo_1+"&campo_2="+campo_2+"&campo_3="+campo_3+"&campo_4="+campo_4+"&campo_5="+campo_5+"&campo_6="+campo_6+"&campo_7="+campo_7+"&campo_8="+campo_8+"&campo_9="+campo_9+"&license_number="+license_number+"&campo_10="+campo_10+"&campo_11="+campo_11+"&campo_12="+campo_12+"&campo_13="+campo_13+"&campo_14="+campo_14+"&campo_15="+campo_15+"&campo_16="+campo_16+"&campo_17="+campo_17+"&campo_18="+campo_18+"&campo_19="+campo_19+"&campo_20="+campo_20+"&campo_21="+campo_21+"&campo_22="+campo_22+"&campo_23="+campo_23+"&campo_24="+campo_24+"&pay="+pay+"&adults_progress_notes="+adults_progress_notes+"&pedriatics_progress_notes="+pedriatics_progress_notes;   
             
                }
    

        function Eliminar_treatments(id_treatments,incrementador,accion){
                    
                                        swal({
          title: "Confirmación",
          text: "Desea Eliminar el Registro N° "+id_treatments+"?",
          type: "warning",
          showCancelButton: true,   
        confirmButtonColor: "#3085d6",   
        cancelButtonColor: "#d33",   
        confirmButtonText: "Eliminar",   
        closeOnConfirm: false,
        closeOnCancel: false
                }).then(function(isConfirm) {
                  if (isConfirm === true) {                                 
                
                   var campos_formulario = '&id_treatments='+id_treatments+'&accion='+accion;
                    
                        $.post(
                                "../../controlador/treatments/gestionar_treatments.php",
                                campos_formulario,
                                function (resultado_controlador) {
                                    //confirmacion_almacenamiento('Registro N° '+id_treatments+' Eliminado');
                                    $('#resultado_'+incrementador).html(resultado_controlador.mensaje_data_table);
                                    setTimeout(function(){
                                        validar_formulario();
                                    },1500);
                                },
                                "json" 
                                ); 
            
          }
        });
                    
                                                                            
                }
                    

/*modulos_dependientes_j*/


    
        </script>
        



        
<div class="col-lg-12">

                        <table id="table_kidswork_therapy" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>    
                        <th  >ID TREAT</th>
            <th>Treatments Day</th>
            <th>Patients</th>
            <th>Pat. #</th>
            <th>Therapist</th>
            <th>Type</th>
            <th>Proc. Code </th>
                            <th>Insu</th>
            <th>Dur</th>


            <th>Loc.</th>
                            <th>Diag</th>


            <th>Notes</th>
            <th>Sig</th>
                            <th>Inv</th>
            </tr>
                    </thead>                                      
                    
                    <tbody>
                        <?php
                        $i = 0;
                        $color = '<tr style="height=25px;" >';
                        while (isset($reporte[$i])) {





                            if($reporte[$i]['campo_4']=='' || $reporte[$i]['campo_20']=='NULL'){

                                $locations21='../../../images/treatment/ok.png';



                                $action='<td align="center"><img src='.$locations21.' height=25px width=25px onclick="aceptar('.$reporte[$i]['id_treatments'].');"> </img></td>';

                            }else{
                                $locations20='../../../images/treatment/edit.png';

                               $action='<td align="center"> <img src='.$locations20.' height=25px width=25px onclick="edit('.$reporte[$i]['id_treatments'].');"> </img></td>';

                            }


                            if($reporte[$i]['campo_20']=='ü' || $reporte[$i]['campo_20']=='NULL'){
                                $locations20='../../../images/sign/not_sign.jpg';

                            }else{
                                $locations20='../../../images/sign/sign.jpg';

                            }

                            if($reporte[$i]['campo_18']=='ü' || $reporte[$i]['campo_18']=='NULL'){
                                $locations18='../../../images/sign/fail.png';

                            }else{
                                $locations18='../../../images/sign/dinero.png';

                            }

                            if($reporte[$i]['campo_19']=='ü' || $reporte[$i]['campo_19']=='NULL'){
                                $locations19='../../../images/sign/fail.png';

                            }else{
                                $locations19='../../../images/sign/save_note1.jpg';

                            }
                          

                            
 echo '<td align="center"><b>'.$reporte[$i]['id_treatments'].'</b></font></td>';
             echo '<td align="center"><font size="2">'.$reporte[$i]['campo_1'].'</font></td>';
             echo '<td align="center"><font size="2">'.$reporte[$i]['campo_6'].'</font></td>';
             echo '<td align="center"><font size="2">'.$reporte[$i]['campo_5'].'</font></td>';
             echo '<td align="center"><font size="2">'.$reporte[$i]['campo_9'].'</font></td>';
             echo '<td align="center"><font size="2">'.$reporte[$i]['campo_10'].'</font></td>';
             echo '<td align="center"><font size="2">'.$reporte[$i]['campo_11'].'</font></td>';
             echo '<td align="center"><font size="2">'.$reporte[$i]['campo_7'].'</font></td>';
             echo '<td align="center"><font size="2">'.$reporte[$i]['campo_15'].'</font></td>';

             echo '<td align="center"><font size="2">'.$reporte[$i]['campo_2'].'</font></td>';
             echo '<td align="center"><font size="2">'.$reporte[$i]['campo_16'].'</font></td>';

             echo '<td align="center"><img src='.$locations19.' height=25px width=25px> </img></td>';
                            echo '<td align="center"><img src='.$locations20.' height=25px width=25px> </img></td>';

                            echo $action;
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


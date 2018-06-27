<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();



if($_POST['insertar']=='no' || $_POST['autorizacion']=='si'){



########esto aqui hay que cambiarlo pr lo que me da el post es patients id y las discipoina con numero y la consulta ee deb hacer con letra

    $info_pat="SELECT * FROM patients
                    WHERE  id =".$_POST['pat_id'];
    $info_pat_id = ejecutar($info_pat,$conexion);
    while ($row=mysqli_fetch_array($info_pat_id)) {
        $salida['Pat_id'] = $row['Pat_id'];
    }


    $info_disciplina="SELECT * FROM discipline
                    WHERE  DisciplineId =".$_POST['diciplina'];
    $info_disciplina1 = ejecutar($info_disciplina,$conexion);
    while ($row=mysqli_fetch_array($info_disciplina1)) {
        $salida2['Name'] = $row['Name'];
    }



    //consulto si el appoiments enviado tiene alguna evaluacion asociada
    $progres_sql="SELECT count(*) as contador,id_careplans,Patient_ID,Discipline FROM careplans 
    WHERE (deleted = 0 OR deleted IS NULL) and status=2  and Patient_ID=".$salida['Pat_id'];
    $active_progres = ejecutar($progres_sql,$conexion);
    $querry=$progres_sql;
    while ($row=mysqli_fetch_array($active_progres)){
        $valor_progres['contador'] = $row['contador'];
        $valor_progres['id'] = $row['id_careplans'];

    }

    if($valor_progres['contador']>0){
        $mensaje="<b>Are you sure you want to create This NOTE</b>";
        $success=true;
        $id=$valor_progres['id'];
    }else
    {
        $mensaje="<b>There is no ACTIVE  poc to assign this NOTE . Please Contact your Supervisor</b>";
        $success=false;
        $id='';
    }
    if($_POST['autorizacion']=='si'){ 
        $start=explode(' ',$_POST['star_time']);
        $end=explode(' ',$_POST['end_time']);
        
  ////       ////// PARA SABER LOS SEGUROS QUE NO NECESITAN AUTHORIZACION IGUAL LOS INCLUYA EN EL IF /////
            $consulta_if_need_auth_eval = " SELECT * 
                                FROM  patients
                                    JOIN tbl_patient_seguros s on s.patient_id=patients.id
                                        LEFT JOIN tbl_seguros_table st on s.insure_id=st.id_seguros
                                            AND st.id_seguros_type_person=patients.id_seguros_type_person
                                                AND st.id_seguros_type_task='4'
                                                    AND st.discipline='".$salida2['Name']."' 
                                            JOIN careplans c ON c.Patient_ID=patients.Pat_id
                                                AND c.Discipline='".$salida2['Name']."'
                                                AND c.Re_Eval_due>now()
                                WHERE patients.id='".$_POST['pat_id']."'
                                AND st.id_seguros_table is null  ";
            $result_consulta_if_need_auth_eval = ejecutar($consulta_if_need_auth_eval,$conexion);
            $row_cnt_need_eval = mysqli_num_rows($result_consulta_if_need_auth_eval);
  ///      /////////////////FIN DE QUERY PARA SABER LOS SEGUROS Q NO NECESITAN AUTHORIZACION////////////

        $sql_auth="SELECT IA.id 
                    FROM tbl_patient_seguros PS
                    LEFT JOIN patient_insurers_auths IA ON(IA.seguro_id=PS.id)
                    WHERE PS.patient_id='".$_POST['pat_id']."'
                    AND IA.discipline_id='".$_POST['diciplina']."' 
                    AND IA.cpt_type='TX' 
                    AND IA.start<='".$start[0]."'
                    AND IA.END>='".$end[0]."' 
                    AND PS.status=1 
                    AND IA.status=1";
        $resultado_autorizacion = ejecutar($sql_auth,$conexion);
        while($datos_aut = mysqli_fetch_assoc($resultado_autorizacion)){            
                $id_aut = $datos_aut['id'];
        }


        if($id_aut!=null || $row_cnt_need_eval>0){
            $sql_am='SELECT COUNT(*) as visit,P.amount,P.end FROM tbl_visits V LEFT JOIN
                    patient_insurers_auths P ON(P.id=V.auth_id) WHERE deleted!=1 AND P.id='.$id_aut;                            
              $sql_amount = ejecutar($sql_am,$conexion);
              while($datos_amont = mysqli_fetch_assoc($sql_amount)){            
                      $visit = $datos_amont['visit'];
                      $amount = $datos_amont['amount'];
                      $end_amount = $datos_amont['end'];
              }
            $total_amount=$amount-$visit;

             $sql_end=" SELECT  Re_Eval_Due FROM careplans WHERE deleted!=1 AND status=2
                             AND Discipline='".$_POST['diciplina']."'  AND Patient_ID='".$_POST['pat_id']."' ;";
           //die;
            $sql_end_poc = ejecutar($sql_end,$conexion);
            while($datos_end_poc = mysqli_fetch_assoc($sql_end_poc)){            
                    $end_poc = $datos_end_poc['Re_Eval_Due'];
            } 
            $query_disciplines = " SELECT Name  FROM discipline where DisciplineId='".$_POST['diciplina']."'   ;";
                    $result_discipline = ejecutar($query_disciplines,$conexion);                                       
                    while($datos_discipline = mysqli_fetch_assoc($result_discipline)) { 
                        $dis_name = $datos_discipline['Name'];
                    }
            $consulta_if_hold = " SELECT pc.id 
                                FROM  patients_copy pc
                                WHERE pc.id='".$_POST['pat_id']."' AND hold_".$dis_name."='1' ";
                $result_consulta_if_hold = ejecutar($consulta_if_hold,$conexion);
                $row_cnt_hold = mysqli_num_rows($result_consulta_if_hold);
                if($row_cnt_hold > 0){
            $mensajes="<b>¿Are you sure you want to create This NOTE?</b><br><br> <b>Patient: </b><span style='color:red'><b>- ON HOLD -</b></span><br>
                    <b>Remaining visits: </b><span style='color:red'><b>".$total_amount."</b></span><br><b>End AUTH: </b><span style='color:red'><b>".$end_amount."</b></span><br><b>End _POC: </b><span style='color:red'><b>".$end_poc."</b><span>";
                }else{
                $mensajes="<b>¿Are you sure you want to create This NOTE?</b><br><br> <b>Remaining visits: </b><span style='color:red'><b>".$total_amount."</b></span><br><b>End AUTH: </b><span style='color:red'><b>".$end_amount."</b></span><br><b>End _POC: </b><span style='color:red'><b>".$end_poc."</b><span>";
                }

            $succes=true;
            $aut=$id_aut;                
        }else{
            $mensajes="<b>There is no active Authorization, are you sure you want to continue</b>";
            $succes='no_exist';
            $aut=''; 
        }                        

        $arreglo=array('mensaje'=>$mensajes,'success'=>$succes,'id_aut'=>$aut,'id_poc'=>$id);
        echo json_encode($arreglo); 
    }else{
    
        $arreglo=array('mensaje'=>$mensaje,'success'=>$success, 'id_poc'=>$id,'query'=>$querry);
        echo json_encode($arreglo);
    }
}

if($_POST['insertar']=='si'){
    
    $insert_visit="INSERT INTO tbl_visits(pat_id,user_id,visit_date,visit_discip_id,id_visit_type,visit_loc_id,app_id,auth_id) "
                    . "VALUES('".$_POST['pat_id']."','".$_POST['therapista_id']."','".$_POST['star_time']."','".$_POST['diciplina']."','2','".$_POST['location']."','".$_POST['id_date']."','".$_POST['aut_id']."')";

    $resultado = ejecutar($insert_visit,$conexion);



    ////  update del reporte para poner scheduled a =0
    $sql_update="SELECT Name FROM discipline WHERE DisciplineId = '".$_POST['diciplina']."'   ;";
    $resultado_update = ejecutar($sql_update,$conexion); 
    while($datos_update = mysqli_fetch_assoc($resultado_update)) {            
        $dis_name = $datos_update['Name'];
    }
    $update_reportes = "UPDATE patients_copy SET scheduled_".$dis_name." = '0' WHERE id='".$_POST['pat_id']."' ;";
    $resultado_update_reporte = ejecutar($update_reportes,$conexion);
    //// FIN ----- update del reporte para poner scheduled a =0

    /////// REPORTE PARA PONER EN ON HOLD ///////////
    $sql_am_1="SELECT COUNT(*) as visit,P.amount,P.end, DATEDIFF(P.end,NOW()) as end_1 FROM tbl_visits V LEFT JOIN
                    patient_insurers_auths P ON(P.id=V.auth_id) WHERE deleted!=1 AND P.id='".$_POST['aut_id']."' ;   ";                          
              $sql_amount_1 = ejecutar($sql_am_1,$conexion);
              while($datos_amont_1 = mysqli_fetch_assoc($sql_amount_1)){            
                      $visit_1 = $datos_amont_1['visit'];
                      $amount_1 = $datos_amont_1['amount'];
                      $end_amount_1 = $datos_amont_1['end_1'];
              }
            $total_amount_1=$amount_1-$visit_1;

             $sql_end_1=" SELECT  DATEDIFF(Re_Eval_Due,NOW()) as days_diff FROM careplans WHERE deleted!=1 AND status=2
                             AND Discipline='".$_POST['diciplina']."'  AND Patient_ID='".$_POST['pat_id']."' ;";
           //die;
            $sql_end_poc_1 = ejecutar($sql_end_1,$conexion);
            while($datos_end_poc_1 = mysqli_fetch_assoc($sql_end_poc_1)){            
                    $end_poc_1 = $datos_end_poc_1['days_diff'];
            } 

        if($total_amount_1<1 || $end_poc_1<1 || $end_amount_1<1 ){
            $update_reporte_1 = "UPDATE patients_copy SET hold_".$dis_name." = '1' where  id='".$_POST['pat_id']."'  ;";
    $resultado_update_reporte_1 = ejecutar($update_reporte_1,$conexion);
        }

//////// FIN REPORTE DE PONER EN ON HOLD //////////////////////


/////////// ///////////////////////////    /////// REPORTE PARA poner en --> PEDIR ATUHOTIZACION PARA TRATAMIENTO TX  ///////////
////       ////// PARA SABER LOS SEGUROS QUE NO NECESITAN AUTHORIZACION IGUAL LOS INCLUYA EN EL IF /////
            $consulta_if_need_auth_eval = " SELECT * 
                                FROM  patients
                                    JOIN tbl_patient_seguros s on s.patient_id=patients.id
                                        JOIN tbl_seguros_table st on s.insure_id=st.id_seguros
                                            AND st.id_seguros_type_person=patients.id_seguros_type_person
                                                AND st.id_seguros_type_task='4'
                                                    AND st.discipline='".$_POST['diciplina']."'
                                        JOIN tbl_seguros_auth_treat_auth_days tad ON st.id_seguros_table=tad.id_seguros_table
                                        JOIN tbl_seguros_auth_treat_visit_remain tvr ON st.id_seguros_table=tvr.id_seguros_table 
                                        JOIN careplans c on c.Patient_ID=patients.id 
                                            AND c.status=2
                                            AND c.Discipline='".$_POST['diciplina']."'

                                            and DATEDIFF(c.Re_Eval_due,now())>15  ########################### ESTE NUMERO ES EL DE NUMERO DE DIAS DEL POC 
                                            
                                WHERE patients.id='".$_POST['pat_id']."'
                                 AND  ('".$total_amount_1."' <= tvr.visit_remain
                     OR '".$end_amount_1."' <= tad.auth_days_left   )  ";
            $result_consulta_if_need_auth_eval = ejecutar($consulta_if_need_auth_eval,$conexion);
            $row_cnt_need_eval = mysqli_num_rows($result_consulta_if_need_auth_eval);
  ///      /////////////////FIN DE QUERY PARA SABER LOS SEGUROS Q NO NECESITAN AUTHORIZACION////////////
            if($row_cnt_need_eval > 0 ){
                $update_reporte_2 = "UPDATE patients_copy SET tx_auth_".$dis_name." = '1', waiting_tx_auth_".$dis_name."='0' WHERE  id='".$_POST['pat_id']."'  ;";
                $resultado_update_reporte_2 = ejecutar($update_reporte_2,$conexion);
                ejecutar($resultado_update_reporte_2,$conexion);

            }


 ///////////////////// //////////////  //////// FIN REPORTE DE  poner en --> PEDIR ATUHOTIZACION PARA TRATAMIENTO TX /////////////////////

    //consulto el ultimo id de la tabla visitas
    $last_visit  = "SELECT max(id) as identificador FROM tbl_visits;";
    $resultado_visit = ejecutar($last_visit,$conexion); 

    while($datos = mysqli_fetch_assoc($resultado_visit)) {            
        $id_visit = $datos['identificador'];

    }
    //consulto todo desde careplans en base al id 
    
    $sql_plans="SELECT * FROM careplans WHERE  (deleted = 0 OR deleted IS NULL) and  id_careplans=".$_POST['id_poc'];
    $resultado_care_plans = ejecutar($sql_plans,$conexion); 

    while($datos = mysqli_fetch_assoc($resultado_care_plans)) {            
        $care_plans[] = $datos;

    }
    //fecha
    $fecha=date('Y-m-d H:i:s');
    //inserto en tbl_note_documentation
    
    $insertar_nota="INSERT INTO tbl_notes_documentation(discipline_id,id_careplans,created,user_id,visit_id,patient_name,patient_id,visit_date)"
            . "     VALUES('".$care_plans[0]['Discipline']."','".$_POST['id_poc']."','".$fecha."','".$_POST['therapista_id']."','".$id_visit."','".$care_plans[0]['First_name'].",".$care_plans[0]['Last_name']."','".$care_plans[0]['Patient_ID']."','".$_POST['star_time']."')";
    $resultado_nota = ejecutar($insertar_nota,$conexion);
    
    
    if($resultado_nota){
            
        $last_note  = "SELECT max(id_notes) as identificador FROM tbl_notes_documentation;";
        $resultado_note = ejecutar($last_note,$conexion); 

        while($datos_note = mysqli_fetch_assoc($resultado_note)) {            
            $id_note = $datos_note['identificador'];

        }
        $mensaje="<b>Information was loaded correctly</b>";
        $success=true;
    }else{
        $mensaje="<b>An error has occurred, please try again</b>";
        $success=false;
        $id_note='';
    }
    $arreglo=array('mensaje'=>$mensaje,'success'=>$success, 'id_note'=>$id_note);
    echo json_encode($arreglo);
    
}



<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}
$conexion = conectar();
//consulto si el appoiments enviado tiene alguna evaluacion asociada

$appoiment="SELECT COUNT(*) as contador,E.id FROM tbl_visits V LEFT JOIN tbl_evaluations E ON (E.visit_id=V.id)
            WHERE (E.deleted = 0 OR E.deleted IS NULL) AND  V.app_id=".$_POST['id_date'];

$active_appoiment = ejecutar($appoiment,$conexion);
while ($row_appoiment=mysqli_fetch_array($active_appoiment)) {
    $valor_appoiment['contador'] = $row_appoiment['contador'];
    $valor_appoiment['id'] = $row_appoiment['id'];
}

if($valor_appoiment['contador']>0){
    
    $mensaje="<b>This appointment already have an associated evaluation</b>";
    $success='reenvio';
    $id_appoiment=$_POST['id_date'];
    $pat_id=$_POST['pat_id'];
    $discipline=$_POST['diciplina'];
    $id=$valor_appoiment['id'];
  
    
    $arreglo=array('mensaje'=>$mensaje,'success'=>$success,'id_appoiment'=>$id_appoiment,'pat_id'=>$pat_id,'disciplina'=>$discipline,'id'=>$id);
    echo json_encode($arreglo); 
    
}else{

        if($_POST['insertar']=='no') {


            /*EVALUALITON Los Eval son hijos del RX Prescription . Osea que se tienen que guardar debajo
             * de alguna RX prescription. Para saber con que RX prescription relacionar esta EVAL*/

            //Paso 1 Primero consultar si existe alguna  RX prescription con status 2  -(ACTIVE )

            $contador_sql = "SELECT count(*) as contador,P.id_prescription,P.Table_name as company,PA.Pat_id,C.company_id,
C.company_name,C.facility_phone,concat(D.DiagCodeValue,', ',D.DiagCodeDescrip) as diagnostic_relation,D.DiagCodeId 
FROM prescription P"
                . " LEFT JOIN patients PA ON(P.Patient_ID=PA.id) LEFT JOIN companies C ON(P.Table_name=C.company_name)"
                . " LEFT JOIN diagnosiscodes D ON(P.Diagnostic=D.DiagCodeId) 
                        WHERE  (P.deleted = 0 OR P.deleted IS NULL)
                        AND P.status=2 AND P.Patient_ID=" . $_POST['pat_id'] . " AND P.Discipline=" . $_POST['diciplina'];
            $active = ejecutar($contador_sql, $conexion);
            while ($row = mysqli_fetch_array($active)) {
                $valor_contador['contador'] = $row['contador'];
                $valor_contador['id_prescription'] = $row['id_prescription'];
                $valor_contador['diagnostic_relation'] = $row['diagnostic_relation'];
                $valor_contador['diagnostic_id'] = $row['DiagCodeId'];
                $valor_contador['Pat_id'] = $row['Pat_id'];
                $valor_contador['company_id'] = $row['company_id'];
                $valor_contador['company_name'] = $row['company_name'];
                $valor_contador['facility_phone'] = $row['facility_phone'];
            }

            if ($valor_contador['contador'] > 0) {

                //paso 2 Consultar si esa RX Prescription  tiene alguna Evaluation asociada  ,TABLA tbl_evaluations
                $eval_sql = "SELECT count(*) as contador FROM tbl_evaluations e 
                    WHERE  (e.deleted = 0 OR e.deleted IS NULL) and e.id_prescription=" . $valor_contador['id_prescription'];
                $active_eval = ejecutar($eval_sql, $conexion);
                while ($row = mysqli_fetch_array($active_eval)) {
                    $valor_eval['contador'] = $row['contador'];
                }

                if ($valor_eval['contador'] > 0) {

                    //consultar si hay alguna  RX prescription IN PROGRESS  (status=1)
                    $progres_sql = "SELECT count(*) as contador,P.id_prescription,P.Table_name as company,PA.Pat_id,C.company_id,
C.company_name,C.facility_phone,concat(D.DiagCodeValue,', ',D.DiagCodeDescrip) as diagnostic_relation,D.DiagCodeId 
FROM prescription P"
                        . " LEFT JOIN patients PA ON(P.Patient_ID=PA.Pat_id) LEFT JOIN companies C ON(P.Table_name=C.company_name)"
                        . " LEFT JOIN diagnosiscodes D ON(P.Diagnostic=D.DiagCodeId) 
                        WHERE (P.deleted = 0 OR P.deleted IS NULL)
                        and P.status=1 AND P.Patient_ID=" . $_POST['pat_id'] . " AND P.Discipline=" . $_POST['diciplina'];
                    $active_progres = ejecutar($progres_sql, $conexion);
                    while ($row = mysqli_fetch_array($active_progres)) {
                        $valor_progres['contador'] = $row['contador'];
                        $valor_progres['id_prescription'] = $row['id_prescription'];
                        $valor_progres['diagnostic_relation'] = $row['diagnostic_relation'];
                        $valor_progres['diagnostic_id'] = $row['DiagCodeId'];
                        $valor_progres['Pat_id'] = $row['Pat_id'];
                        $valor_progres['company_id'] = $row['company_id'];
                        $valor_progres['company_name'] = $row['company_name'];
                        $valor_progres['facility_phone'] = $row['facility_phone'];
                    }

                    if ($valor_progres['contador'] > 0) {
                        //Consultar si esa RX Prescriptio  tiene alguna Evaluation  asociada   , TABLA tbl_evaluations
                        $eval_sql = "SELECT count(*) as contador FROM tbl_evaluations e 
                            WHERE  (e.deleted = 0 OR e.deleted IS NULL) and e.id_prescription=" . $valor_progres['id_prescription'];
                        $active_eval = ejecutar($eval_sql, $conexion);
                        while ($row = mysqli_fetch_array($active_eval)) {
                            $valor_eval_det['contador'] = $row['contador'];
                        }



                            $mensaje = "<b>¿Are you sure you want to create This EVAL?</b>";
                            $success = true;
                            $diagnostic = $valor_progres['diagnostic_relation'];
                            $diagnostic_id = $valor_progres['diagnostic_id'];
                            $id_preescription = $valor_progres['id_prescription'];
                            $patient_id = $valor_progres['Pat_id'];
                            $company_id = $valor_progres['company_id'];
                            $company_name = $valor_progres['company_name'];
                            $facility_phone = $valor_progres['facility_phone'];


                    }
                } else {
                    //paso 7 Crear la Visita en la tabla nueva

                    $mensaje = "<b>¿Are you sure you want to create This EVAL?</b>";
                    $success = true;
                    $diagnostic = $valor_contador['diagnostic_relation'];
                    $diagnostic_id = $valor_contador['diagnostic_id'];
                    $id_preescription = $valor_contador['id_prescription'];
                    $patient_id = $valor_contador['Pat_id'];
                    $company_id = $valor_contador['company_id'];
                    $company_name = $valor_contador['company_name'];
                    $facility_phone = $valor_contador['facility_phone'];

                }

            }
            $arreglo=array('mensaje'=>$mensajes,'success'=>true, 'id_aut'=>$aut,'id_preescription'=>$id_preescription,'diagnostic'=>$diagnostic,'patient_id'=>$patient_id,'company_id'=>$company_id,'company_name'=>$company_name,'facility_phone'=>$facility_phone,'diagnostic_id'=>$diagnostic_id);
            echo json_encode($arreglo);

        }
            if($_POST['autorizacion']=='si')
            {

                        $start=explode(' ',$_POST['star_time']);
                        $end=explode(' ',$_POST['end_time']);
                        $sql_auth="SELECT IA.id FROM tbl_patient_seguros PS
                                    LEFT JOIN patient_insurers_auths IA ON(IA.seguro_id=PS.id)
                                    WHERE PS.patient_id=".$_POST['pat_id']." 
                                    AND IA.discipline_id=".$_POST['diciplina']." 
                                    AND IA.cpt_type='EVAL' 
                                    AND IA.start<='".$start[0]."'
                                    AND IA.END>='".$end[0]."' 
                                    AND PS.status=1 
                                    AND IA.status=1";
                        $resultado_autorizacion = ejecutar($sql_auth,$conexion);
                        while($datos_aut = mysqli_fetch_assoc($resultado_autorizacion)){            
                                $id_aut = $datos_aut['id'];
                        }

                        if($id_aut!=null){
                            $sql_am="SELECT COUNT(*) as visit,P.amount,P.end FROM tbl_visits V LEFT JOIN
                                  patient_insurers_auths P ON(P.id=V.auth_id) WHERE V.deleted!=1 AND P.id='".$id_aut."' ; ";                           
                            $sql_amount = ejecutar($sql_am,$conexion);
                            while($datos_amont = mysqli_fetch_assoc($sql_amount)){            
                                    $visit = $datos_amont['visit'];
                                    $amount = $datos_amont['amount'];
                                    $end_amount = $datos_amont['end'];
                            }
                            $total_amount=$amount-$visit;

                             $sql_end="SELECT Re_Eval_Due FROM careplans WHERE deleted!=1 AND Patient_ID='".$_POST['pat_id']."'
                            AND status=2 AND Discipline=".$_POST['diciplina'];
                           // die;
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
                            $mensajes="<b>¿Are you sure you want to create This EVAL?</b><br><br>  <b>Patient: </b><span style='color:red'><b>- ON HOLD -</b></span> <br>  <b>Remaining Visits: </b><span style='color:red'><b>".$total_amount."</b></span><br><b>End AUTH: </b><span style='color:red'><b>".$end_amount."</b></span><br>";
                        }else {
                            $mensajes="<b>¿Are you sure you want to create This EVAL?</b><br><br> <b>Remaining Visits: </b><span style='color:red'><b>".$total_amount."</b></span><br><b>End AUTH: </b><span style='color:red'><b>".$end_amount."</b></span><br>";
                        }
                            $succes=true;
                            $aut=$id_aut;                
                        }else{
                            $mensajes="<b>There is no active Authorization, are you sure you want to continue</b>";
                            $succes='no_exist';
                            $aut=''; 
                        }

                $arreglo=array('mensaje'=>$mensaje,'success'=>$success,'id_preescription'=>$id_preescription,'diagnostic'=>$diagnostic,'patient_id'=>$patient_id,'company_id'=>$company_id,'company_name'=>$company_name,'facility_phone'=>$facility_phone,'diagnostic_id'=>$diagnostic_id);
                echo json_encode('hola');
            }



        if($_POST['insertar']=='si'){

            //paso 7

            $insert_visit="INSERT INTO tbl_visits(pat_id,user_id,visit_date,visit_discip_id,id_visit_type,visit_loc_id,app_id,auth_id) "
                    . "VALUES('".$_POST['pat_id']."','".$_POST['therapista_id']."','".$_POST['star_time']."','".$_POST['diciplina']."','1','".$_POST['location']."','".$_POST['id_date']."','".$_POST['aut_id']."')";

            $resultado = ejecutar($insert_visit,$conexion);

            //consulto el ultimo id de la tabla visitas
            $last_visit  = "SELECT max(id) as identificador FROM tbl_visits;";
            $resultado_visit = ejecutar($last_visit,$conexion); 

            while($datos = mysqli_fetch_assoc($resultado_visit)) {            
                $id_visit = $datos['identificador'];

            }

            /////////seccion para adicionar la fecha de la evaluacion
            ///
            ///
            $salida_date['start']='';
            $info_appoinment="SELECT * FROM calendar_appoiment_date
                    WHERE  id =".$_POST['id_date'];
            $info_pat_id = ejecutar($info_appoinment,$conexion);
            while ($row=mysqli_fetch_array($info_pat_id)) {
                $salida_date['start'] = $row['start'];
                $salida_date['end'] = $row['end'];
            }

            if($salida_date['start']==''){
                $info_appoinment="SELECT * FROM calendar_appointments
                    WHERE  id =".$_POST['id_calendar'];
                $info_pat_id = ejecutar($info_appoinment1,$conexion);
                while ($row=mysqli_fetch_array($info_pat_id)) {
                    $salida_date['start'] = $row['date'];
                    $salida_date['end'] = $row['date'];
                }


            }


            $start_evaluations=date("m/d/Y",strtotime($salida_date['start']));
            $stop_evaluations=date("m/d/Y",strtotime($salida_date['end']));
            ///
            ///



            //inserto la evaluacion            
            $insert_eval="INSERT INTO tbl_evaluations(`from`,`to`,`name`,discipline_id,id_user,created,diagnostic,id_prescription,patient_id,company,visit_id)"
                    . "VALUES('".$salida_date['start']."','".$salida_date['end']."','".$_POST['name_patients']."','".$_POST['diciplina']."','".$_POST['therapista_id']."','".$_POST['star_time']."','".$_POST['diagnostic_id']."','".$_POST['id_preescription']."','".$_POST['pat_id']."','".$_POST['company_id']."','".$id_visit."')";


            ///////////////aqui debo poner la parte de los reportes
            ///
            ///
            ///
            ///


            $insertar_dat=ejecutar($insert_eval,$conexion); 
            if($insertar_dat){
                $mensaje="<b>Information was loaded correctly</b>";
                $success=true;

                //consulto el ultimo id de la tabla evaluaciones
                $last_eval  = "SELECT max(id) as identificador FROM tbl_evaluations;";
                $resultado_eval = ejecutar($last_eval,$conexion); 

                while($datos_eval = mysqli_fetch_assoc($resultado_eval)){            
                    $id_eval = $datos_eval['identificador'];
                }

            }else{
                $mensaje="<b>An error has occurred, please try again</b>";
                $success=false;
            }
            $arreglo=array('mensaje'=>$mensaje,'success'=>$success, 'id_eval'=>$id_eval);
            echo json_encode($arreglo);


        }       
        
}
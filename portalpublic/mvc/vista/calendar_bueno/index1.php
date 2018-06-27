<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location=../../../"index.php";</script>';
}

$conexion = conectar();
$sql2  = "SELECT first_name,last_name,permission_calendar FROM employee WHERE id=".$_SESSION['user_id'];
$resultado1 = ejecutar($sql2,$conexion);      
while($datos = mysqli_fetch_assoc($resultado1)){            
    $respuesta['permission_calendar'] = $datos['permission_calendar'];
    $respuesta['first_name'] = $datos['first_name'];
    $respuesta['last_name'] = $datos['last_name'];
}
if($respuesta !=null){    
    $permisos=explode(',',$respuesta['permission_calendar']);
}else{
    //SIN PERMISOS
    $permisos='S/P';
}
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset='utf-8' />
        <link href="../../../plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
        <link href='../../../plugins/fullcalendar/fullcalendar.min.css' rel='stylesheet' />
        <link href='../../../plugins/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
        <link href="../../../plugins/select2/select2.css" rel="stylesheet">        
        <link href="../../../plugins/fullcalendar/jquery.bootstrapvalidator/dist/css/bootstrapValidator.min.css" rel="stylesheet" />
        <link href="../../../plugins/fullcalendar/css/bootstrap-colorpicker.min.css" rel="stylesheet" />
        <link href="../../../plugins/fullcalendar/css/bootstrap-timepicker.min.css" rel="stylesheet" />  
        <link rel="stylesheet" type="text/css" href="../../../css/bootstrap-multiselect.css">
        <script src='../../../plugins/fullcalendar/js/moment.min.js'></script>
        <script src="../../../plugins/fullcalendar/js/jquery.min.js"></script>
        <script src="../../../plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="../../../js/funciones.js" type="text/javascript"></script>   
        <script type="text/javascript" src="../../../plugins/fullcalendar/jquery.bootstrapvalidator/dist/js/bootstrapValidator.min.js"></script>
        <script src="../../../plugins/fullcalendar/js/fullcalendar.min.js"></script>
        <script src='../../../plugins/fullcalendar/js/bootstrap-colorpicker.min.js'></script>
        <script src='../../../plugins/fullcalendar/js/bootstrap-timepicker.min.js'></script>
        <script src='../../../plugins/fullcalendar/js/main.js'></script>
        <script src='../../../plugins/fullcalendar/js/bootbox.min.js'></script>
        <script src="../../../plugins/select2/select2.js"></script>   
        <script type="text/javascript" src="../../../plugins/datepiker/jquery.simple-dtpicker.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/bootstrap-multiselect.js"></script>
	<link type="text/css" href="../../../plugins/datepiker/jquery.simple-dtpicker.css" rel="stylesheet" />
        
        <style>
            body {
                margin: 40px 10px;
                padding: 0;
                font-family: "Lucida Grande", Helvetica, Arial, Verdana, sans-serif;
                font-size: 14px;
            }
            .fc th {
                padding: 10px 0px;
                vertical-align: middle;
                background:#F2F2F2;
            }
            .fc-day-grid-event>.fc-content {
                padding: 4px;
            }
            #calendario {
                max-width: 900px;
                margin: 0 auto;
            }
            .error {
                color: #ac2925;
                margin-bottom: 15px;
            }
            .event-tooltip {
                width:550px;
                background: rgba(0, 0, 0, 0.85);
                color:#FFF;
                padding:10px;
                position:absolute;
                z-index:10001;
                -webkit-border-radius: 4px;
                -moz-border-radius: 4px;
                border-radius: 4px;
                cursor: pointer;
                font-size: 11px;
            }
        </style>
    </head>
    <body>
<!-- Navigation -->
    <?php $perfil = $_SESSION['user_type']; include "../nav_bar/nav_bar.php"; ?>
    <hr>

    <?PHP if($permisos!='S/P' && $permisos[0]!='view_none'){?>
       
        <!--permisos para editar-->
        <?php            
            if($permisos[1]=='edit_none'){
        ?>
            <input type="hidden" name="edit_none" id="edit_none" value="si"/>
        <?php } ?>
            
        <?php
            if($permisos[1]=='edit_own'){
        ?>
            <input type="hidden" name="edit_own" id="edit_own" value="si"/>
            <input type="hidden" name="edit_usuario" id="edit_usuario" value="<?=$_SESSION['user_id']?>"/>
        <?php } ?>
            
        <?php
            if($permisos[1]=='edit_all'){
        ?>
            <input type="hidden" name="edit_all" id="edit_all" value="si"/>
        <?php } ?>
       
        <div class="row">
            <div class="col-md-4">              
                       <b>Discipline</b>
                            <?php if($permisos[0]=='view_own'){?>
                            <select style="width:1250px" name='discipline[]' id='discipline' class="multiple form-control" multiple disabled="">                				

                                <option value=""></option>

                            </select>
                            <?php }else{?>
                            <select style="width:1250px" name='discipline[]' id='discipline' class="multiple form-control" multiple onchange="search_users();">                                				
                                <option value="office">Office</option>
                                        <?php 
                                            $sql  = "Select * from discipline"; 
                                            $conexion = conectar(); 
                                            $resultado = ejecutar($sql,$conexion); 
                                            while ($row=mysqli_fetch_array($resultado))  
                                            {                                             
                                                print("<option value='".$row["DisciplineId"]."'>".$row["Name"]." </option>"); 
                                            }       

                                        ?> 
                            </select>
                            <?php } ?>
                            <?php if($permisos[0]=='view_own'){?>
                            <b>Users</b>
                            <select style="width:1250px" name='users[]' id='users' class="multiple form-control" multiple>                				

                                <option value="<?=$_SESSION['user_id']?>"><?php echo $respuesta['first_name']." ".$respuesta['last_name'] ?></option>

                            </select>
                            <?php }else{?>
                                <div id='users_dinamicos'></div>


                            <?php } ?>

                            <b>Patients</b>                                      
                            <select style="width:250px" name='patients_search[]' id='patients_search' class="multiple form-control" multiple>

                                <?php
                                    $sql  = "Select Distinct Table_name, id, concat(Last_name,', ',First_name) as Patient_name from patients order by Patient_name ";
                                    $conexion = conectar();
                                    $resultado = ejecutar($sql,$conexion);
                                    while ($row=mysqli_fetch_array($resultado)) 
                                    {	

                                        print("<option value='".$row["id"]."'>".$row["Patient_name"] .$row["Table_name"]    ." </option>");
                                    }

                                ?>

                            </select> 
            </div>
            <div class="col-md-8">
                <div id='calendario'></div>

            </div>
        </div>
        <div class="modal fade" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <!--<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>-->
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="error"></div>
                        <form class="form-horizontal" id="crud-form">
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="title">Type</label>
                                <div class="col-md-4">
                                    <select id="type" name="type" class="populate placeholder">
                                        <option value="">Seleccione</option>                                        
                                        <?php
                                            $sql  = "SELECT * FROM tbl_type_appoiments";
                                            $conexion = conectar();
                                            $resultado = ejecutar($sql,$conexion);
                                            while ($row=mysqli_fetch_array($resultado)) 
                                            {	

                                                print("<option value='".$row["id"]."'>".$row["name"]." </option>");
                                               
                                            }
                                            //guardar en un campo oculto el valor asociado al location
                                            $resultados = ejecutar($sql,$conexion);
                                            while ($rows=mysqli_fetch_array($resultados)) 
                                            {	
                                        ?>
                                            <input type="hidden" id="location_type_id<?=$rows["id"]?>" value="<?=$rows["location_id"]?>"/>
                                            <?php } ?>
                                        
                                    </select>
                                </div>
                            </div>
                             <div class="form-group">			
                                
                                    <label class="col-md-4 control-label" for="title">Patients</label>
                                    <div class="input-group">                                        
                                        <select style="width:250px" name='patients' id='patients' class="populate placeholder">
                                                <option value=''>--- SELECT ---</option>				
                                            <?php
                                                $sql  = "Select Distinct Table_name, id, concat(Last_name,', ',First_name) as Patient_name from patients order by Patient_name ";
                                                $conexion = conectar();
                                                $resultado = ejecutar($sql,$conexion);
                                                while ($row=mysqli_fetch_array($resultado)) 
                                                {	
                                                        
                                                    print("<option value='".$row["id"]."'>".$row["Patient_name"] .$row["Table_name"]    ." </option>");
                                                }

                                            ?>

                                        </select>                                   
                                </div>
                            </div>
                            <div class="form-group">                               
                                    <label class="col-md-4 control-label" for="title">User</label>
                                    <div class="input-group"> 
                                        <?php if($permisos[0]=='view_own'){?>
                                                <select style="width:250px;" name='therapist' id='therapist' class="populate placeholder">
                                                    <option value=''>--- SELECT ---</option>
                                                    <option value="<?=$_SESSION['user_id']?>"><?php echo $respuesta['first_name']." ".$respuesta['last_name'] ?></option>
                                                </select>
                                                <input type="hidden" name="own" id="own" value="si"/>
                                                <input type="hidden" name="own_user" id="own_user" value="<?=$_SESSION['user_id']?>"/>
                                            <?php }else{?>
                                                <select style="width:250px;" name='therapist' id='therapist' class="populate placeholder">
                                                    <option value=''>--- SELECT ---</option>                 
                                                    <?php 
                                                    $sql  = "Select id,concat(Last_name,', ',First_name) as employee from employee order by id"; 
                                                    $conexion = conectar(); 
                                                    $resultado = ejecutar($sql,$conexion); 
                                                    while ($row=mysqli_fetch_array($resultado))  
                                                    {                                             
                                                        print("<option value='".$row["id"]."'>".$row["employee"]." </option>"); 
                                                    }       

                                                    ?> 
                                                </select>                                                 
                                            <?php } ?>
                                    </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="description">Subject</label>
                                <div class="col-md-4">
                                    <input id="subject" name="subject" type="text" class="form-control input-md" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="title">Location</label>
                                <div class="col-md-4">
                                 <select id="location" name="location" class="populate placeholder">
                                     <option value="">Seleccione</option>
                                     <?php
                                            $sql  = "SELECT * FROM tbl_location_appoiments";
                                            $conexion = conectar();
                                            $resultado = ejecutar($sql,$conexion);
                                            while ($row=mysqli_fetch_array($resultado)) 
                                            {	

                                                print("<option value='".$row["id"]."'>".$row["name"]." </option>");
                                            }

                                        ?>
                                 </select>
                                </div>
                            </div>
                            <hr>
                            <!--time-recurring-->
                            <div align="center">
                                <input name="tiempo" id="time_view" type="radio" />Time
                                <input name="tiempo" id="recurring" type="radio"/>Recurring
                            </div>
                            <hr>
                            <div style="display:none" id="hide_time">
                                <div class="panel panel-default">
                                    <div class="panel-heading">TIME</div>
                                    <div class="panel-body">                                       
                                      <div id="collapse1" class="panel-collapse collapse in">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="time">Start Time</label>
                                            <div class="col-md-4 input-append bootstrap-timepicker">
                                                <input id="star_time" name="star_time" type="text" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label" for="time">End Time</label>
                                            <div class="col-md-4 input-append bootstrap-timepicker">
                                                <input id="end_time" name="end_time" type="text" class="form-control" />
                                            </div>
                                        </div>
                                      </div>  
                                    </div>
                                </div>
                            </div>
                            <div style="display:none" id="hide_recurring">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                      <h4 class="panel-title">
                                        Recurring every
                                      </h4>
                                    </div>
                                    <div id="collapse2" class="panel-collapse collapse in">
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <table class="table" border="0">
                                                    <tr>
                                                        <td>
                                                            <div>
                                                        <input type="checkbox" id="mon" name="mon"/> 
                                                            Mon
                                                            <b>From</b> &nbsp <select id="mon_hour" disabled="">
                                                                <option value="01">01</option>
                                                                <option value="02">02</option>
                                                                <option value="03">03</option>
                                                                <option value="04">04</option>
                                                                <option value="05">05</option>
                                                                <option value="06">06</option>
                                                                <option value="07">07</option>
                                                                <option value="08">08</option>
                                                                <option value="09">09</option>
                                                                <option value="10">10</option>
                                                                <option value="11">11</option>
                                                                <option value="12">12</option>
                                                            </select>:
                                                            <select id="mon_minute" disabled="">
                                                                <option value="00">00</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                                <option value="60">60</option>                                                                
                                                            </select>:
                                                            <select id="mon_type" disabled="">
                                                                <option value="am">AM</option>
                                                                <option value="pm">PM</option>                                                                                                                                
                                                            </select>
                                                            </div>
                                                            <div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                            <b>To</b>&nbsp&nbsp&nbsp<select id="mon_hour_to" disabled="">
                                                                <option value="01">01</option>
                                                                <option value="02">02</option>
                                                                <option value="03">03</option>
                                                                <option value="04">04</option>
                                                                <option value="05">05</option>
                                                                <option value="06">06</option>
                                                                <option value="07">07</option>
                                                                <option value="08">08</option>
                                                                <option value="09">09</option>
                                                                <option value="10">10</option>
                                                                <option value="11">11</option>
                                                                <option value="12">12</option>
                                                           </select>:
                                                            <select id="mon_minute_to" disabled="">
                                                                <option value="00">00</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                                <option value="60">60</option>                                                                
                                                            </select>:
                                                            <select id="mon_type_to" disabled="">
                                                                <option value="am">AM</option>
                                                                <option value="pm">PM</option>                                                                                                                                
                                                            </select>
                                                            </div>
                                                        </td>
                                                        <td>For the next<input id="next" name="next" type="number" maxlength="3" class="form-control" size="3"/></td>
                                                        
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                        <div>
                                                        <input type="checkbox" id="tue" name="tue"/> 
                                                            Tue
                                                           &nbsp <b>From</b>&nbsp&nbsp<select id="tue_hour" disabled="">
                                                                <option value="01">01</option>
                                                                <option value="02">02</option>
                                                                <option value="03">03</option>
                                                                <option value="04">04</option>
                                                                <option value="05">05</option>
                                                                <option value="06">06</option>
                                                                <option value="07">07</option>
                                                                <option value="08">08</option>
                                                                <option value="09">09</option>
                                                                <option value="10">10</option>
                                                                <option value="11">11</option>
                                                                <option value="12">12</option>
                                                            </select>:
                                                            <select id="tue_minute" disabled="">
                                                                <option value="00">00</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                                <option value="60">60</option>                                                                
                                                            </select>:
                                                            <select id="tue_type" disabled="">
                                                                <option value="am">AM</option>
                                                                <option value="pm">PM</option>                                                                                                                                
                                                            </select>
                                                            </div>
                                                            <div>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                                            <b>To</b>&nbsp&nbsp<select id="tue_hour_to" disabled="">
                                                                <option value="01">01</option>
                                                                <option value="02">02</option>
                                                                <option value="03">03</option>
                                                                <option value="04">04</option>
                                                                <option value="05">05</option>
                                                                <option value="06">06</option>
                                                                <option value="07">07</option>
                                                                <option value="08">08</option>
                                                                <option value="09">09</option>
                                                                <option value="10">10</option>
                                                                <option value="11">11</option>
                                                                <option value="12">12</option>
                                                            </select>:
                                                            <select id="tue_minute_to" disabled="">
                                                                <option value="00">00</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                                <option value="60">60</option>                                                                
                                                            </select>:
                                                            <select id="tue_type_to" disabled="">
                                                                <option value="am">AM</option>
                                                                <option value="pm">PM</option>                                                                                                                                
                                                            </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <select id="count_time" class="form-control">
                                                                <option value="" selected=""></option>
                                                                <option value="weeks" selected="">Weeks</option>
                                                                <option value="biweekly">Biweekly</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" id="wed" name="wed"/> Wed
                                                            <b>From</b>&nbsp<select id="wed_hour" disabled="">
                                                                <option value="01">01</option>
                                                                <option value="02">02</option>
                                                                <option value="03">03</option>
                                                                <option value="04">04</option>
                                                                <option value="05">05</option>
                                                                <option value="06">06</option>
                                                                <option value="07">07</option>
                                                                <option value="08">08</option>
                                                                <option value="09">09</option>
                                                                <option value="10">10</option>
                                                                <option value="11">11</option>
                                                                <option value="12">12</option>
                                                            </select>:
                                                            <select id="wed_minute" disabled="">
                                                                <option value="00">00</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                                <option value="60">60</option>                                                                
                                                            </select>:
                                                            <select id="wed_type" disabled="">
                                                                <option value="am">AM</option>
                                                                <option value="pm">PM</option>                                                                                                                                
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <b>To</b>&nbsp<select id="wed_hour_to" disabled="">
                                                                <option value="01">01</option>
                                                                <option value="02">02</option>
                                                                <option value="03">03</option>
                                                                <option value="04">04</option>
                                                                <option value="05">05</option>
                                                                <option value="06">06</option>
                                                                <option value="07">07</option>
                                                                <option value="08">08</option>
                                                                <option value="09">09</option>
                                                                <option value="10">10</option>
                                                                <option value="11">11</option>
                                                                <option value="12">12</option>
                                                            </select>:
                                                            <select id="wed_minute_to" disabled="">
                                                                <option value="00">00</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                                <option value="60">60</option>                                                                
                                                            </select>:
                                                            <select id="wed_type_to" disabled="">
                                                                <option value="am">AM</option>
                                                                <option value="pm">PM</option>                                                                                                                                
                                                            </select>
                                                         </td>
                                                         
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" id="thu" name="thu"/> Thu
                                                            <b>From</b>&nbsp<select id="thu_hour" disabled="">
                                                                <option value="01">01</option>
                                                                <option value="02">02</option>
                                                                <option value="03">03</option>
                                                                <option value="04">04</option>
                                                                <option value="05">05</option>
                                                                <option value="06">06</option>
                                                                <option value="07">07</option>
                                                                <option value="08">08</option>
                                                                <option value="09">09</option>
                                                                <option value="10">10</option>
                                                                <option value="11">11</option>
                                                                <option value="12">12</option>
                                                            </select>:
                                                            <select id="thu_minute" disabled="">
                                                                <option value="00">00</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                                <option value="60">60</option>                                                                
                                                            </select>:
                                                            <select id="thu_type" disabled="">
                                                                <option value="am">AM</option>
                                                                <option value="pm">PM</option>                                                                                                                                
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <b>To</b>&nbsp<select id="thu_hour_to" disabled="">
                                                                <option value="01">01</option>
                                                                <option value="02">02</option>
                                                                <option value="03">03</option>
                                                                <option value="04">04</option>
                                                                <option value="05">05</option>
                                                                <option value="06">06</option>
                                                                <option value="07">07</option>
                                                                <option value="08">08</option>
                                                                <option value="09">09</option>
                                                                <option value="10">10</option>
                                                                <option value="11">11</option>
                                                                <option value="12">12</option>
                                                            </select>:
                                                            <select id="thu_minute_to" disabled="">
                                                                <option value="00">00</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                                <option value="60">60</option>                                                                
                                                            </select>:
                                                            <select id="thu_type_to" disabled="">
                                                                <option value="am">AM</option>
                                                                <option value="pm">PM</option>                                                                                                                                
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" id="fri" name="fri"/> Fri
                                                            <b>From</b>&nbsp<select id="fri_hour" disabled="">
                                                                <option value="01">01</option>
                                                                <option value="02">02</option>
                                                                <option value="03">03</option>
                                                                <option value="04">04</option>
                                                                <option value="05">05</option>
                                                                <option value="06">06</option>
                                                                <option value="07">07</option>
                                                                <option value="08">08</option>
                                                                <option value="09">09</option>
                                                                <option value="10">10</option>
                                                                <option value="11">11</option>
                                                                <option value="12">12</option>
                                                            </select>:
                                                            <select id="fri_minute" disabled="">
                                                                <option value="00">00</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                                <option value="60">60</option>                                                                
                                                            </select>:
                                                            <select id="fri_type" disabled="">
                                                                <option value="am">AM</option>
                                                                <option value="pm">PM</option>                                                                                                                                
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <b>To</b>&nbsp<select id="fri_hour_to" disabled="">
                                                                <option value="01">01</option>
                                                                <option value="02">02</option>
                                                                <option value="03">03</option>
                                                                <option value="04">04</option>
                                                                <option value="05">05</option>
                                                                <option value="06">06</option>
                                                                <option value="07">07</option>
                                                                <option value="08">08</option>
                                                                <option value="09">09</option>
                                                                <option value="10">10</option>
                                                                <option value="11">11</option>
                                                                <option value="12">12</option>
                                                            </select>:
                                                            <select id="fri_minute_to" disabled="">
                                                                <option value="00">00</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                                <option value="60">60</option>                                                                
                                                            </select>:
                                                            <select id="fri_type_to" disabled="">
                                                                <option value="am">AM</option>
                                                                <option value="pm">PM</option>                                                                                                                                
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" id="sat" name="sat"/> Sat
                                                            <b>From</b>&nbsp<select id="sat_hour" disabled="">
                                                                <option value="01">01</option>
                                                                <option value="02">02</option>
                                                                <option value="03">03</option>
                                                                <option value="04">04</option>
                                                                <option value="05">05</option>
                                                                <option value="06">06</option>
                                                                <option value="07">07</option>
                                                                <option value="08">08</option>
                                                                <option value="09">09</option>
                                                                <option value="10">10</option>
                                                                <option value="11">11</option>
                                                                <option value="12">12</option>
                                                            </select>:
                                                            <select id="sat_minute" disabled="">
                                                                <option value="00">00</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                                <option value="60">60</option>                                                                
                                                            </select>:
                                                            <select id="sat_type" disabled="">
                                                                <option value="am">AM</option>
                                                                <option value="pm">PM</option>                                                                                                                                
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <b>To</b>&nbsp<select id="sat_hour_to" disabled="">
                                                                <option value="01">01</option>
                                                                <option value="02">02</option>
                                                                <option value="03">03</option>
                                                                <option value="04">04</option>
                                                                <option value="05">05</option>
                                                                <option value="06">06</option>
                                                                <option value="07">07</option>
                                                                <option value="08">08</option>
                                                                <option value="09">09</option>
                                                                <option value="10">10</option>
                                                                <option value="11">11</option>
                                                                <option value="12">12</option>
                                                            </select>:
                                                            <select id="sat_minute_to" disabled="">
                                                                <option value="00">00</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                                <option value="60">60</option>                                                                
                                                            </select>:
                                                            <select id="sat_type_to" disabled="">
                                                                <option value="am">AM</option>
                                                                <option value="pm">PM</option>                                                                                                                                
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" id="sun" name="sun"/> Sun 
                                                            <b>From</b>&nbsp<select id="sun_hour" disabled="">
                                                                <option value="01">01</option>
                                                                <option value="02">02</option>
                                                                <option value="03">03</option>
                                                                <option value="04">04</option>
                                                                <option value="05">05</option>
                                                                <option value="06">06</option>
                                                                <option value="07">07</option>
                                                                <option value="08">08</option>
                                                                <option value="09">09</option>
                                                                <option value="10">10</option>
                                                                <option value="11">11</option>
                                                                <option value="12">12</option>
                                                            </select>:
                                                            <select id="sun_minute" disabled="">
                                                                <option value="00">00</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                                <option value="60">60</option>                                                                
                                                            </select>:
                                                            <select id="sun_type" disabled="">
                                                                <option value="am">AM</option>
                                                                <option value="pm">PM</option>                                                                                                                                
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <b>To</b>&nbsp<select id="sun_hour_to" disabled="">
                                                                <option value="01">01</option>
                                                                <option value="02">02</option>
                                                                <option value="03">03</option>
                                                                <option value="04">04</option>
                                                                <option value="05">05</option>
                                                                <option value="06">06</option>
                                                                <option value="07">07</option>
                                                                <option value="08">08</option>
                                                                <option value="09">09</option>
                                                                <option value="10">10</option>
                                                                <option value="11">11</option>
                                                                <option value="12">12</option>
                                                            </select>:
                                                            <select id="sun_minute_to" disabled="">
                                                                <option value="00">00</option>
                                                                <option value="15">15</option>
                                                                <option value="30">30</option>
                                                                <option value="45">45</option>
                                                                <option value="60">60</option>                                                                
                                                            </select>:
                                                            <select id="sun_type_to" disabled="">
                                                                <option value="am">AM</option>
                                                                <option value="pm">PM</option>                                                                                                                                
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </table>
                                           
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>                            
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="title">Attendance Status</label>
                                <div class="col-md-4">
                                 <select id="attendance" name="attendance" class="populate placeholder">
                                     <option value="">-None-</option>
                                     <?php
                                            $sql  = "SELECT * FROM tbl_status_appoiments";
                                            $conexion = conectar();
                                            $resultado = ejecutar($sql,$conexion);
                                            while ($row=mysqli_fetch_array($resultado)) 
                                            {	

                                                print("<option value='".$row["id"]."'>".$row["name"]." </option>");
                                            }

                                        ?>
                                 </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4 input-append bootstrap-timepicker">
                                <input id="time" name="time" type="hidden" class="form-control input-md" />
                            </div>
                            <input type="hidden" id="id_calendar" value=""/>
                            <input type="hidden" id="id_date" value=""/>
                        </form>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" onclick="cancelar();">Cancelar</button>
                    </div>
               
            </div>
        </div>
        
   
 
    <?PHP }else{ ?>
                <h2><span style="color: #ac2925"><div align="center">You do not have permission to view this page.</div></span></h2> <br>
                <div align="center"><img src="../../../img/candado.jpg"></div><br>
                <h2><span style="color: #ac2925"><div align="center">Please contact your system administrator.</div></span></h2> <br>
    <?PHP } ?>
    </body>
   
    <script type="text/javascript">
            function cancelar(){
                $('.modal').modal('hide');
                $('#calendario').fullCalendar("refetchEvents");
                //location.reload();
            } 
            
            function send_update_time(){
                
                if($("#terapistas").is(":checked")){                    
                    var sms_terapista='si';
                }else{
                    sms_terapista='no';
                }
                if($("#pacientes").is(":checked")){                    
                    var sms_paciente='si';
                }else{
                    sms_paciente='no';
                }
                
                bootbox.confirm({
                        message: '<h3><b>¿Be sure to modify the appointments?</b></h3>',
                        buttons: {
                            confirm: {
                                label: 'Accept',
                                className: 'btn-success'
                            },
                            cancel: {
                                label: 'Cancel',
                                className: 'btn-danger'
                            }
                        },
                        callback: function (result) {
                            if(result===true){
                                    $.post('../../../mvc/controlador/calendar/update/update_appoiments.php', {
                                    id_date: $('#id_date').val(),
                                    id_calendar: $('#id_calendar').val(),
                                    pat_id: $('#patients').val(),
                                    therapista_id:$('#therapist').val(),
                                    type:$('#type').val(),
                                    subject:$('#subject').val(),
                                    location: $('#location').val(),                                                    
                                    star_time: $('#star_time').val(),
                                    end_time: $('#end_time').val(),                                        
                                    attendance:$('#attendance').val(),                                            
                                    conflicto: 'no',
                                    type_appoiments:1,
                                    sms_terapista:sms_terapista,
                                    sms_paciente:sms_paciente,
                                    //date: currentEvent.date.split(' ')[0]  + ' ' +  getTime()
                                }, function(success){
                                    if(success){
                                        bootbox.alert("Data has been updated correctly");                                        
                                        $('.modal').modal('hide');
                                        $('#patients').val('');
                                        $('#type').val('');
                                        $('#therapist').val('');
                                        $('#subject').val('');
                                        $('#location').val('');                                                    
                                        $('#attendance').val('');
                                        $("#select2-chosen-1").html('Seleccione');
                                        $("#select2-chosen-2").html('---SELECT---');
                                        $("#select2-chosen-3").html('');
                                        $("#select2-chosen-4").html('---SELECT---');
                                        $("#select2-chosen-5").html('-None-');
                                        $('#calendario').fullCalendar("refetchEvents");
                                        //location.reload();

                                    }else{
                                        bootbox.alert("An error has occurred, please try again");
                                    }

                                });
                            }
                        }
                });
                
            }
            function is(hour_mon,hour_mon_to,hour_tue,hour_tue_to,hour_wed,hour_wed_to,hour_thu,hour_thu_to,hour_fri,hour_fri_to,hour_sat,hour_sat_to,hour_sun,hour_sun_to,next,count_time,data){
                
                bootbox.alert("<div align='center'><b><h3>Do you want to send a message to the therapist <input type='checkbox' name='terapistas' id='terapistas'> or the patient <input type='checkbox' name='pacientes' id='pacientes'>?</h3></b></div><br><div align='center'><button onclick='send_update(\""+hour_mon+"\",\""+hour_mon_to+"\",\""+hour_tue+"\",\""+hour_tue_to+"\",\""+hour_wed+"\",\""+hour_wed_to+"\",\""+hour_thu+"\",\""+hour_thu_to+"\",\""+hour_fri+"\",\""+hour_fri_to+"\",\""+hour_sat+"\",\""+hour_sat_to+"\",\""+hour_sun+"\",\""+hour_sun_to+"\",\""+next+"\",\""+count_time+"\",\"is\");' class='btn btn-success'>Update</button></div>");
            }
            function send_update(hour_mon,hour_mon_to,hour_tue,hour_tue_to,hour_wed,hour_wed_to,hour_thu,hour_thu_to,hour_fri,hour_fri_to,hour_sat,hour_sat_to,hour_sun,hour_sun_to,next,count_time,data){
                
                if($("#terapistas").is(":checked")){                    
                    var sms_terapista='si';
                }else{
                    sms_terapista='no';
                }
                if($("#pacientes").is(":checked")){                    
                    var sms_paciente='si';
                }else{
                    sms_paciente='no';
                }
                
                
                bootbox.confirm({
                    message: '<h3><b>¿Be sure to modify the appointments?</b></h3>',
                    buttons: {
                        confirm: {
                            label: 'Accept',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'Cancel',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result===true){
                                $.post('../../../mvc/controlador/calendar/update/update_appoiments.php', {
                                id_date: $('#id_date').val(),
                                id_calendar: $('#id_calendar').val(),
                                pat_id: $('#patients').val(),
                                therapista_id:$('#therapist').val(),
                                type:$('#type').val(),
                                subject:$('#subject').val(),
                                location: $('#location').val(),                                                    
                                star_time: $('#star_time').val(),
                                end_time: $('#end_time').val(),                                        
                                attendance:$('#attendance').val(),                                            
                                conflicto: 'no',
                                type_appoiments:2,
                                hour_mon:hour_mon,
                                hour_mon_to:hour_mon_to,
                                hour_tue:hour_tue,
                                hour_tue_to:hour_tue_to,
                                hour_wed:hour_wed,
                                hour_wed_to:hour_wed_to,
                                hour_thu:hour_thu,
                                hour_thu_to:hour_thu_to,
                                hour_fri:hour_fri,
                                hour_fri_to:hour_fri_to,
                                hour_sat:hour_sat,
                                hour_sat_to:hour_sat_to,
                                hour_sun:hour_sun,
                                hour_sun_to:hour_sun_to,
                                quantity:next,
                                period:count_time,
                                cuantos: data,
                                sms_terapista:sms_terapista,
                                sms_paciente:sms_paciente,
                                
                                //date: currentEvent.date.split(' ')[0]  + ' ' +  getTime()
                            }, function(success){
                                if(success){
                                    bootbox.alert("Data has been updated correctly");                                    
                                    $('.modal').modal('hide');
                                    $('#patients').val('');
                                    $('#type').val('');
                                    $('#therapist').val('');
                                    $('#subject').val('');
                                    $('#location').val('');                                                    
                                    $('#attendance').val('');                                                    
                                    $("#select2-chosen-1").html('Seleccione');
                                    $("#select2-chosen-2").html('---SELECT---');
                                    $("#select2-chosen-3").html('');
                                    $("#select2-chosen-4").html('---SELECT---');
                                    $("#select2-chosen-5").html('-None-');
                                    $('#calendario').fullCalendar("refetchEvents");
                                    //location.reload();

                                }else{
                                    bootbox.alert("An error has occurred, please try again");
                                }

                            });
                        }
                    }
                });
            }
            
            function delete_recurring(data){
                
                bootbox.confirm({
                        message: '<h3><b>¿Are you sure to continue??</b></h3>',
                        buttons: {
                            confirm: {
                                label: 'Accept',
                                className: 'btn-success'
                            },
                            cancel: {
                                label: 'Cancel',
                                className: 'btn-danger'
                            }
                        },
                        callback: function (result) {
                            if(result===true){

                                $.get('../../../mvc/controlador/calendar/delete/delete_appoiments.php?cuantos='+data+'&type_appoiments=2&id_date='+$('#id_date').val()+'&id_calendar=' + $('#id_calendar').val(), function(success){
                                    if(success){
                                        bootbox.alert("Data has been deleted correctly");                    
                                        location.reload();
                                    }else{
                                        bootbox.alert("An error has occurred, please try again");
                                    }
                                });
                            }
                        }
                });
                
            }
            
            
            
            function conflictos(pat_id,therapist_id,subject,location,date,recurring,next_weeks,biweekly,type,start,end,attendance){
                bootbox.confirm({
                    message: '<h3><b>¿Are you sure to continue?</b></h3>',
                    buttons: {
                        confirm: {
                            label: 'Accept',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'Cancel',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if(result===true){
                            
                            $.ajax({
                                url: '../../../mvc/controlador/calendar/add/registrar_appoiments.php',
                                data: {
                                    pat_id: pat_id,
                                    therapista_id: therapist_id,
                                    type: type,
                                    subject: subject,
                                    location: location,
                                    recurring: recurring,
                                    next_weeks: next_weeks,
                                    biweekly: biweekly,
                                    star_time: start,
                                    end_time: end,                                        
                                    attendance: attendance,
                                    date: date,
                                    conflicto: 'si'
                                },
                                type: 'POST',
                                async:false,
                                dataType: "json",
                                success: function(data){
                                    if(data.success){
                                        bootbox.alert("Data has been loaded correctly");                                        
                                        $('.modal').modal('hide');
                                        $('#patients').val('');
                                        $('#type').val('');
                                        $('#therapist').val('');
                                        $('#subject').val('');
                                        $('#location').val('');
                                        $('#next_weeks').val('');
                                        $('#biweekly').val('');
                                        $('#attendance').val('');
                                        $('#color').val('');                                        
                                        $("#select2-chosen-1").html('Seleccione');
                                        $("#select2-chosen-2").html('---SELECT---');
                                        $("#select2-chosen-3").html('');
                                        $("#select2-chosen-4").html('---SELECT---');
                                        $("#select2-chosen-5").html('-None-');
                                        $('#calendario').fullCalendar("refetchEvents");
                                        location.reload();
                                    }else{                                
                                        bootbox.alert("An error has occurred, please try again");
                                    }
                                }

                            });
                        }
                    }
                });
                
            }
            $(document).ready(function() {
                iniciar_users();
                $('#color_h').hide();
                $('#type').select2();
                $('#patients').select2();
                $('#therapist').select2();
                $('#location').select2();
                $('#attendance').select2();
                
                $(".multiple").multiselect({
                    buttonWidth: '100%',
                    enableCaseInsensitiveFiltering:true,
                    includeSelectAllOption: true,
                    maxHeight:400
                });
                
                $(function(){
			$('#star_time').appendDtpicker({"inline": false});
                        $('#end_time').appendDtpicker({"inline": false});
		});
                
                $(function(){
                    var currentDate;
                    var currentEvent; 
                    $('#color').colorpicker(); // Colopicker
                    $('#time').timepicker({
                        minuteStep: 5,
                        showInputs: false,
                        disableFocus: true,
                        showMeridian: false
                    });  
                    $('#calendario').fullCalendar({
                        //timeFormat: 'H(:mm)',
                        header: {
                            left: 'prev, next, today',
                            center: 'title',
                            right: 'month,agendaWeek,agendaDay'
                        },                        
                        defaultView: 'agendaWeek', 
                        allDay:true,
                        editable: true, 
                        minTime: '07:00:00',
                        maxTime: '20:00:00', 
                        eventLimit: true,
                        views: {
                            month: {
                              eventLimit: 1
                            },
                            agenda: {
                              eventLimit: 1
                            }
                            
                        },
                        eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc){
                            var fecha_nueva_start=event.start.format();
                            var fecha_nueva_end=event.end.format();
                            var id_date=event.id_date;
                            
                            bootbox.confirm({
                                    message: '<h3><b>¿You are sure to change this appointment for the following date <span style="color:red">'+fecha_nueva_start+'</span>?</b></h3>',
                                    buttons: {
                                        confirm: {
                                            label: 'Accept',
                                            className: 'btn-success'
                                        },
                                        cancel: {
                                            label: 'Cancel',
                                            className: 'btn-danger'
                                        }
                                    },
                                    callback: function (result) {
                                        if(result===true){
                                            $.ajax({
                                                url: '../../../mvc/controlador/calendar/update/drag_and_drop/update.php',
                                                data: {
                                                    id_date: id_date,
                                                    star_time: fecha_nueva_start,
                                                    end_time: fecha_nueva_end,                                                    
                                                },
                                                type: 'POST',
                                                async:false,
                                                dataType: "json",
                                                success: function(data){
                                                    if(data.success){
                                                        bootbox.alert("Data has been Updated correctly");                          
                                                        $('#calendario').fullCalendar("refetchEvents");                                                        
                                                    }else{                                
                                                        bootbox.alert("An error has occurred, please try again");
                                                    }
                                                }

                                            });
                                        }else{
                                            $('.modal').modal('hide');
                                            $('#calendario').fullCalendar("refetchEvents");
                                            //location.reload();
                                        }
                                    }
                                });
                        },
                        eventResize: function(event, delta, revertFunc){
                        
                            var fecha_nueva_start=event.start.format();
                            var fecha_nueva_end=event.end.format();
                            var id_date=event.id_date;                            
                            bootbox.confirm({
                                    message: '<h3><b>¿You are sure to change this appointment for the following date <span style="color:red">'+fecha_nueva_end+'</span>?</b></h3>',
                                    buttons: {
                                        confirm: {
                                            label: 'Accept',
                                            className: 'btn-success'
                                        },
                                        cancel: {
                                            label: 'Cancel',
                                            className: 'btn-danger'
                                        }
                                    },
                                    callback: function (result) {
                                        if(result===true){
                                            $.ajax({
                                                url: '../../../mvc/controlador/calendar/update/drag_and_drop/update.php',
                                                data: {
                                                    id_date: id_date,
                                                    star_time: fecha_nueva_start,
                                                    end_time: fecha_nueva_end,                                                    
                                                },
                                                type: 'POST',
                                                async:false,
                                                dataType: "json",
                                                success: function(data){
                                                    if(data.success){
                                                        bootbox.alert("Data has been Updated correctly");                          
                                                        $('#calendario').fullCalendar("refetchEvents");                                                        
                                                    }else{                                
                                                        bootbox.alert("An error has occurred, please try again");
                                                    }
                                                }

                                            });
                                        }else{
                                            $('.modal').modal('hide');
                                            $('#calendario').fullCalendar("refetchEvents");
                                            //location.reload();
                                        }
                                    }
                            });                            

                        },
                        selectable: true,
                        allDaySlot: true,                        
                        events: {
                            url: "../../../mvc/controlador/calendar/consult/listar_appoiments.php",
                            data: function() {
                                return {
                                    users: $("#users").val(),
                                    patients: $("#patients_search").val(),                                    
                                    own:$("#own").val(),
                                    usuario:$("#own_user").val(),
                                };
                            },
                            type: "GET",
                            
                        },
                        eventRender: function(event, element){ 
                            element.find('.fc-time').remove(); 
                            element.find('.fc-title').append("<br/>" + event.inicio).append("<br/>" + event.fin); 
                        },
                        // Handle Day Click
                        dayClick: function(date, event, view) {                            
                            currentDate = date.format();
                            // Open modal to add event                           
                            if($("#edit_none").val()==='si'){                                
                                $("#calenadrio").modal('hide');
                            }else{                                
                                modal({
                                    // Available buttons when adding
                                    buttons: {
                                        add: {
                                            id: 'agregar_evento', 
                                            css: 'btn-success', 
                                            label: 'Add Appointments' 
                                        }
                                    },
                                    title: 'Add Appointments (' + date.format() + ')'
                                },date);
                            }
                        },
                        
                        eventMouseover: function(calEvent, jsEvent, view){
                            if(calEvent.dia_completo ===undefined){                               
                                var tooltip = '<div class="event-tooltip"> <span style="color:#119add">Patients:</span> ' + calEvent.title+'<br><span style="color:#119add">Therapist:</span> ' + calEvent.employee+'<br><span style="color:#119add">Location:</span> ' + calEvent.locations+'<br><span style="color:#119add">Start Time:</span> ' + calEvent.inicio+'<br><span style="color:#119add">End Time:</span> ' + calEvent.fin+'<br><hr>\n\</div>';
                            
                            $("body").append(tooltip);
                            $(this).mouseover(function(e) {
                                $(this).css('z-index', 10000);
                                $('.event-tooltip').fadeIn('500');
                                $('.event-tooltip').fadeTo('10', 1.9);
                            }).mousemove(function(e) {
                                    $('.event-tooltip').css('top', e.pageY + 10);
                                    $('.event-tooltip').css('left', e.pageX + 20);
                                });
                            }
                        },
                        eventMouseout: function(calEvent, jsEvent) {
                            $(this).css('z-index', 8);
                            $('.event-tooltip').remove();
                        },
                        eventClick: function(calEvent, jsEvent, view) {
                            currentEvent = calEvent;                            
                            var date=new Date();
                            if($("#edit_none").val()==='si'){                                
                                $("#calenadrio").modal('hide');
                            }else if($("#edit_own").val()==='si' && calEvent.therapist_id !==$("#edit_usuario").val()){
                                $("#calenadrio").modal('hide');
                            }else{                                
                                modal({
                                    buttons: {
                                        delete: {
                                            id: 'delete-event',
                                            css: 'btn-danger',
                                            label: 'Delete'
                                        },
                                        update: {
                                            id: 'update-event',
                                            css: 'btn-success',
                                            label: 'Update'
                                        }
                                    },
                                    title: 'Edit Appoiments for :"' + calEvent.title + '"',
                                    event: calEvent
                                },date);  
                            }
                        }
                    });
                    function modal(data,date) { 
                        
                        if($("#edit_none").val()==='si'){    
                           
                            $("#agregar_evento").attr('disabled', 'disabled');
                        }
                        $('.modal-title').html(data.title);
                        $('.modal-footer button:not(".btn-default")').remove();
                        $('#title').val(data.event ? data.event.title : '');
                        if( ! data.event) {                            
                            var now = new Date();
                            var time = now.getHours() + ':' + (now.getMinutes() < 10 ? '0' + now.getMinutes() : now.getMinutes());
                        } else {                            
                            var time = data.event.date.split(' ')[1].slice(0, -3);
                            time = time.charAt(0) === '0' ? time.slice(1) : time;
                        }
                        $('#type').val(data.event ? data.event.type : '');                        
                        if(data.event) {
                            var type_text=$("#type option:selected" ).text();
                            $("#select2-chosen-1").html(data.event ? type_text : '');
                        }                        
                        $('#patients').val(data.event ? data.event.Pat_id : '');
                        if(data.event) {
                            var patient_text=$("#patients option:selected" ).text();
                            $("#select2-chosen-2").html(data.event ? patient_text : '');
                        }
                        if(data.event) {
                            if(data.event.type_appoiment==='time'){                                
                                $("#hide_time").show();     
                                $("#hide_recurring").hide();
                                $("#time_view").attr('checked', 'true');
                                $("#recurring").attr('disabled', 'disabled');
                            }
                            if(data.event.type_appoiment==='recurring'){                                
                                $("#hide_time").hide();     
                                $("#hide_recurring").show();
                                $("#time_view").attr('disabled', 'disabled');
                                $("#count_time").attr('disabled', 'disabled');
                                $("#next").attr('disabled', 'disabled');
                                $("#recurring").attr('checked', 'true');
                            }
                            //lunes recurring
                            if(data.event.monday!==' ' && data.event.monday!==null){                                 
                                var res = data.event.monday.split(" ");
                                var res1=res[0].split(":");
                                var res1_to=res[2].split(":");
                                $("#mon").attr("checked",true);                                
                                $("#mon_hour").val(res1[0]);
                                $("#mon_minute").val(res1[1]);
                                $("#mon_hour_to").val(res1_to[0]);
                                $("#mon_minute_to").val(res1_to[1]);                                
                                $("#mon_type").val(res[1]);
                                $("#mon_type_to").val(res[3]);
                                
                                //deshabilito
                                $("#mon_hour").removeAttr("disabled");
                                $("#mon_minute").removeAttr("disabled");
                                $("#mon_type").removeAttr("disabled");
                                $("#mon_hour_to").removeAttr("disabled");
                                $("#mon_minute_to").removeAttr("disabled");
                                $("#mon_type_to").removeAttr("disabled");
                            }
                            
                            //martes recurring
                            if(data.event.tuesday!==' ' && data.event.tuesday!==null){ 
                               
                                var res = data.event.tuesday.split(" ");
                                var res1=res[0].split(":");
                                var res1_to=res[2].split(":");                                
                                $("#tue").attr("checked",true);                                
                                $("#tue_hour").val(res1[0]);
                                $("#tue_minute").val(res1[1]);
                                $("#tue_hour_to").val(res1_to[0]);
                                $("#tue_minute_to").val(res1_to[1]);                                
                                $("#tue_type").val(res[1]);
                                $("#tue_type_to").val(res[3]);
                                
                                
                                //deshabilito
                                $("#tue_hour").removeAttr("disabled");
                                $("#tue_minute").removeAttr("disabled");
                                $("#tue_type").removeAttr("disabled");
                                $("#tue_hour_to").removeAttr("disabled");
                                $("#tue_minute_to").removeAttr("disabled");
                                $("#tue_type_to").removeAttr("disabled");
                            }
                           
                            //miercoles recurring
                            if(data.event.wednesday!==' ' && data.event.wednesday!==null){    
                                var res = data.event.wednesday.split(" ");
                                var res1=res[0].split(":");
                                var res1_to=res[2].split(":");                                
                                $("#wed").attr("checked",true); 
                                $("#wed_hour").val(res1[0]);
                                $("#wed_minute").val(res1[1]);
                                $("#wed_hour_to").val(res1_to[0]);
                                $("#wed_minute_to").val(res1_to[1]);                                
                                $("#wed_type").val(res[1]);
                                $("#wed_type_to").val(res[3]);
                                
                                
                                //deshabilito
                                $("#wed_hour").removeAttr("disabled");
                                $("#wed_minute").removeAttr("disabled");
                                $("#wed_type").removeAttr("disabled");
                                $("#wed_hour_to").removeAttr("disabled");
                                $("#wed_minute_to").removeAttr("disabled");
                                $("#wed_type_to").removeAttr("disabled");
                            }
                            
                            //jueves recurring
                            if(data.event.thursday!==' ' && data.event.thursday!==null){    
                                var res = data.event.thursday.split(" ");
                                var res1=res[0].split(":");
                                var res1_to=res[2].split(":");                                
                                $("#thu").attr("checked",true); 
                                $("#thu_hour").val(res1[0]);
                                $("#thu_minute").val(res1[1]);
                                $("#thu_hour_to").val(res1_to[0]);
                                $("#thu_minute_to").val(res1_to[1]);                                
                                $("#thu_type").val(res[1]);
                                $("#thu_type_to").val(res[3]);
                                
                                
                                //deshabilito
                                $("#thu_hour").removeAttr("disabled");
                                $("#thu_minute").removeAttr("disabled");
                                $("#thu_type").removeAttr("disabled");
                                $("#thu_hour_to").removeAttr("disabled");
                                $("#thu_minute_to").removeAttr("disabled");
                                $("#thu_type_to").removeAttr("disabled");
                            }
                            //viernes recurring
                            if(data.event.friday!==' ' && data.event.friday!==null){                              
                                var res = data.event.friday.split(" ");
                                var res1=res[0].split(":");
                                var res1_to=res[2].split(":");                                
                                $("#fri").attr("checked",true); 
                                $("#fri_hour").val(res1[0]);
                                $("#fri_minute").val(res1[1]);
                                $("#fri_hour_to").val(res1_to[0]);
                                $("#fri_minute_to").val(res1_to[1]);                                
                                $("#fri_type").val(res[1]);
                                $("#fri_type_to").val(res[3]);
                                
                                
                                //deshabilito
                                $("#fri_hour").removeAttr("disabled");
                                $("#fri_minute").removeAttr("disabled");
                                $("#fri_type").removeAttr("disabled");
                                $("#fri_hour_to").removeAttr("disabled");
                                $("#fri_minute_to").removeAttr("disabled");
                                $("#fri_type_to").removeAttr("disabled");
                            }
                            
                            //sabado recurring
                            if(data.event.saturday!==' ' && data.event.saturday!==null){    
                                var res = data.event.saturday.split(" ");
                                var res1=res[0].split(":");
                                var res1_to=res[2].split(":");                                
                                $("#sat").attr("checked",true); 
                                $("#sat_hour").val(res1[0]);
                                $("#sat_minute").val(res1[1]);
                                $("#sat_hour_to").val(res1_to[0]);
                                $("#sat_minute_to").val(res1_to[1]);                                
                                $("#sat_type").val(res[1]);
                                $("#sat_type_to").val(res[3]);
                                
                                
                                //deshabilito
                                $("#sat_hour").removeAttr("disabled");
                                $("#sat_minute").removeAttr("disabled");
                                $("#sat_type").removeAttr("disabled");
                                $("#sat_hour_to").removeAttr("disabled");
                                $("#sat_minute_to").removeAttr("disabled");
                                $("#sat_type_to").removeAttr("disabled");
                            }
                            
                            //domingo recurring
                            if(data.event.sunday!==' ' && data.event.sunday!==null){                                
                                var res = data.event.sunday.split(" ");
                                var res1=res[0].split(":");
                                var res1_to=res[2].split(":");                                
                                $("#sun").attr("checked",true); 
                                $("#sun_hour").val(res1[0]);
                                $("#sun_minute").val(res1[1]);
                                $("#sun_hour_to").val(res1_to[0]);
                                $("#sun_minute_to").val(res1_to[1]);                                
                                $("#sun_type").val(res[1]);
                                $("#sun_type_to").val(res[3]);
                                
                                //deshabilito
                                $("#sun_hour").removeAttr("disabled");
                                $("#sun_minute").removeAttr("disabled");
                                $("#sun_type").removeAttr("disabled");
                                $("#sun_hour_to").removeAttr("disabled");
                                $("#sun_minute_to").removeAttr("disabled");
                                $("#sun_type_to").removeAttr("disabled");
                            }
                            
                        }
                        $("#next").val(data.event ? data.event.quantity : '');
                        $("#count_time").val(data.event ? data.event.period : '');
                        $('#therapist').val(data.event ? data.event.therapist_id : '');
                        if(data.event) {
                            var therapist_text=$("#therapist option:selected" ).text();
                            $("#select2-chosen-3").html(data.event ? therapist_text : '');
                        }
                        $('#time').val(time);
                        $('#subject').val(data.event ? data.event.subject : '');
                        $('#location').val(data.event ? data.event.location : '');
                        if(data.event) {
                            var location_text=$("#location option:selected" ).text();
                            $("#select2-chosen-4").html(data.event ? location_text : '');
                        }
                       
                        if(data.event) {
                            $('#star_time').val(data.event ? data.event.inicio : '');
                            $('#end_time').val(data.event ? data.event.fin : '');
                        }else{
                            var fin=HoraFin(date.format());
                            $('#star_time').val(data.event ? data.event.inicio : date.format());
                            $('#end_time').val(data.event ? data.event.fin : fin); 
                        }
                        $('#attendance').val(data.event ? data.event.attendance : '');
                        $('#id_calendar').val(data.event ? data.event.id_calendar : '');
                        $('#id_date').val(data.event ? data.event.id_date : '');
                        if(data.event){
                            var attendance_text=$("#attendance option:selected" ).text();
                            $("#select2-chosen-5").html(data.event ? attendance_text : '');
                        }
                        $.each(data.buttons, function(index, button){
                            $('.modal-footer').prepend('<button type="button" id="' + button.id  + '" class="btn ' + button.css + '">' + button.label + '</button>');
                        });
                        
                        $("br").remove();         
                        $('.modal').modal('show');
                        
                        //codigo para dejar marcado el terapista en el modal en el caso de que este seleccionado en el filtro y no sea para modificacion
                        if($("#users").val()!==null && !data.event){                            
                            $('#therapist').val($("#users").val());
                            var therapist_text=$("#therapist option:selected" ).text();
                            $("#select2-chosen-3").html(therapist_text);
                        }
                        //codigo para dejar marcado el paciente en el modal en el caso de que este seleccionado en el filtro y no sea para modificacion
                        if($("#patients_search").val()!==null && !data.event){                           
                            $('#patients').val($("#patients_search").val());
                            var paciente_text=$("#patients option:selected" ).text();
                            $("#select2-chosen-2").html(paciente_text);
                        }
                    }

                    $('.modal').on('click', '#agregar_evento',  function(e){
                        if($("#patients").val()==='' && $("#therapist").val()===''){                            
                            bootbox.alert("<b>Please, select a patients or therapist<b>");
                            return false;
                        } 
                        
                        if(validator(['type','location'])) {            
                            if($('#time_view').is(':checked')){ 
                                var type_appoiments=1;
                            }
                            if($('#recurring').is(':checked')){ 
                                 type_appoiments=2;
                            }
                            if(!$('#time_view').is(':checked') && !$('#recurring').is(':checked')){ 
                                 type_appoiments=0;
                            }
                            
                            if(type_appoiments===0){
                                
                                bootbox.alert("<b>Please, select the time or recurring<b>");
                                return false;
                            }
                            if(type_appoiments===1 && ($('#star_time').val()==='' || $('#end_time').val()==='')){
                                
                                bootbox.alert("<b>Please, select the time or recurring<b>");
                                return false;
                            }
                            if($("#next").val()==='' && type_appoiments===2){
                            
                                bootbox.alert("<b>Please, Indicates the amount<b>");
                                return false;
                            }
                            if($("#count_time").val()==='' && type_appoiments===2){
                            
                                bootbox.alert("<b>Please, select a week or biweekly<b>");
                                return false;
                            }
                            if(type_appoiments===1){
                                
                                    bootbox.confirm({
                                    message: '<h3><b>¿Be sure to add the appointments?</b></h3>',
                                    buttons: {
                                        confirm: {
                                            label: 'Accept',
                                            className: 'btn-success'
                                        },
                                        cancel: {
                                            label: 'Cancel',
                                            className: 'btn-danger'
                                        }
                                    },
                                    callback: function (result) {
                                        if(result===true){
                                            $.ajax({
                                                url: '../../../mvc/controlador/calendar/add/registrar_appoiments.php',
                                                data: {
                                                    pat_id: $('#patients').val(),
                                                    therapista_id:$('#therapist').val(),
                                                    type:$('#type').val(),
                                                    subject:$('#subject').val(),
                                                    location: $('#location').val(),                                                    
                                                    star_time: $('#star_time').val(),
                                                    end_time: $('#end_time').val(),                                        
                                                    attendance:$('#attendance').val(),
                                                    date: currentDate + ' ' + getTime(),
                                                    conflicto: 'no',
                                                    type_appoiments:1,
                                                },

                                                type: 'POST',
                                                async:false,

                                                dataType: "json",

                                                success: function(data){

                                                    if(data.success){
                                                        bootbox.alert("Data has been loaded correctly");                                                        
                                                        $('.modal').modal('hide');
                                                        $('#patients').val('');
                                                        $('#type').val('');
                                                        $('#therapist').val('');
                                                        $('#subject').val('');
                                                        $('#location').val('');                                                        
                                                        $('#attendance').val(''); 
                                                        $('#star_time').val('');
                                                        $('#end_time').val('');
                                                        $("#select2-chosen-1").html('Seleccione');
                                                        $("#select2-chosen-2").html('---SELECT---');
                                                        $("#select2-chosen-3").html('');
                                                        $("#select2-chosen-4").html('---SELECT---');
                                                        $("#select2-chosen-5").html('-None-');
                                                        $('#calendario').fullCalendar("refetchEvents");                                                        
                                                       // location.reload();

                                                    }else{
                                                        if(data.duplicated==='si'){
                                                            bootbox.alert(data.body);
                                                        }else{    
                                                            bootbox.alert("An error has occurred, please try again");
                                                        }
                                                    }
                                                }

                                            });
                                        }
                                    }
                                });
                            
                            }
                            if(type_appoiments===2){
                                var next=$("#next").val();
                                var count_time=$("#count_time").val();
                                
                                //monday
                                if($('#mon').is(':checked')){                        
                                    var mon_hour= $("#mon_hour").val();
                                    var mon_minute=$("#mon_minute").val();
                                    var mon_type= $("#mon_type").val();
                                    var mon_hour_to= $("#mon_hour_to").val();
                                    var mon_minute_to=$("#mon_minute_to").val();
                                    var mon_type_to= $("#mon_type_to").val();

                                    var hour_mon=mon_hour+":"+mon_minute+" "+mon_type;
                                    var hour_mon_to=mon_hour_to+":"+mon_minute_to+" "+mon_type_to;
                                }else{
                                    hour_mon='';
                                    hour_mon_to='';
                                }

                                //tuesday
                                if($('#tue').is(':checked')){                        
                                    var tue_hour= $("#tue_hour").val();
                                    var tue_minute=$("#tue_minute").val();
                                    var tue_type= $("#tue_type").val();
                                    var tue_hour_to= $("#tue_hour_to").val();
                                    var tue_minute_to=$("#tue_minute_to").val();
                                    var tue_type_to= $("#tue_type_to").val();

                                    var hour_tue=tue_hour+":"+tue_minute+" "+tue_type;
                                    var hour_tue_to=tue_hour_to+":"+tue_minute_to+" "+tue_type_to;
                                }else{
                                    hour_tue='';
                                    hour_tue_to='';
                                }

                                //Wednesday
                                if($('#wed').is(':checked')){                        
                                    var wed_hour= $("#wed_hour").val();
                                    var wed_minute=$("#wed_minute").val();
                                    var wed_type= $("#wed_type").val();
                                    var wed_hour_to= $("#wed_hour_to").val();
                                    var wed_minute_to=$("#wed_minute_to").val();
                                    var wed_type_to= $("#wed_type_to").val();

                                    var hour_wed=wed_hour+":"+wed_minute+" "+wed_type;
                                    var hour_wed_to=wed_hour_to+":"+wed_minute_to+" "+wed_type_to;
                                }else{
                                    hour_wed='';
                                    hour_wed_to='';
                                }

                                //Thursday
                                if($('#thu').is(':checked')){                        
                                    var thu_hour= $("#thu_hour").val();
                                    var thu_minute=$("#thu_minute").val();
                                    var thu_type= $("#thu_type").val();
                                    var thu_hour_to= $("#thu_hour_to").val();
                                    var thu_minute_to=$("#thu_minute_to").val();
                                    var thu_type_to= $("#thu_type_to").val();

                                    var hour_thu=thu_hour+":"+thu_minute+" "+thu_type;
                                    var hour_thu_to=thu_hour_to+":"+thu_minute_to+" "+thu_type_to;
                                }else{
                                    hour_thu='';
                                    hour_thu_to='';
                                }

                                //Friday
                                if($('#fri').is(':checked')){                        
                                    var fri_hour= $("#fri_hour").val();
                                    var fri_minute=$("#fri_minute").val();
                                    var fri_type= $("#fri_type").val();
                                    var fri_hour_to= $("#fri_hour_to").val();
                                    var fri_minute_to=$("#fri_minute_to").val();
                                    var fri_type_to= $("#fri_type_to").val();

                                    var hour_fri=fri_hour+":"+fri_minute+" "+fri_type;
                                    var hour_fri_to=fri_hour_to+":"+fri_minute_to+" "+fri_type_to;
                                }else{
                                    hour_fri='';
                                    hour_fri_to='';
                                }

                                //Saturday
                                if($('#sat').is(':checked')){                        
                                    var sat_hour= $("#sat_hour").val();
                                    var sat_minute=$("#sat_minute").val();
                                    var sat_type= $("#sat_type").val();
                                    var sat_hour_to= $("#sat_hour_to").val();
                                    var sat_minute_to=$("#sat_minute_to").val();
                                    var sat_type_to= $("#sat_type_to").val();

                                    var hour_sat=sat_hour+":"+sat_minute+" "+sat_type;
                                    var hour_sat_to=sat_hour_to+":"+sat_minute_to+" "+sat_type_to;
                                }else{
                                    hour_sat='';
                                    hour_sat_to='';
                                }

                                //Sunday
                                if($('#sun').is(':checked')){                        
                                    var sun_hour= $("#sun_hour").val();
                                    var sun_minute=$("#sun_minute").val();
                                    var sun_type= $("#sun_type").val();
                                    var sun_hour_to= $("#sun_hour_to").val();
                                    var sun_minute_to=$("#sun_minute_to").val();
                                    var sun_type_to= $("#sun_type_to").val();

                                    var hour_sun=sun_hour+":"+sun_minute+" "+sun_type;
                                    var hour_sun_to=sun_hour_to+":"+sun_minute_to+" "+sun_type_to;
                                }else{
                                    hour_sun='';
                                    hour_sun_to='';
                                }
                                
                                if(hour_mon==='' && hour_tue==='' && hour_wed==='' && hour_thu==='' && hour_fri==='' && hour_sat==='' && hour_sun===''){
                                    
                                    bootbox.alert("<b>Please, indicate at least one day<b>");
                                    return false;   
                                }
                                    bootbox.confirm({
                                    message: '<h3><b>¿Be sure to add the appointments?</b></h3>',
                                    buttons: {
                                        confirm: {
                                            label: 'Accept',
                                            className: 'btn-success'
                                        },
                                        cancel: {
                                            label: 'Cancel',
                                            className: 'btn-danger'
                                        }
                                    },
                                    callback: function (result) {
                                        if(result===true){

                                            $.ajax({

                                                url: '../../../mvc/controlador/calendar/add/registrar_appoiments.php',

                                                data: {
                                                    pat_id: $('#patients').val(),
                                                    therapista_id:$('#therapist').val(),
                                                    type:$('#type').val(),
                                                    subject:$('#subject').val(),
                                                    location: $('#location').val(),                                                                                                                                                                                                    
                                                    attendance:$('#attendance').val(),
                                                    date: currentDate + ' ' + getTime(),
                                                    conflicto: 'no',
                                                    type_appoiments:2,
                                                    hour_mon:hour_mon,
                                                    hour_mon_to:hour_mon_to,
                                                    hour_tue:hour_tue,
                                                    hour_tue_to:hour_tue_to,
                                                    hour_wed:hour_wed,
                                                    hour_wed_to:hour_wed_to,
                                                    hour_thu:hour_thu,
                                                    hour_thu_to:hour_thu_to,
                                                    hour_fri:hour_fri,
                                                    hour_fri_to:hour_fri_to,
                                                    hour_sat:hour_sat,
                                                    hour_sat_to:hour_sat_to,
                                                    hour_sun:hour_sun,
                                                    hour_sun_to:hour_sun_to,
                                                    quantity:next,
                                                    period:count_time,
                                                },

                                                type: 'POST',
                                                async:false,

                                                dataType: "json",

                                                success: function(data){

                                                    if(data.success){
                                                        bootbox.alert("Data has been loaded correctly");                                                        
                                                        $('.modal').modal('hide');
                                                        $('#patients').val('');
                                                        $('#type').val('');
                                                        $('#therapist').val('');
                                                        $('#subject').val('');
                                                        $('#location').val('');                                                        
                                                        $('#attendance').val('');
                                                        $("#select2-chosen-1").html('Seleccione');
                                                        $("#select2-chosen-2").html('---SELECT---');
                                                        $("#select2-chosen-3").html('');
                                                        $("#select2-chosen-4").html('---SELECT---');
                                                        $("#select2-chosen-5").html('-None-');
                                                        $('#calendario').fullCalendar("refetchEvents");
                                                        //location.reload();

                                                    }else{
                                                        if(data.duplicated==='si'){
                                                            bootbox.alert(data.body);
                                                        }else{    
                                                            bootbox.alert("An error has occurred, please try again");
                                                        }
                                                    }
                                                }

                                            });
                                        }
                                    }
                                });
                            }
                            
                           
                        }
                    });                    

                    $('.modal').on('click', '#update-event',  function(e){
                        if($("#patients").val()==='' && $("#therapist").val()===''){                            
                            bootbox.alert("<b>Please, select a patients or therapist<b>");
                            return false;
                        }
                        if(validator(['type','location','star_time','end_time'])) {
                            if($('#time_view').is(':checked')){ 
                                var type_appoiments=1;
                            }
                            if($('#recurring').is(':checked')){ 
                                 type_appoiments=2;
                            }
                            if(!$('#time_view').is(':checked') && !$('#recurring').is(':checked')){ 
                                 type_appoiments=0;
                            }                            
                            
                            if(type_appoiments===1){
                                bootbox.alert("<div align='center'><b><h3>Do you want to send a message to the therapist <input type='checkbox' name='terapistas' id='terapistas'> or the patient <input type='checkbox' name='pacientes' id='pacientes'>?</h3></b></div><br><div align='center'><button onclick='send_update_time();' class='btn btn-success'>Update</button></div>");
                                 
                            }
                            
                            if(type_appoiments===2){
                                var next=$("#next").val();
                                var count_time=$("#count_time").val();
                                
                                //monday
                                if($('#mon').is(':checked')){                        
                                    var mon_hour= $("#mon_hour").val();
                                    var mon_minute=$("#mon_minute").val();
                                    var mon_type= $("#mon_type").val();
                                    var mon_hour_to= $("#mon_hour_to").val();
                                    var mon_minute_to=$("#mon_minute_to").val();
                                    var mon_type_to= $("#mon_type_to").val();

                                    var hour_mon=mon_hour+":"+mon_minute+" "+mon_type;
                                    var hour_mon_to=mon_hour_to+":"+mon_minute_to+" "+mon_type_to;
                                }else{
                                    hour_mon='';
                                    hour_mon_to='';
                                }

                                //tuesday
                                if($('#tue').is(':checked')){                        
                                    var tue_hour= $("#tue_hour").val();
                                    var tue_minute=$("#tue_minute").val();
                                    var tue_type= $("#tue_type").val();
                                    var tue_hour_to= $("#tue_hour_to").val();
                                    var tue_minute_to=$("#tue_minute_to").val();
                                    var tue_type_to= $("#tue_type_to").val();

                                    var hour_tue=tue_hour+":"+tue_minute+" "+tue_type;
                                    var hour_tue_to=tue_hour_to+":"+tue_minute_to+" "+tue_type_to;
                                }else{
                                    hour_tue='';
                                    hour_tue_to='';
                                }

                                //Wednesday
                                if($('#wed').is(':checked')){                        
                                    var wed_hour= $("#wed_hour").val();
                                    var wed_minute=$("#wed_minute").val();
                                    var wed_type= $("#wed_type").val();
                                    var wed_hour_to= $("#wed_hour_to").val();
                                    var wed_minute_to=$("#wed_minute_to").val();
                                    var wed_type_to= $("#wed_type_to").val();

                                    var hour_wed=wed_hour+":"+wed_minute+" "+wed_type;
                                    var hour_wed_to=wed_hour_to+":"+wed_minute_to+" "+wed_type_to;
                                }else{
                                    hour_wed='';
                                    hour_wed_to='';
                                }

                                //Thursday
                                if($('#thu').is(':checked')){                        
                                    var thu_hour= $("#thu_hour").val();
                                    var thu_minute=$("#thu_minute").val();
                                    var thu_type= $("#thu_type").val();
                                    var thu_hour_to= $("#thu_hour_to").val();
                                    var thu_minute_to=$("#thu_minute_to").val();
                                    var thu_type_to= $("#thu_type_to").val();

                                    var hour_thu=thu_hour+":"+thu_minute+" "+thu_type;
                                    var hour_thu_to=thu_hour_to+":"+thu_minute_to+" "+thu_type_to;
                                }else{
                                    hour_thu='';
                                    hour_thu_to='';
                                }

                                //Friday
                                if($('#fri').is(':checked')){                        
                                    var fri_hour= $("#fri_hour").val();
                                    var fri_minute=$("#fri_minute").val();
                                    var fri_type= $("#fri_type").val();
                                    var fri_hour_to= $("#fri_hour_to").val();
                                    var fri_minute_to=$("#fri_minute_to").val();
                                    var fri_type_to= $("#fri_type_to").val();

                                    var hour_fri=fri_hour+":"+fri_minute+" "+fri_type;
                                    var hour_fri_to=fri_hour_to+":"+fri_minute_to+" "+fri_type_to;
                                }else{
                                    hour_fri='';
                                    hour_fri_to='';
                                }

                                //Saturday
                                if($('#sat').is(':checked')){                        
                                    var sat_hour= $("#sat_hour").val();
                                    var sat_minute=$("#sat_minute").val();
                                    var sat_type= $("#sat_type").val();
                                    var sat_hour_to= $("#sat_hour_to").val();
                                    var sat_minute_to=$("#sat_minute_to").val();
                                    var sat_type_to= $("#sat_type_to").val();

                                    var hour_sat=sat_hour+":"+sat_minute+" "+sat_type;
                                    var hour_sat_to=sat_hour_to+":"+sat_minute_to+" "+sat_type_to;
                                }else{
                                    hour_sat='';
                                    hour_sat_to='';
                                }

                                //Sunday
                                if($('#sun').is(':checked')){                        
                                    var sun_hour= $("#sun_hour").val();
                                    var sun_minute=$("#sun_minute").val();
                                    var sun_type= $("#sun_type").val();
                                    var sun_hour_to= $("#sun_hour_to").val();
                                    var sun_minute_to=$("#sun_minute_to").val();
                                    var sun_type_to= $("#sun_type_to").val();

                                    var hour_sun=sun_hour+":"+sun_minute+" "+sun_type;
                                    var hour_sun_to=sun_hour_to+":"+sun_minute_to+" "+sun_type_to;
                                }else{
                                    hour_sun='';
                                    hour_sun_to='';
                                }
                                
                                if(hour_mon==='' && hour_tue==='' && hour_wed==='' && hour_thu==='' && hour_fri==='' && hour_sat==='' && hour_sun===''){
                                    
                                    bootbox.alert("<b>Please, indicate at least one day<b>");
                                    return false;   
                                }
                                bootbox.alert("<div align='center'><b>¿Do you want to update this appointment or all associates that have this recurring? <button onclick='is(\""+hour_mon+"\",\""+hour_mon_to+"\",\""+hour_tue+"\",\""+hour_tue_to+"\",\""+hour_wed+"\",\""+hour_wed_to+"\",\""+hour_thu+"\",\""+hour_thu_to+"\",\""+hour_fri+"\",\""+hour_fri_to+"\",\""+hour_sat+"\",\""+hour_sat_to+"\",\""+hour_sun+"\",\""+hour_sun_to+"\",\""+next+"\",\""+count_time+"\",\"is\");' class='btn btn-success'>Is</button> <button onclick='is(\""+hour_mon+"\",\""+hour_mon_to+"\",\""+hour_tue+"\",\""+hour_tue_to+"\",\""+hour_wed+"\",\""+hour_wed_to+"\",\""+hour_thu+"\",\""+hour_thu_to+"\",\""+hour_fri+"\",\""+hour_fri_to+"\",\""+hour_sat+"\",\""+hour_sat_to+"\",\""+hour_sun+"\",\""+hour_sun_to+"\",\""+next+"\",\""+count_time+"\",\"all\");' class='btn btn-danger'>All</button><b></div>");
                                
                                 
                            }
                        }
                    });
                    $('.modal').on('click', '#delete-event',  function(e){
                        if($('#time_view').is(':checked')){ 
                            var type_appoiments=1;
                        }
                        if($('#recurring').is(':checked')){ 
                             type_appoiments=2;
                        }
                        if(!$('#time_view').is(':checked') && !$('#recurring').is(':checked')){ 
                             type_appoiments=0;
                        }
                        if(type_appoiments===1){
                            bootbox.confirm({
                                    message: '<h3><b>¿Be sure to delete the appointments?</b></h3>',
                                    buttons: {
                                        confirm: {
                                            label: 'Accept',
                                            className: 'btn-success'
                                        },
                                        cancel: {
                                            label: 'Cancel',
                                            className: 'btn-danger'
                                        }
                                    },
                                    callback: function (result) {
                                        if(result===true){

                                            $.get('../../../mvc/controlador/calendar/delete/delete_appoiments.php?type_appoiments='+type_appoiments+'&id_date='+$('#id_date').val()+'&id_calendar=' + $('#id_calendar').val(), function(success){
                                                if(success){
                                                    bootbox.alert("Data has been deleted correctly");                    
                                                    location.reload();
                                                }else{
                                                    bootbox.alert("An error has occurred, please try again");
                                                }
                                            });
                                        }
                                    }
                            });
                        }
                        if(type_appoiments===2){
                            
                            bootbox.alert("<div align='center'><b><h3>Do you want to delete this appointment only? Or all belonging to the recurring</h3></b></div><br><div align='center'><button onclick='delete_recurring(\"is\");' class='btn btn-success'>Is </button> <button onclick='delete_recurring(\"all\");' class='btn btn-danger'> All</button></div>");
                        }
                    });
                    
                    
                    
                    function getTime() {
                        var time = $('#time').val();
                        return (time.indexOf(':') == 1 ? '0' + time : time) + ':00';
                    }
                    function validator(elements) {
                        var errors = 0;
                        $.each(elements, function(index, element){
                            if($.trim($('#' + element).val()) == '') errors++;
                        });
                        if(errors) {
                            $('.error').html(' <b>Please indicate all the fields of the form, to continue with the process </b>');
                            return false;
                        }
                        return true;
                    }
                });
                
                $('#users').change(function () { 
                    
                    var events = {
                            url: '../../../mvc/controlador/calendar/consult/listar_appoiments.php',
                            type: 'GET',
                            data: {
                                users: $("#users").val(),
                                patients: $("#patients_search").val(),
                                own:$("#own").val(),
                                usuario:$("#own_user").val(),
                            }
                        }
                    
                    $('#calendario').fullCalendar('removeEventSource', events);
                    $('#calendario').fullCalendar('addEventSource', events);
                    //$('#calendario').fullCalendar('refetchEvents');
                    
                });
                
                $('#patients_search').change(function () {
                    
                    var events = {
                            url: '../../../mvc/controlador/calendar/consult/listar_appoiments.php',
                            type: 'GET',
                            data: {
                                users: $("#users").val(),
                                patients: $("#patients_search").val(),
                                own:$("#own").val(),
                                usuario:$("#own_user").val(),
                            }
                        }
                    $('#calendario').fullCalendar('removeEventSource', events);
                    $('#calendario').fullCalendar('addEventSource', events);
                    //$('#calendario').fullCalendar('refetchEvents');
                });   
                
                $('#type').change(function (){ 
                    var id=this.value;                                        
                    var valor=$('#location_type_id'+id).val();                    
                    $("#location").val(valor); 
                    var location_text=$("#location option:selected" ).text();
                    $("#select2-chosen-4").html(location_text);
                    
                });
                
            });
            
            
            $('.conflicto').on('click', '#ignorar_conflicto',  function(e){
                    alert("aquii");
            });
            
    </script>
</html>


                                

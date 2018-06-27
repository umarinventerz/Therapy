<?php

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
$conexion = conectar();
$sql2  = "SELECT A.*,L.name as location FROM tbl_type_appoiments A left join tbl_location_appoiments L ON(A.location_id=L.id)  order by id desc";
$resultado1 = ejecutar($sql2,$conexion); 
$j = 0;      
while($datos = mysqli_fetch_assoc($resultado1)){            
    $respuesta[$j] = $datos;
    $j++;
}
//location 

$sql3  = "SELECT * FROM tbl_location_appoiments order by id desc";
$resultado2 = ejecutar($sql3,$conexion); 
$i = 0;      
while($datos1 = mysqli_fetch_assoc($resultado2)){            
    $respuesta1[$i] = $datos1;
    $i++;
}

//status 

$sql4  = "SELECT * FROM tbl_status_appoiments order by id desc";
$resultado3 = ejecutar($sql4,$conexion); 
$m = 0;      
while($datos2 = mysqli_fetch_assoc($resultado3)){            
    $respuesta2[$m] = $datos2;
    $m++;
}

//time 
$data  = "SELECT * FROM calendar_time";
$row = ejecutar($data,$conexion);
while($datos = mysqli_fetch_assoc($row)) {            
        $time_result = $datos['time'];
        
}
$valor_time=  explode(':', $time_result);
$result_valor=  explode(" ", $valor_time[1]);
?>
<html>    
    <head>
        
        <link rel="stylesheet" href="../../../css/bootstrap.min.css" type='text/css'/>
        <link href="../../../plugins/fullcalendar/css/bootstrap-colorpicker.min.css" rel="stylesheet" />
        <link href="../../../plugins/fullcalendar/css/bootstrap-timepicker.min.css" rel="stylesheet" />        
        <script src="../../../plugins/jquery/jquery.min.js"></script>
        <script src="../../../plugins/jquery-ui/jquery-ui.min.js"></script>    
        <script src="../../../plugins/bootstrap/bootstrap.min.js"></script>    
        <script src='../../../plugins/fullcalendar/js/bootstrap-colorpicker.min.js'></script>
        <script src='../../../plugins/fullcalendar/js/bootstrap-timepicker.min.js'></script>
        <script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
        
        
    </head>
    <!--Campos ocultos para el time-->
    <input type="hidden" name="result_hour" id="result_hour" value="<?=$valor_time[0]?>"/>
    <input type="hidden" name="result_minute" id="result_minute" value="<?=$result_valor[0]?>"/>
    <input type="hidden" name="result_type" id="result_type" value="<?=$result_valor[1]?>"/>
    
    <body>
        <div class="container">    
            <div class="row">
               <div class="col-lg-6">
                   <img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">
               </div>
            </div>
            <h1><div align="center"><u>Calendar Setup</u></div></h1><br>
            <div class="panel panel-primary">
                <div class="panel-heading">TYPE APPOIMENTS</div>
                <div class="panel-body">
                    <h2>Add Type</h2><br>
                    <div class="col-md-12">
                            Type:<input type="text" name="type" id="type" class="form-control" placeholder="Type"/><br>
                            Color:<input id="color"  name="color" type="text" class="form-control input-md" readonly="readonly" />
                            <span class="help-block">Select a color to identify appointments with this type</span>
                            Location:<select id="location_type" name="location_type" class="form-control">
                                <option value="0">Seleccione</option>
                                <?php 
                                    for($i=0;$i<count($respuesta1);$i++){
                                ?>
                                <option value="<?=$respuesta1[$i]['id']?>"><?=$respuesta1[$i]['name']?></option>
                                <?php } ?>
                            </select><br>
                            <div align="center"><button class="btn btn-primary" onclick="add_type();">Add</button></div>
                            <hr>
                            <h2>Result type appoiments</h2>
                            <table  id="type_appoiments" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th data-field="id">
                                        <b>Id</b>
                                    </th>

                                    <th data-field="type">
                                        <b>Type</b>
                                    </th>
                                    
                                    <th data-field="color">
                                        <b>Color</b>
                                    </th>  
                                    <th data-field="location">
                                        <b>Location</b>
                                    </th>
                                    <th data-field="Action">
                                        <b>Action</b>
                                    </th>
                                </tr>
                            </thead>
                             <tbody>  

                                 <?php 
                                    $i=0;
                                    while($respuesta[$i]!=null){
                                 ?>
                                    <tr>
                                        <td><?=$respuesta[$i]['id']?></td>
                                        <td>
                                            <div id="show_type_name<?=$respuesta[$i]['id']?>"><?=$respuesta[$i]['name']?></div>
                                            <div id="hide_type_name<?=$respuesta[$i]['id']?>" style="display:none"><input type="text" name="type_update<?=$respuesta[$i]['id']?>" id="type_update<?=$respuesta[$i]['id']?>" class="form-control" placeholder="Type" value="<?=$respuesta[$i]['name']?>"/><br></div>
                                        </td>
                                        <td>
                                            <div id="show_type_color<?=$respuesta[$i]['id']?>"><span style="color: <?=$respuesta[$i]['color']?>"><b><u>_____</u></b></span></div>
                                            <div id="hide_type_color<?=$respuesta[$i]['id']?>" style="display:none"><input id="color_update<?=$respuesta[$i]['id']?>" name="color_update<?=$respuesta[$i]['id']?>" value="<?=$respuesta[$i]['color']?>" type="text" class="form-control input-md" readonly="readonly" /><br></div>
                                        </td>
                                        
                                        <td>
                                            <div id="show_type_location<?=$respuesta[$i]['id']?>"><?=$respuesta[$i]['location']?></div>
                                            <div id="hide_type_location<?=$respuesta[$i]['id']?>" style="display:none">
                                                <select id="location_type_update<?=$respuesta[$i]['id']?>" name="location_type_update<?=$respuesta[$i]['id']?>" class="form-control">
                                                    <option value="0">Seleccione</option>
                                                    <?php 
                                                        for($j=0;$j<count($respuesta1);$j++){
                                                            if($respuesta1[$j]['id']==$respuesta[$i]['location_id']){
                                                    ?>                                                    
                                                    <option value="<?=$respuesta1[$j]['id']?>" selected=""><?=$respuesta1[$j]['name']?></option>
                                                            <?php }else{?>
                                                    <option value="<?=$respuesta1[$j]['id']?>"><?=$respuesta1[$j]['name']?></option>
                                                            <?php } } ?>
                                                </select><br>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div id="show_type_update<?=$respuesta[$i]['id']?>"><button id="update" class="btn btn-primary" onclick="update_type(<?=$respuesta[$i]['id']?>);">Update</button></div>
                                            <div id="hide_type_update<?=$respuesta[$i]['id']?>" style="display:none"><button id="update" class="btn btn-primary" onclick="actualizar('<?=$respuesta[$i]['id']?>');">Update</button></div>
                                            <br><button id="eliminar" class="btn btn-danger" onclick="eliminar_type(<?=$respuesta[$i]['id']?>);">Delete</button>
                                        </td>
                                    </tr>
                                <?php $i++; } ?>

                             </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="panel panel-danger">
                <div class="panel-heading">LOCATION APPOIMENTS</div>
                <div class="panel-body">
                    <h2>Location</h2><br>
                    <div class="col-md-12">
                            Location:<input type="text" name="location" id="location" class="form-control" placeholder="Location"/><br>                            
                            <div align="center"><button class="btn btn-primary" onclick="add_location();">Add</button></div>
                            <hr>
                            <h2>Result Location</h2>
                            <table  id="location_appoiments" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th data-field="id">
                                        <b>Id</b>
                                    </th>

                                    <th data-field="Location">
                                        <b>Location</b>
                                    </th>                                                                       
                                    <th data-field="Action">
                                        <b>Action</b>
                                    </th>
                                </tr>
                            </thead>
                             <tbody>  

                                 <?php 
                                    $i=0;
                                    while($respuesta1[$i]!=null){
                                 ?>
                                    <tr>
                                        <td><?=$respuesta1[$i]['id']?></td>
                                        <td>
                                            <div id="show_location_name<?=$respuesta1[$i]['id']?>"><?=$respuesta1[$i]['name']?></div>
                                            <div id="hide_location_name<?=$respuesta1[$i]['id']?>" style="display:none"><input type="text" name="location_update<?=$respuesta1[$i]['id']?>" id="location_update<?=$respuesta1[$i]['id']?>" class="form-control" placeholder="Location" value="<?=$respuesta1[$i]['name']?>"/></div>
                                        </td>                                        
                                        <td>
                                            <div id="show_location_update<?=$respuesta1[$i]['id']?>"><button id="update" class="btn btn-primary" onclick="update_location(<?=$respuesta1[$i]['id']?>);">Update</button></div>
                                            <div id="hide_location_update<?=$respuesta1[$i]['id']?>" style="display:none"><button id="update" class="btn btn-primary" onclick="actualizar_location('<?=$respuesta1[$i]['id']?>');">Update</button></div>
                                            <br><button id="eliminar" class="btn btn-danger" onclick="eliminar_location(<?=$respuesta1[$i]['id']?>);">Delete</button>
                                        </td>
                                    </tr>
                                <?php $i++; } ?>

                             </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="panel panel-info">
                <div class="panel-heading">STATUS APPOIMENTS</div>
                <div class="panel-body">
                    <h2>Status</h2><br>
                    <div class="col-md-12">
                            Status:<input type="text" name="status" id="status" class="form-control" placeholder="Status"/><br>                            
                            <div align="center"><button class="btn btn-primary" onclick="add_status();">Add</button></div>
                            <hr>
                            <h2>Result Status</h2>
                            <table  id="status_appoiments" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th data-field="id">
                                        <b>Id</b>
                                    </th>

                                    <th data-field="status">
                                        <b>Status</b>
                                    </th>                                                                       
                                    <th data-field="Action">
                                        <b>Action</b>
                                    </th>
                                </tr>
                            </thead>
                             <tbody>  

                                 <?php 
                                    $i=0;
                                    while($respuesta2[$i]!=null){
                                 ?>
                                    <tr>
                                        <td><?=$respuesta2[$i]['id']?></td>
                                        <td>
                                            <div id="show_status_name<?=$respuesta2[$i]['id']?>"><?=$respuesta2[$i]['name']?></div>
                                            <div id="hide_status_name<?=$respuesta2[$i]['id']?>" style="display:none"><input type="text" name="status_update<?=$respuesta2[$i]['id']?>" id="status_update<?=$respuesta2[$i]['id']?>" class="form-control" value="<?=$respuesta2[$i]['name']?>" placeholder="Status"/></div>
                                        </td>                                        
                                        <td>
                                            <div id="show_status_update<?=$respuesta2[$i]['id']?>"><button id="update" class="btn btn-primary" onclick="update_status(<?=$respuesta2[$i]['id']?>);">Update</button></div>
                                            <div id="hide_status_update<?=$respuesta2[$i]['id']?>" style="display:none"><button id="update" class="btn btn-primary" onclick="actualizar_status('<?=$respuesta2[$i]['id']?>');">Update</button></div>
                                            <br><button id="eliminar" class="btn btn-danger" onclick="eliminar_status(<?=$respuesta2[$i]['id']?>);">Delete</button>
                                        </td>
                                    </tr>
                                <?php $i++; } ?>

                             </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="panel panel-warning">
                <div class="panel-heading">SCHEDULE TIME TO SEND MESSAGES FOR APPOIMENTS</div>
                <div class="panel-body">
                    <h2>Current time for sending appointments</h2><br>
                    <div class="col-md-12">
                        <b>Hour:</b><select id="hour" class="form-control">
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
                            </select>
                        <b>Minute:</b><select id="minute" class="form-control">
                                            <option value="00">00</option>                                
                                            <option value="30">30</option>                        
                                        </select>
                        <b>Time:</b><select id="type_time" class="form-control">
                                <option value="am">AM</option>
                                <option value="pm">PM</option>                                                                                                                                
                            </select><br>                            
                            <div align="center"><button class="btn btn-primary" onclick="schedule();">SCHEDULE</button></div>
                            <hr>
                            
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" language="javascript">
        $(document).ready(function(){  
            $('#color').colorpicker();            
                       
            $('#type_appoiments').DataTable({
                    pageLength: 5,                    
                    "scrollX": true,
                    "pagingType": "full_numbers",
                    "lengthMenu": [ 5, 25, 50, 75, 100 ],
                    
                    
            });            
            
            $('#location_appoiments').DataTable({
                    pageLength: 5,                    
                    "scrollX": true,
                    "pagingType": "full_numbers",
                    "lengthMenu": [ 5, 25, 50, 75, 100 ],
                    
            });
            
            $('#status_appoiments').DataTable({
                    pageLength: 5,                    
                    "scrollX": true,
                    "pagingType": "full_numbers",
                    "lengthMenu": [ 5, 25, 50, 75, 100 ],
                    
            });
            
            //seteo los valores de la hora con lo que buscamos de la BD
            var hour=$("#result_hour").val();
            var minute=$("#result_minute").val();
            var type=$("#result_type").val();
            
            $("#hour").val(hour);
            $("#minute").val(minute);
            $("#type_time").val(type);
        });
        
        function schedule(){
            
            var hour=$("#hour").val();
            var minute=$("#minute").val();
            var time=$("#type_time").val();
            
            var hour_seteada=hour+":"+minute+" "+time;
            
            $.ajax({
                url: '../../../mvc/controlador/calendar/time/gestion.php',
                data: {
                    'time': hour_seteada                      
                },
                type: 'GET',
                async:false,
                dataType: "json",
                success: function(data){
                    if(data.success){
                        alert("Data has been loaded correctly");                            
                        location.reload();
                    }else{
                        alert("An error has occurred, please try again");
                        location.reload();
                    }
                }

            });
            
            
        }
       
        function update_type(id){
            
            $("#show_type_name"+id).toggle();
            $("#hide_type_name"+id).toggle();
            $("#show_type_color"+id).toggle();
            $("#hide_type_color"+id).toggle();
            $("#show_type_update"+id).toggle();
            $("#hide_type_update"+id).toggle();
            $('#color_update'+id).colorpicker();
            $("#show_type_location"+id).toggle();
            $("#hide_type_location"+id).toggle();
            
        }
        function update_location(id){            
            $("#show_location_name"+id).toggle();
            $("#hide_location_name"+id).toggle();            
            $("#show_location_update"+id).toggle();
            $("#hide_location_update"+id).toggle();    
        }
        
        function update_status(id){            
            $("#show_status_name"+id).toggle();
            $("#hide_status_name"+id).toggle();            
            $("#show_status_update"+id).toggle();
            $("#hide_status_update"+id).toggle();    
        }
        function actualizar(id){            
            var type=$("#type_update"+id).val();            
            var color=$("#color_update"+id).val();
            var type_location=$("#location_type_update"+id).val();
            
            if(id==='' || type==='' || color==='' || type_location==='0'){
                alert("please, Enter the data form");
                return false;
            }
            $.ajax({
                    url: '../../../mvc/controlador/calendar/update/update_type_appoiments.php',
                    data: {
                        'id': id,'name':type,'color':color,'type_location':type_location                      
                    },
                    type: 'POST',
                    async:false,
                    dataType: "json",
                    success: function(data){
                        if(data.success){
                            alert("Data has been loaded correctly");                            
                            location.reload();
                        }else{
                            alert("An error has occurred, please try again");
                            location.reload();
                        }
                    }

                });
        }
        
        function actualizar_location(id){
            if(id===''){
                alert("please, Enter the data form");
                return false;
            }
            var type=$("#location_update"+id).val();     
            $.ajax({
                    url: '../../../mvc/controlador/calendar/update/update_location_appoiments.php',
                    data: {
                        'id': id,'name':type                    
                    },
                    type: 'POST',
                    async:false,
                    dataType: "json",
                    success: function(data){
                        if(data.success){
                            alert("Data has been loaded correctly");                            
                            location.reload();
                        }else{
                            alert("An error has occurred, please try again");
                            location.reload();
                        }
                    }

                });
        }
        
        function actualizar_status(id){
            if(id===''){
                alert("please, Enter the data form");
                return false;
            }
            var type=$("#status_update"+id).val();     
            $.ajax({
                    url: '../../../mvc/controlador/calendar/update/update_status_appoiments.php',
                    data: {
                        'id': id,'name':type                    
                    },
                    type: 'POST',
                    async:false,
                    dataType: "json",
                    success: function(data){
                        if(data.success){
                            alert("Data has been loaded correctly");                            
                            location.reload();
                        }else{
                            alert("An error has occurred, please try again");
                            location.reload();
                        }
                    }

                });
        }
        function add_type(){
                
            var type=$("#type").val();
            var color=$("#color").val();
            var location_type=$("#location_type").val();
            if(type==='' || color==='' || location_type===''){                
                alert("please, Enter the data form");
                return false;
            }
            $.ajax({
                url: '../../../mvc/controlador/calendar/add/registrar_type_appoiments.php',
                data: {
                    type: type,
                    color: color,
                    location_type: location_type
                },
                type: 'POST',
                async:false,
                dataType: "json",
                success: function(data){
                    if(data.success){
                        alert("Data has been loaded correctly");                            
                        location.reload();
                    }else{
                        alert("An error has occurred, please try again");
                    }
                }

            });
        }
        
        function eliminar_type(id){
            var confirmar=confirm('¿Are you sure to delete this type?');
            if(confirmar){
                $.ajax({
                    url: '../../../mvc/controlador/calendar/delete/delete_type_appoiments.php',
                    data: {
                        id: id                       
                    },
                    type: 'GET',
                    async:false,
                    dataType: "json",
                    success: function(data){
                        if(data.success){
                            alert("Data has been loaded correctly");                            
                            location.reload();
                        }else{
                            alert("An error has occurred, please try again");
                        }
                    }

                });
            }else{
                return false;
            }
        }
        
        //location 
        
        function add_location(){
                
            var locations=$("#location").val();            
            if(locations===''){                
                alert("please, Enter the data form");
                return false;
            }
            $.ajax({
                url: '../../../mvc/controlador/calendar/add/registrar_location_appoiments.php',
                data: {
                    locations: locations
                                          
                },
                type: 'POST',
                async:false,
                dataType: "json",
                success: function(data){
                    if(data.success){
                        alert("Data has been loaded correctly");                            
                        location.reload();
                    }else{
                        alert("An error has occurred, please try again");
                    }
                }

            });
        }
        
        function eliminar_location(id){
            var confirmar=confirm('¿Are you sure to delete this location?');
            if(confirmar){
                $.ajax({
                    url: '../../../mvc/controlador/calendar/delete/delete_location_appoiments.php',
                    data: {
                        id: id                       
                    },
                    type: 'GET',
                    async:false,
                    dataType: "json",
                    success: function(data){
                        if(data.success){
                            alert("Data has been loaded correctly");                            
                            location.reload();
                        }else{
                            alert("An error has occurred, please try again");
                        }
                    }

                });
            }else{
                return false;
            }
            
        }     
        //Status 
        
            function add_status(){

                var status=$("#status").val();            
                if(status===''){                
                    alert("please, Enter the data form");
                    return false;
                }
                $.ajax({
                    url: '../../../mvc/controlador/calendar/add/registrar_status_appoiments.php',
                    data: {
                        status: status

                    },
                    type: 'POST',
                    async:false,
                    dataType: "json",
                    success: function(data){
                        if(data.success){
                            alert("Data has been loaded correctly");                            
                            location.reload();
                        }else{
                            alert("An error has occurred, please try again");
                        }
                    }

                });
            }

            function eliminar_status(id){
                var confirmar=confirm('¿Are you sure to delete this status?');
                if(confirmar){
                    $.ajax({
                        url: '../../../mvc/controlador/calendar/delete/delete_status_appoiments.php',
                        data: {
                            id: id                       
                        },
                        type: 'GET',
                        async:false,
                        dataType: "json",
                        success: function(data){
                            if(data.success){
                                alert("Data has been loaded correctly");                            
                                location.reload();
                            }else{
                                alert("An error has occurred, please try again");
                            }
                        }

                    });
                }else{
                    return false;
                }
            }
        
        
    </script>
</html>
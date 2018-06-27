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

        $consulta = 'SELECT * FROM tbl_event';  

        
        $conexion = conectar();
        $ejecucion_consulta = mysqli_query($conexion, $consulta);        
        
        $reporte = array();
        
        $e = 0;      
        while($datos = mysqli_fetch_assoc($ejecucion_consulta)) {            
            $reporte_eventos[$e] = $datos;
            $e++;
        }
        
        
        $i=0;
        $cadena_eventos = null;
        while (isset($reporte_eventos[$i])){
            
            $cadena_eventos .= "{
                                        
                                        id: '".$reporte_eventos[$i]['id_event']."',
                                        title: '".$reporte_eventos[$i]['descripcion']."',
                                        
                                        start: '".$reporte_eventos[$i]['date_start']."'";
            
            if($reporte_eventos[$i]['date_end'] != null){
            
                $cadena_eventos .= ",
                        end: '".$reporte_eventos[$i]['date_end']."'
                        ";                            
            }            
            
            $cadena_eventos .= "}";
            
            if(isset($reporte_eventos[$i+1])){
                
                $cadena_eventos .= ",";
                
            }
            
            
            $i++;
        }         
                
?>


<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<link href='../../../css/fullcalendar.css' rel='stylesheet' />
<link href='../../../css/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='../../../js/moment.min.js'></script>
<script src='../../../js/fullcalendar.js'></script>
<script>

    function aceptar(){
        var id_event = $('#id_date').val();
        var asunto = $('#subject').val();
         var action='update';

        $.ajax({
            type: "POST",
            url: '../../controlador/payroll/ingresar_eventos.php',
            data: {id_event: id_event, asunto: asunto,action:action},
            dataType: 'JSON',
            success: function (data) {
                $("#fullCalModal").modal('hide');
                swal("Event Update", "Congrtulations", "success");
                $("#fullCalModal").modal('hide');

                location.reload();


            },

            error: function (data) {
                swal("Error!", " Contact with administrator!", "error");
            }
        })

    }

    function cancelar(){

        var id_event = $('#id_date').val();
        var asunto = $('#subject').val();
        var action='delete';

        $.ajax({
            type: "POST",
            url: '../../controlador/payroll/ingresar_eventos.php',
            data: {id_event: id_event, asunto: asunto,action:action},
                dataType: 'JSON',
                success: function (data) {
                    $("#fullCalModal").modal('hide');
                    swal("Event Delete", "Congrtulations", "success");
                    $("#fullCalModal").modal('hide');

                    location.reload();



                },

                error: function (data) {
                    swal("Error!", " Contact with administrator!", "error");
                }
            })
       // $('#fullCalModal').modal('hide');

    }

	$(document).ready(function() {
		
                    var d = new Date();
                
                var fecha = d.getFullYear()+'-'+0+(parseInt(d.getMonth())+parseInt(1))+'-'+d.getDate();    
                
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			defaultDate: fecha,
			selectable: true,
			selectHelper: true,


            eventClick: function(event) {
                if (event.title) {


                    $('#modalTitle').html(event.title);

                    $('#start').html(moment(event.start));
                   $('#end').html(moment(event.end));
                    $('#subject').val(event.title);
                    $('#id_date').val(event.id);

                    $('#fullCalModal').modal();
                    return false;
                }
            },

            select: function(start, end) {
                        
                        var d = new Date(start);
                        
                        var dia_ini = (parseInt(d.getDate())+parseInt(1));
                        var mes_ini = (parseInt(d.getMonth())+parseInt(1));                        
                        var anho_ini = d.getFullYear();
                        
                        var fecha_ini = anho_ini+'-'+mes_ini+'-'+dia_ini;
                        
                        var e = new Date(end);
                        
                        var dia_fin = (parseInt(e.getDate())+parseInt(1));
                        var mes_fin = (parseInt(e.getMonth())+parseInt(1));                        
                        var anho_fin = e.getFullYear();                        
                        
                        var fecha_fin = anho_fin+'-'+mes_fin+'-'+dia_fin;
                        
                        
                                var title = prompt('Event Title:');
				var eventData;
				if (title) {
					
                            $.post(
                                    "../../controlador/payroll/ingresar_eventos.php",
                                    '&title='+title+'&start='+fecha_ini+'&end='+fecha_fin,
                                    function (resultado_controlador) {
                                        
                                        if(resultado_controlador.resultado == 'creado'){
                                            
                                            eventData = {
                                                    title: title,
                                                    start: start,
                                                    end: end
                                            };
                                            $('#calendar').fullCalendar('renderEvent', eventData, true);                                            
                                            
                                            
                                        }
                                  

                                    },
                                    "json" 
                                    );                                        
            
            
            
            

				}
				$('#calendar').fullCalendar('unselect');
			},
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: [

                <?php echo $cadena_eventos; ?>
			]
		});

 
		
	});

</script>
<style>

	body {
		margin: 40px 10px;
		padding: 0;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		font-size: 14px;
	}



</style>
</head>
<body>


<div id="collapse1" class="panel-collapse collapse in">
    <div class="row">
        <div class="col-lg-12" style="padding: 30px;">
	<div id='calendar'></div>
        </div>
    </div>
</div>

    <div id="fullCalModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="panel panel-default formPanel">
                    <div class="panel-heading bg-color-1 border-color-1">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
                        <div class="row" >
                            <div class="col-md-4"> <h4  class="modal-title">Subject</h4></div>

                            <div class="col-md-4">
                                <h5 id="modalTitle" class="modal-title"></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div  class="modal-body">

                    <form class="form-horizontal" id="crud-form">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="description">Subject</label>
                            <div class="col-md-6">
                                <input style="width:100%;" id="subject" name="subject" type="text" class="form-control input-md" />
                            </div>
                        </div>
                        <input type="hidden" id="id_date" value=""/>












                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-danger btn-block " data-dismiss="modal"  onclick="cancelar();">Delete</button></div>


                        <div class="col-md-6">
                        <button  type="button" class="btn btn-success btn-block"  onclick="aceptar();">Save</button>></div>

                    </div>            </div>
                </form>
            </div></div>
    </div>

</body>
</html>

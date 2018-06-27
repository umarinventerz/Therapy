$(document).ready(function(){
   
    $('#time_view').change(function () {
        $("#hide_time").show();     
        $("#hide_recurring").hide();
    });
    $('#recurring').change(function () {                    
        $("#hide_recurring").show();
        $("#hide_time").hide();
    });
    
    $('#mon').change(function () {                    
        if($('#mon').is(':checked')){                        
            $("#mon_hour").removeAttr("disabled");
            $("#mon_minute").removeAttr("disabled");
            $("#mon_type").removeAttr("disabled");
            $("#mon_hour_to").removeAttr("disabled");
            $("#mon_minute_to").removeAttr("disabled");
            $("#mon_type_to").removeAttr("disabled");
        }else{
            $("#mon_hour").attr('disabled', 'disabled');
            $("#mon_minute").attr('disabled', 'disabled');
            $("#mon_type").attr('disabled', 'disabled');
            $("#mon_hour_to").attr('disabled', 'disabled');
            $("#mon_minute_to").attr('disabled', 'disabled');
            $("#mon_type_to").attr('disabled', 'disabled');
        }
    });
    
    $('#tue').change(function () {                    
        if($('#tue').is(':checked')){                        
            $("#tue_hour").removeAttr("disabled");
            $("#tue_minute").removeAttr("disabled");
            $("#tue_type").removeAttr("disabled");
            $("#tue_hour_to").removeAttr("disabled");
            $("#tue_minute_to").removeAttr("disabled");
            $("#tue_type_to").removeAttr("disabled");
        }else{
            $("#tue_hour").attr('disabled', 'disabled');
            $("#tue_minute").attr('disabled', 'disabled');
            $("#tue_type").attr('disabled', 'disabled');
            $("#tue_hour_to").attr('disabled', 'disabled');
            $("#tue_minute_to").attr('disabled', 'disabled');
            $("#tue_type_to").attr('disabled', 'disabled');
        }
    });
    
    $('#wed').change(function () {                    
        if($('#wed').is(':checked')){                        
            $("#wed_hour").removeAttr("disabled");
            $("#wed_minute").removeAttr("disabled");
            $("#wed_type").removeAttr("disabled");
            $("#wed_hour_to").removeAttr("disabled");
            $("#wed_minute_to").removeAttr("disabled");
            $("#wed_type_to").removeAttr("disabled");
        }else{
            $("#wed_hour").attr('disabled', 'disabled');
            $("#wed_minute").attr('disabled', 'disabled');
            $("#wed_type").attr('disabled', 'disabled');
            $("#wed_hour_to").attr('disabled', 'disabled');
            $("#wed_minute_to").attr('disabled', 'disabled');
            $("#wed_type_to").attr('disabled', 'disabled');
        }
    });
    
    $('#thu').change(function () {                    
        if($('#thu').is(':checked')){                        
            $("#thu_hour").removeAttr("disabled");
            $("#thu_minute").removeAttr("disabled");
            $("#thu_type").removeAttr("disabled");
            $("#thu_hour_to").removeAttr("disabled");
            $("#thu_minute_to").removeAttr("disabled");
            $("#thu_type_to").removeAttr("disabled");
        }else{
            $("#thu_hour").attr('disabled', 'disabled');
            $("#thu_minute").attr('disabled', 'disabled');
            $("#thu_type").attr('disabled', 'disabled');
            $("#thu_hour_to").attr('disabled', 'disabled');
            $("#thu_minute_to").attr('disabled', 'disabled');
            $("#thu_type_to").attr('disabled', 'disabled');
        }
    });
    
    $('#fri').change(function () {                    
        if($('#fri').is(':checked')){                        
            $("#fri_hour").removeAttr("disabled");
            $("#fri_minute").removeAttr("disabled");
            $("#fri_type").removeAttr("disabled");
            $("#fri_hour_to").removeAttr("disabled");
            $("#fri_minute_to").removeAttr("disabled");
            $("#fri_type_to").removeAttr("disabled");
        }else{
            $("#fri_hour").attr('disabled', 'disabled');
            $("#fri_minute").attr('disabled', 'disabled');
            $("#fri_type").attr('disabled', 'disabled');
            $("#fri_hour_to").attr('disabled', 'disabled');
            $("#fri_minute_to").attr('disabled', 'disabled');
            $("#fri_type_to").attr('disabled', 'disabled');
        }
    });
    
    $('#sat').change(function () {                    
        if($('#sat').is(':checked')){                        
            $("#sat_hour").removeAttr("disabled");
            $("#sat_minute").removeAttr("disabled");
            $("#sat_type").removeAttr("disabled");
            $("#sat_hour_to").removeAttr("disabled");
            $("#sat_minute_to").removeAttr("disabled");
            $("#sat_type_to").removeAttr("disabled");
        }else{
            $("#sat_hour").attr('disabled', 'disabled');
            $("#sat_minute").attr('disabled', 'disabled');
            $("#sat_type").attr('disabled', 'disabled');
            $("#sat_hour_to").attr('disabled', 'disabled');
            $("#sat_minute_to").attr('disabled', 'disabled');
            $("#sat_type_to").attr('disabled', 'disabled');
        }
    });
    
    $('#sun').change(function () {                    
        if($('#sun').is(':checked')){                        
            $("#sun_hour").removeAttr("disabled");
            $("#sun_minute").removeAttr("disabled");
            $("#sun_type").removeAttr("disabled");
            $("#sun_hour_to").removeAttr("disabled");
            $("#sun_minute_to").removeAttr("disabled");
            $("#sun_type_to").removeAttr("disabled");
        }else{
            $("#sun_hour").attr('disabled', 'disabled');
            $("#sun_minute").attr('disabled', 'disabled');
            $("#sun_type").attr('disabled', 'disabled');
            $("#sun_hour_to").attr('disabled', 'disabled');
            $("#sun_minute_to").attr('disabled', 'disabled');
            $("#sun_type_to").attr('disabled', 'disabled');
        }
    });
    
    
});

function HoraFin(fecha){
    var res = fecha.split("T");
    var hora=res[1];    
    switch(hora){
        case '00:00:00':
            var nueva_hora='01:00:00';
        break;
        case '00:30:00':
             nueva_hora='01:30:00';
        break;
        case '01:00:00':
            var nueva_hora='02:00:00';
        break;
        case '01:30:00':
             nueva_hora='02:30:00';
        break;
        case '02:00:00':
            var nueva_hora='03:00:00';
        break;
        case '02:30:00':
             nueva_hora='03:30:00';
        break;
        
        case '03:00:00':
            var nueva_hora='04:00:00';
        break;
        case '03:30:00':
             nueva_hora='04:30:00';
        break;
        
        case '04:00:00':
            var nueva_hora='05:00:00';
        break;
        case '04:30:00':
             nueva_hora='05:30:00';
        break;
        
        case '05:00:00':
            var nueva_hora='06:00:00';
        break;
        case '05:30:00':
             nueva_hora='06:30:00';
        break;
        
        case '06:00:00':
            var nueva_hora='07:00:00';
        break;
        case '06:30:00':
             nueva_hora='07:30:00';
        break;
        
        case '07:00:00':
            var nueva_hora='08:00:00';
        break;
        case '07:30:00':
             nueva_hora='08:30:00';
        break;
        
        case '08:00:00':
            var nueva_hora='09:00:00';
        break;
        case '08:30:00':
             nueva_hora='09:30:00';
        break;
        
        case '09:00:00':
            var nueva_hora='10:00:00';
        break;
        case '09:30:00':
             nueva_hora='10:30:00';
        break;
        
        case '10:00:00':
            var nueva_hora='11:00:00';
        break;
        case '10:30:00':
             nueva_hora='11:30:00';
        break;
        
        case '11:00:00':
            var nueva_hora='12:00:00';
        break;
        case '11:30:00':
             nueva_hora='12:30:00';
        break;
        
        case '12:00:00':
            var nueva_hora='13:00:00';
        break;
        case '12:30:00':
             nueva_hora='13:30:00';
        break;
        
        case '13:00:00':
            var nueva_hora='14:00:00';
        break;
        case '13:30:00':
             nueva_hora='14:30:00';
        break;
        
        case '14:00:00':
            var nueva_hora='15:00:00';
        break;
        case '14:30:00':
             nueva_hora='15:30:00';
        break;
        
        case '15:00:00':
            var nueva_hora='16:00:00';
        break;
        case '15:30:00':
             nueva_hora='16:30:00';
        break;
        
        case '16:00:00':
            var nueva_hora='17:00:00';
        break;
        case '16:30:00':
             nueva_hora='17:30:00';
        break;
        
        case '17:00:00':
            var nueva_hora='18:00:00';
        break;
        case '17:30:00':
             nueva_hora='18:30:00';
        break;
        
        case '18:00:00':
            var nueva_hora='19:00:00';
        break;
        case '18:30:00':
             nueva_hora='19:30:00';
        break;
        
        case '19:00:00':
            var nueva_hora='20:00:00';
        break;
        case '19:30:00':
             nueva_hora='20:30:00';
        break;
        
        case '20:00:00':
            var nueva_hora='21:00:00';
        break;
        case '20:30:00':
             nueva_hora='21:30:00';
        break;
        
        case '21:00:00':
            var nueva_hora='22:00:00';
        break;
        case '21:30:00':
             nueva_hora='22:30:00';
        break;
        
        case '22:00:00':
            var nueva_hora='23:00:00';
        break;
        case '22:30:00':
             nueva_hora='23:30:00';
        break;
        
        case '23:00:00':
            var nueva_hora='00:00:00';
        break;
        case '23:30:00':
             nueva_hora='00:30:00';
        break;
    }
    var fecha_seteada=res[0]+" "+nueva_hora;
    return fecha_seteada;

}

function search_users(){
    var data=$("#discipline").val();
    $("#users_dinamicos").load("../../../mvc/controlador/calendar/user_dinamicos.php?discipline="+data);
    
    $.ajax({
        type: "POST",
        url: "../../../mvc/controlador/calendar/user_id.php",
        data: {'discipline':data}, 
        success: function(data){  
            
            refrescar_appoiments_discipline(data);
        }
    });
    
}
function iniciar_users(){
    
    $("#users_dinamicos").load("../../../mvc/controlador/calendar/user_dinamicos.php?all=si");
}
function consultar_users(){
    var usuarios= $("#users").val();    
    if(usuarios !==null){
        var events = {
                    url: '../../../mvc/controlador/calendar/consult/listar_appoiments.php',
                    type: 'GET',
                    data: {                    
                        users: usuarios,
                        patients: $("#patients_search").val(),
                        own:$("#own").val(),
                        usuario:$("#own_user").val(),
                    }
                }

            $('#calendario').fullCalendar('removeEventSource', events);
            $('#calendario').fullCalendar('addEventSource', events);
    }else{
        search_users();
    }
}
function refrescar_appoiments_discipline(data){
    
    var events = {
                url: '../../../mvc/controlador/calendar/consult/listar_appoiments.php',
                type: 'GET',
                data: {
                    
                    users: data,
                    patients: $("#patients_search").val(),
                    own:$("#own").val(),
                    usuario:$("#own_user").val(),
                    resfresh:'si',
                }
            }
    $('#calendario').fullCalendar('removeEventSource', events);
    $('#calendario').fullCalendar('addEventSource', events);
    
}

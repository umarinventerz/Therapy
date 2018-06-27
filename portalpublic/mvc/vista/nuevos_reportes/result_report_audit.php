<?php
session_start();
require_once("../../../conex.php");
require_once("../../modelo/FusionCharts.php");
if(!isset($_SESSION['user_id'])){
        echo "<script>alert('Must LOG IN First')</script>";
        echo "<script>window.location='../../../index.php';</script>";
}else{
        if($_SESSION['user_type'] == 2 || !isset($_SESSION['user_id'])){
                echo "<script>alert('PERMISION DENIED FOR THIS USER')</script>";
                echo "<script>window.location='home.php';</script>";
        }
}
                               
$valor = $_GET["valor"];
$conexion = conectar();
$calendar = $_GET["calendar"];
$start_date = $_GET["start_date"];
$end_date = $_GET["end_date"];
 
if(isset($_GET['calendar'])){
    $calendar = $_GET['calendar'];    
}
$having4 = "";
if($_GET['calendar'] == 'weeks'){
    $having4 = "WEEK(created_at)";
}else{
    if($_GET['calendar'] == 'months'){
        $having4 = "MONTHNAME(created_at)";
    }
    if($_GET['calendar'] == 'days'){
        $having4 = "date(created_at)";
    }
}
if(isset($_GET["valor"]) && $_GET["valor"] != null){                
    $valor = str_replace('|',' ',$_GET["valor"]);                                
}

    switch ($_GET['consulta']){
        
        case 1:
        
            $query_usuario = "SELECT CONCAT(trim(e.first_name),' , ',trim(e.last_name)) AS User, CONCAT(trim(R.First_name),' , ',trim(R.Last_name))  as Patient,T.name as type_report,
                    G.created_at as Date
                    FROM tbl_audit_generales G 
                    JOIN employee e on e.id=G.user_id
                    JOIN tbl_referral R on R.id_referral=G.Pat_id
                    JOIN tbl_type_audit_report T on T.id=G.type
                    WHERE ".$having4." = '".$valor."'
                    and date(G.created_at)>= str_to_date('".$start_date."','%m/%d/%Y') and date(G.created_at)<=str_to_date('".$end_date."','%m/%d/%Y') and G.type=1
                    order by  G.created_at desc";
        break;
    
        case 2:
        
            $query_usuario = "SELECT CONCAT(trim(e.first_name),' , ',trim(e.last_name)) AS User, CONCAT(trim(P.First_name),' , ',trim(P.Last_name))  as Patient,T.name as type_report,
                    G.created_at as Date
                    FROM tbl_audit_generales G 
                    JOIN employee e on e.id=G.user_id
                    JOIN patients P on P.Pat_id=G.Pat_id
                    JOIN tbl_type_audit_report T on T.id=G.type
                    WHERE ".$having4." = '".$valor."'
                    and date(G.created_at)>= str_to_date('".$start_date."','%m/%d/%Y') and date(G.created_at)<=str_to_date('".$end_date."','%m/%d/%Y') and G.type=2
                    order by  G.created_at desc";
        break;
    
        case 3:
        
            $query_usuario = "SELECT CONCAT(trim(e.first_name),' , ',trim(e.last_name)) AS User, A.Patient_name  as Patient,A.Discipline,T.name as type_report,
                    G.created_at as Date
                    FROM tbl_audit_generales G 
                    JOIN employee e on e.id=G.user_id
                    JOIN authorizations A on A.id_authorizations=G.Pat_id
                    JOIN tbl_type_audit_report T on T.id=G.type
                    WHERE ".$having4." = '".$valor."'
                    and date(G.created_at)>= str_to_date('".$start_date."','%m/%d/%Y') and date(G.created_at)<=str_to_date('".$end_date."','%m/%d/%Y') and G.type=3
                    order by  G.created_at desc";
        break;
    
        case 4:
        
            $query_usuario = "SELECT CONCAT(trim(e.first_name),' , ',trim(e.last_name)) AS User, S.Patient_name  as Patient,S.Discipline,T.name as type_report,
                    G.created_at as Date
                    FROM tbl_audit_generales G 
                    JOIN employee e on e.id=G.user_id
                    JOIN signed_doctor S on S.id_signed_doctor=G.Pat_id
                    JOIN tbl_type_audit_report T on T.id=G.type
                    WHERE ".$having4." = '".$valor."'
                    and date(G.created_at)>= str_to_date('".$start_date."','%m/%d/%Y') and date(G.created_at)<=str_to_date('".$end_date."','%m/%d/%Y') and G.type=4
                    order by  G.created_at desc";
        break;
    
        case 5:
        
            $query_usuario = "SELECT CONCAT(trim(e.first_name),' , ',trim(e.last_name)) AS User, P.Patient_name  as Patient,P.Discipline,T.name as type_report,
                    G.created_at as Date
                    FROM tbl_audit_generales G 
                    JOIN employee e on e.id=G.user_id
                    JOIN prescription P on P.id_prescription=G.Pat_id
                    JOIN tbl_type_audit_report T on T.id=G.type
                    WHERE ".$having4." = '".$valor."'
                    and date(G.created_at)>= str_to_date('".$start_date."','%m/%d/%Y') and date(G.created_at)<=str_to_date('".$end_date."','%m/%d/%Y') and G.type=5
                    order by  G.created_at desc";
        break;
    
        case 6:
        
            $query_usuario = "SELECT CONCAT(trim(e.first_name),' , ',trim(e.last_name)) AS User, CONCAT(trim(P.First_name),' , ',trim(P.Last_name))  as Patient,A.type_report,A.dicipline,
                    G.created_at as Date
                    FROM tbl_audit_generales G 
                    JOIN employee e on e.id=G.user_id
                    JOIN patients P on P.Pat_id=G.Pat_id
                    JOIN tbl_audit_report_amount_reporting_dicipline A on A.id_audit_report=G.id
                    WHERE ".$having4." = '".$valor."'
                    and date(G.created_at)>= str_to_date('".$start_date."','%m/%d/%Y') and date(G.created_at)<=str_to_date('".$end_date."','%m/%d/%Y') and G.type=6
                    order by  G.created_at desc";
        break;
    
        case 7:
         
                $query_usuario = "SELECT CONCAT(trim(e.first_name),' , ',trim(e.last_name)) AS User, CONCAT(trim(R.First_name),' , ',trim(R.Last_name))  as Patient,
                    'NO APPLY' AS Discipline, T.name as type_report,
                    G.created_at as Date
                    FROM tbl_audit_generales G 
                    JOIN employee e on e.id=G.user_id
                    JOIN tbl_referral R on R.id_referral=G.Pat_id
                    JOIN tbl_type_audit_report T on T.id=G.type
                    WHERE ".$having4." = '".$valor."'
                    and date(G.created_at)>= str_to_date('".$start_date."','%m/%d/%Y') and date(G.created_at)<=str_to_date('".$end_date."','%m/%d/%Y') and G.type=1
            UNION 
                   SELECT CONCAT(trim(e.first_name),' , ',trim(e.last_name)) AS User, CONCAT(trim(P.First_name),' , ',trim(P.Last_name))  as Patient,
                   'NO APPLY' AS Discipline , T.name as type_report,
                    G.created_at as Date
                    FROM tbl_audit_generales G 
                    JOIN employee e on e.id=G.user_id
                    JOIN patients P on P.Pat_id=G.Pat_id
                    JOIN tbl_type_audit_report T on T.id=G.type
                    WHERE ".$having4." = '".$valor."'
                    and date(G.created_at)>= str_to_date('".$start_date."','%m/%d/%Y') and date(G.created_at)<=str_to_date('".$end_date."','%m/%d/%Y') and G.type=2
                
            UNION
                    SELECT CONCAT(trim(e.first_name),' , ',trim(e.last_name)) AS User, A.Patient_name  as Patient,A.Discipline,T.name as type_report,
                    G.created_at as Date
                    FROM tbl_audit_generales G 
                    JOIN employee e on e.id=G.user_id
                    JOIN authorizations A on A.id_authorizations=G.Pat_id
                    JOIN tbl_type_audit_report T on T.id=G.type
                    WHERE ".$having4." = '".$valor."'
                    and date(G.created_at)>= str_to_date('".$start_date."','%m/%d/%Y') and date(G.created_at)<=str_to_date('".$end_date."','%m/%d/%Y') and G.type=3
            UNION
                    SELECT CONCAT(trim(e.first_name),' , ',trim(e.last_name)) AS User, S.Patient_name  as Patient,S.Discipline,T.name as type_report,
                    G.created_at as Date
                    FROM tbl_audit_generales G 
                    JOIN employee e on e.id=G.user_id
                    JOIN signed_doctor S on S.id_signed_doctor=G.Pat_id
                    JOIN tbl_type_audit_report T on T.id=G.type
                    WHERE ".$having4." = '".$valor."'
                    and date(G.created_at)>= str_to_date('".$start_date."','%m/%d/%Y') and date(G.created_at)<=str_to_date('".$end_date."','%m/%d/%Y') and G.type=4
            UNION
                    SELECT CONCAT(trim(e.first_name),' , ',trim(e.last_name)) AS User, P.Patient_name  as Patient,P.Discipline,T.name as type_report,
                    G.created_at as Date
                    FROM tbl_audit_generales G 
                    JOIN employee e on e.id=G.user_id
                    JOIN prescription P on P.id_prescription=G.Pat_id
                    JOIN tbl_type_audit_report T on T.id=G.type
                    WHERE ".$having4." = '".$valor."'
                    and date(G.created_at)>= str_to_date('".$start_date."','%m/%d/%Y') and date(G.created_at)<=str_to_date('".$end_date."','%m/%d/%Y') and G.type=5
            UNION
                    SELECT CONCAT(trim(e.first_name),' , ',trim(e.last_name)) AS User, CONCAT(trim(P.First_name),' , ',trim(P.Last_name))  as Patient,A.dicipline,A.type_report,
                    G.created_at as Date
                    FROM tbl_audit_generales G 
                    JOIN employee e on e.id=G.user_id
                    JOIN patients P on P.Pat_id=G.Pat_id
                    JOIN tbl_audit_report_amount_reporting_dicipline A on A.id_audit_report=G.id
                    WHERE ".$having4." = '".$valor."'
                    and date(G.created_at)>= str_to_date('".$start_date."','%m/%d/%Y') and date(G.created_at)<=str_to_date('".$end_date."','%m/%d/%Y') and G.type=6
                     ;";
            
        break;
    }

                    
$resultado = ejecutar($query_usuario,$conexion);

$reporte = array();

$i = 0;      
while($datos = mysqli_fetch_assoc($resultado)) {            
    $reporte[$i] = $datos;
    $i++;
}              
$i=0; 
while (key($reporte[0])) {

    $reporte_clave[$i] = key($reporte[0]);  
    next($reporte[0]);
    $i++;
} 
?> 
 
<script type="text/javascript" language="javascript" src="../../../js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.bootstrap.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/resources/shCore.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/dataTables.buttons.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/dataTables/buttons.html5.js"></script>
<script>
    $(document).ready(function() {
            $('#tabla_grafico').DataTable({
                    pageLength: 1500,
                    dom: 'Bfrtip',
                    "scrollX": true,
                    buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5',
                            'pdfHtml5'
                    ]
            });
    } );

    $('html,body').animate({scrollTop: $("#bajar_aqui").offset().top}, 800);

</script>
    
<div id="bajar_aqui"></div>
<div class="row">
    <div class="col-lg-12 text-center"><b><h4>DETALLE DEL GRAFICO</h4></b></div>    
</div>
<br>

<div class="col-lg-12">

        <table id="tabla_grafico" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                    <tr>
                        <?php
                        $s=0;
                        while(isset($reporte_clave[$s])){
                            echo '<th>'.$reporte_clave[$s].'</th>';
                            $s++;
                        }
                        ?>
                    </tr>
            </thead>                                      

            <tbody>
                <?php
                $i = 0;
                $color = '<tr class="odd_gradeX">';
                while (isset($reporte[$i])) {

                    echo $color;

                        $r=0;
                        while(isset($reporte_clave[$r])){

                            echo '<td>'.$reporte[$i][$reporte_clave[$r]].'</td>';

                            $r++;
                        }


                    $color = ($color == '<tr class="even_gradeC">' ? '<tr class="odd_gradeX">' : '<tr class="even_gradeC">');

                    echo '</tr>';

                    $i++;
                }
                ?>
            <tbody/>
    </table> 

</div>
                

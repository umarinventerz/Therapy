<?php
session_start();
require_once("conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'Must LOG IN First\')</script>';
	echo '<script>window.location="index.php";</script>';
}

$conexion = conectar();
$sql  = "SELECT 
count(MONTHNAME(STR_TO_DATE(tbl_treatments.campo_1,'%m/%d/%Y'))) as total,
MONTHNAME(STR_TO_DATE(tbl_treatments.campo_1,'%m/%d/%Y')) as month

from tbl_treatments

where YEAR(STR_TO_DATE(tbl_treatments.campo_1,'%m/%d/%Y'))='2016'

group by  month";
$resultado = ejecutar($sql,$conexion);

$reporte = array();

$i = 0;      
while($row = mysqli_fetch_assoc($resultado)) {            
    $orders[] = array(
			'month' => $row['month'],
			//'ProductName' => $row['ProductName'],
			'total' => $row['total']
		  );
		$i++;
}
echo json_encode($orders);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>.: THERAPY  AID :.</title>

 <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="all" href="daterangepicker/daterangepicker.css" />
    <script type="text/javascript" src="daterangepicker/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>



    <link rel="stylesheet" href="chart/jqwidgets/styles/jqx.base.css" type="text/css" />
<script type="text/javascript" src="chart/scripts/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="chart/jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="chart/jqwidgets/jqxchart.js"></script>	
<script type="text/javascript" src="chart/jqwidgets/jqxdata.js"></script>


</head>

<body>
    
    
    <br><br>
    
    <div class="container">        
        <div class="row">
            <div class="col-lg-12">
                <img class="img-responsive portfolio-item" src="images/LOGO_1.png" alt="">
            </div>
        </div>
        <!-- /.row -->

        <div id="jqxChart"></div>




  <script type="text/javascript">
	$(document).ready(function () {
		var source =
		{
			 datatype: "json",
			 datafields: [
				 { name: 'month'},
				 { name: 'total'}
			],
			url: 'prueba.php'
		};		
		
	   var dataAdapter = new $.jqx.dataAdapter(source,
		{
			autoBind: true,
			async: false,
			downloadComplete: function () { },
			loadComplete: function () { },
			loadError: function () { }
		});
		
	 // prepare jqxChart settings
		var settings = {
			title: "Orders by Month",
			showLegend: true,
			padding: { left: 5, top: 5, right: 5, bottom: 5 },
			titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
			source: dataAdapter,
			categoryAxis:
				{
					text: 'Category Axis',
					textRotationAngle: 0,
					dataField: 'month',
					//formatFunction: function (value) {
					//	return $.jqx.dataFormat.formatdate(value, 'dd/MM/yyyy');
					//},
					showTickMarks: true,
					tickMarksInterval: Math.round(dataAdapter.records.length / 6),
					tickMarksColor: '#888888',
					unitInterval: Math.round(dataAdapter.records.length / 6),
					showGridLines: true,
					gridLinesInterval: Math.round(dataAdapter.records.length / 3),
					gridLinesColor: '#888888',
					axisSize: 'auto'                    
				},
			colorScheme: 'scheme05',
			seriesGroups:
				[
					{
						type: 'line',
						valueAxis:
						{
							displayValueAxis: true,
							description: 'total',
							//descriptionClass: 'css-class-name',
							axisSize: 'auto',
							tickMarksColor: '#888888',
							unitInterval: 20,
							minValue: 0,
							maxValue: 100                          
						},
						series: [
								{ dataField: 'total', displayText: 'total' }
						  ]
					}
				]
		};
		// setup the chart
		$('#jqxChart').jqxChart(settings);
	});
</script>

</body>
</html>

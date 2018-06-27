<?php
session_start();
require_once("../../../conex.php");
if(!isset($_SESSION['user_id'])){
	echo '<script>alert(\'MUST LOG IN\')</script>';
	echo '<script>window.location="../../../index.php";</script>';
}

$pat_id = $_GET['Patient_id'];
$discipline = $_GET['Discipline'];
$company = $_GET['Company'];
$pat_name = $_GET['patient_name'];
$type = $_GET['type'];
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
	<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../../css/style.css">
	<link rel="stylesheet" href="../../../css/jquery.fileupload.css">
	<link rel="stylesheet" href="../../../css/jquery.fileupload-ui.css">
	<!-- CSS adjustments for browsers with JavaScript disabled -->
	<noscript><link rel="stylesheet" href="../../../css/jquery.fileupload-noscript.css"></noscript>
	<noscript><link rel="stylesheet" href="../../../css/jquery.fileupload-ui-noscript.css"></noscript>
	<script src="../../../js/jquery.min.js"></script>
	<script src="../../../js/bootstrap.min.js"></script>

	<script src="../../../js/vendor/jquery.ui.widget.js"></script>
	<script src="../../../js/tmpl.min.js"></script>
	<script src="../../../js/load-image.all.min.js"></script>
	<script src="../../../js/jquery.fileupload.js"></script>
	<script src="../../../js/jquery.fileupload-process.js"></script>
	<script src="../../../js/jquery.fileupload-image.js"></script>
	<script src="../../../js/jquery.fileupload-validate.js"></script>
	<script src="../../../js/jquery.fileupload-ui.js"></script>
	<script src="../../../js/main.js"></script>
	<script src="js/fileinput.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/bootstrap-filestyle.js"></script>
    <script>		
	/*function sendMail(pat_id,discipline,company,patient_name,cpt,type){
		subject = $("#subject").val();
		mail = $("#mail").val();
		$("#result_m"+pat_id).load("sendMail.php","&subject="+subject+"&mail="+mail+"&Patient_id="+pat_id+"&Discipline="+discipline+"&Company="+company+"&patient_name="+patient_name);
	}*/
	function load_form(){
		window.open('../form_pif/form_pif.php?Discipline='+$('#Discipline').val()+'&patient_id='+$('#Patient_id').val()+'&patient_name='+$('#patient_name').val(),'','width=2500px,height=700px,noresize');		
	}
    </script>
</head>

<body>

    <!-- Navigation 
    <?php //$perfil = $_SESSION['user_type']; include "nav_bar.php"; ?>  -->


    <!-- Page Content -->
    <div class="container">

        <!-- Portfolio Item Heading -->
        <div class="row" >
            <div class="col-lg-12">
      		<br><br><br>
		<img class="img-responsive portfolio-item" src="../../../images/LOGO_1.png" alt="">

		<h3><?php echo '<b>User:</b> '.$_SESSION['first_name'].' '.$_SESSION['last_name']?></h3>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <form id="fileupload" role="form" enctype="multipart/form-data" action="../../controlador/report_mail/sendMail.php" method="post">
		
		<input type="hidden" name="Patient_id" id="Patient_id" value="<?php echo $pat_id;?>">
		<input type="hidden" name="Discipline" id="Discipline" value="<?php echo $discipline;?>">
		<input type="hidden" name="Company" id="Company" value="<?php echo $company;?>">
		<input type="hidden" name="patient_name" id="patient_name" value="<?php echo $pat_name;?>">
		<input type="hidden" name="type" id="type" value="<?php echo $type;?>">		
		<div class="row" style="margin : 2px;">
		    
			<div class="form-group">

			    <label for="inputEmail">To</label>

			    <input type="text" class="form-control" id="to" name="to" placeholder="To">

			</div>

			<div class="form-group">

			    <label for="inputEmail">Subject</label>

			    <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject">

			</div>

			<div class="form-group">

			    <label for="inputPassword">Mail</label>

			    <textarea name='mail' id='mail' class='form-control' style="height : 300px;"></textarea>

			</div>
                    
                        <div class="form-group">

			    <label for="inputEmail">¿Do you want to send this an automatic text message by email?</label>

                            <input type="checkbox" id="check_sms" name="check_sms" placeholder="To">
                            <label for="inputEmail">¿Do you want to send this an automatic text message by Ring central?</label>

                            <input type="checkbox" id="check_sms_ring_central" name="check_sms_ring_central" placeholder="To">

			</div>

		 </div>
		 <div class="row fileupload-buttonbar">
		    <div class="col-lg-7">
		        <!-- The fileinput-button span is used to style the file input field as button -->
		        <span class="btn btn-success fileinput-button">
		            <i class="glyphicon glyphicon-plus"></i>
		            <span>Add files...</span>
		            <input type="file" name="files[]" multiple>
		        </span>
		        <button type="submit" class="btn btn-primary start">
		            <i class="glyphicon glyphicon-upload"></i>
		            <span>Start upload</span>
		        </button>
		        <button type="reset" class="btn btn-warning cancel">
		            <i class="glyphicon glyphicon-ban-circle"></i>
		            <span>Cancel upload</span>
		        </button>
		        <button type="button" class="btn btn-danger delete">
		            <i class="glyphicon glyphicon-trash"></i>
		            <span>Delete</span>
		        </button>
		        <input type="checkbox" class="toggle">
		        <!-- The global file processing state -->
		        <span class="fileupload-process"></span>
		    </div>
		    <!-- The global progress state -->
		    <div class="col-lg-5 fileupload-progress fade">
		        <!-- The global progress bar -->
		        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
		            <div class="progress-bar progress-bar-success" style="width:0%;"></div>
		        </div>
		        <!-- The extended global progress state -->
		        <div class="progress-extended">&nbsp;</div>
		    </div>
		</div>
		<!-- The table listing the files available for upload/download -->
		<table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
	

		  <div class="row" style="margin : 2px;">
			 <div class="col-xs-2">
				<button type="submit" class="btn btn-primary">Send</button>
			</div>	
			<div class="col-xs-2">
				<button type="button" onclick="load_form();" class="btn btn-primary">Load Form</button>
			</div>				
		</div>
        </form>

            <div class="col-md-4">                
            </div>

        </div>
        <!-- /.row -->

        <!-- Related Projects Row -->
        <div class="row">

        </div>
        <!-- /.row -->

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; THERAPY AID 2016</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->
  <script id="template-upload" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
	    <tr class="template-upload fade">
		<td>
		    <span class="preview"></span>
		</td>
		<td>
		    <p class="name">{%=file.name%}</p>
		    <strong class="error text-danger"></strong>
		</td>
		<td>
		    <p class="size">Processing...</p>
		    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
		</td>
		<td>
		    {% if (!i && !o.options.autoUpload) { %}
		        <button class="btn btn-primary start" disabled>
		            <i class="glyphicon glyphicon-upload"></i>
		            <span>Start</span>
		        </button>
		    {% } %}
		    {% if (!i) { %}
		        <button class="btn btn-warning cancel">
		            <i class="glyphicon glyphicon-ban-circle"></i>
		            <span>Cancel</span>
		        </button>
		    {% } %}
		</td>
	    </tr>
	{% } %}
    </script>
    <script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
</body>

</html>

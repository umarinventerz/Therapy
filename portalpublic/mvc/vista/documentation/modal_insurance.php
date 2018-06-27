<?php
session_start();
require_once("../../../conex.php");
// if(!isset($_SESSION['user_id'])){
//   echo '<script>alert(\'Must LOG IN First\')</script>';
//   echo '<script>window.location=../../../"index.php";</script>';
// }
?>


<!doctype html>
<html>


<script type="text/javascript">
$(document).ready(function() {
  $(".js-example-basic-single").select2();
});
</script>
<body>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 align="center" class="modal-title" id="exampleModalLabel">P A Y E E</h4>
            </div>
            <div id="newUserHTMLBody">
                <form method="post" action="#" role="form" id="new_user_form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" class="nofocus">
                    <div class="modal-body">
                        
                                      <div class="row">

                                  <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                     
                                          <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                   <select style="width:280px; padding: 3px 1px; "  name='insurance' id='insurance' class="populate placeholder" required>
                                                    <option value=''>--- SELECT ---</option>        
                                                    <?php
                                                      $conexion = conectar();
                                                      $sql  = "Select Distinct id, insurance from seguros order by insurance ASC ";  
                                                      $resultado = ejecutar($sql,$conexion);
                                                      while ($row=mysqli_fetch_array($resultado)) 
                                                      {                                                       
                                                      print("<option value='".$row["id"]."'>".$row["insurance"]."</option>");
                                                      }
                                                      ?>
                                               </select>                                   
                                         <label  for="insurance">
                                                
                                                    INSURER
                                                </label>
                                     </div>
                                  </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       id="first_name"
                                                       name="first_name"
                                                       class="form-control"
                                                       placeholder=""
                                                       maxlength=30
                                                       minlength=3
                                                       required="required">
                                                <label for="first_name">
                                                   MEMBER #
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                            <div class="form-group form-md-line-input form-md-floating-label input-icon right">
                                                <input type="text"
                                                       id="username"
                                                       name="username"
                                                       class="form-control tooltips"
                                                       placeholder=""
                                                       data-original-title=""
                                                       maxlength=20
                                                       minlength=3
                                                       required="required">
                                                <label for="username">
                                                   GROUP #
                                                </label>
                                            </div>
                                        </div>
                                        <div align="center" class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div style="max-height:1px !important; margin-top: 1.5px;"  class="checkbox checkbox-success checkbox-circle ">
                                                <input id="checkbox10" class="styled" type="checkbox" checked>
                                                <label style="font-weight:bold;" for="checkbox10">
                                                ACTIVE
                                                </label>
                                            </div>
                                        </div>
                                      

                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <div class="">
                                  
                                <a class="btn btn-default" data-dismiss="modal" id="cancel" name="cancel">
                                    <i class="fa fa-ban"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary" name="save" id="newUserButton">
                                    <i class="fa fa-floppy-o"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>



</body>

    <script type="text/javascript">

$(document).ready(function() {
                $('#insurance').select2();
               
                
              
    });

</script>


</html>


      
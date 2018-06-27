<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>.: THERAPY  AIDd :.</title>
    <link href="plugins_portal/plugins/jquery-ui/jquery-ui.css" rel="stylesheet">
    <link href="plugins_portal/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="plugins_portal/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="plugins_portal/plugins/rs-plugin/css/settings.css" media="screen">
    <link rel="stylesheet" type="text/css" href="plugins_portal/plugins/selectbox/select_option1.css">
    <link rel="stylesheet" type="text/css" href="plugins_portal/plugins/owl-carousel/owl.carousel.css" media="screen">
    <link rel="stylesheet" type="text/css" href="plugins_portal/plugins/isotope/jquery.fancybox.css">
    <link rel="stylesheet" type="text/css" href="plugins_portal/plugins/isotope/isotope.css">

    <!-- GOOGLE FONT -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Dosis:400,300,600,700' rel='stylesheet' type='text/css'>

    <!-- CUSTOM CSS -->
    <link href="plugins_portal/css/style.css" rel="stylesheet">
    <link rel="plugins_portal/stylesheet" href="css/default.css" id="option_color">

    <!-- Icons -->
    <link rel="shortcut icon" href="img/favicon.png">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Icons -->
    <link rel="shortcut icon"src="images/LOGO_1.png">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->




</head>

<body class="body-wrapper"
<div class="main-wrapper">
    <!-- HEADER -->
    <header id="pageTop" class="header-wrapper">
        <!-- COLOR BAR -->
        <div class="container-fluid color-bar top-fixed clearfix">
            <div class="row">
                <div class="col-sm-1 col-xs-2 bg-color-1">fix bar</div>

                <div class="col-sm-1 col-xs-2 bg-color-2">fix bar</div>
                <div class="col-sm-1 col-xs-2 bg-color-3">fix bar</div>
                <div class="col-sm-1 col-xs-2 bg-color-4">fix bar</div>
                <div class="col-sm-1 col-xs-2 bg-color-5">fix bar</div>
                <div class="col-sm-1 col-xs-2 bg-color-6">fix bar</div>
                <div class="col-sm-1 bg-color-1 hidden-xs">fix bar</div>
                <div class="col-sm-1 bg-color-2 hidden-xs">fix bar</div>
                <div class="col-sm-1 bg-color-3 hidden-xs">fix bar</div>
                <div class="col-sm-1 bg-color-4 hidden-xs">fix bar</div>
                <div class="col-sm-1 bg-color-5 hidden-xs">fix bar</div>
                <div class="col-sm-1 bg-color-6 hidden-xs">fix bar</div>
            </div>

        </div>



    </header>
    <!-- NAVBAR -->
    <nav id="menuBar" class="navbar navbar-default lightHeader" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
 <a class="navbar-brand"><img src="images/LOGO_1.png" alt="Therapi Aid"></a>
      
                <a class="navbar-brand" > </a>
            </div>




        </div>
    </nav>
    </header>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">


                        <form class="form-horizontal" role="form"  action="mvc/controlador/authenticate/authenticate_user.php" method="post">

                        <div class="form-group">
                            <label for="name" class="col-md-4 control-label">User name</label>

                            <div class="col-md-6">

                                <input type="text" class="form-control" name="inputUser" id="inputUser" placeholder="UserName">

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="inputPassword" id="inputPassword" placeholder="Password">


                            </div>
                        </div>



                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-sign-in"></i> Login
                                </button>
                                <div id="result" >
                                    <?php if(isset($_SESSION['error_login'])) {
                                        echo '<p class="text-danger">Error: '.$_SESSION['error_login'].'.</p>'; unset($_SESSION['error_login']);
                                    }
                                    ?>
                                </div>


                            </div>
                        </div>
                    </form>



                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                    <form align="left" action="demo_form.asp" method="get" style="font-family:verdana;color:darkblue;">
                        <input   type="checkbox"> Remember me
                        <hr/>
                    </form>
                    <form align="left" action="demo_form.asp" method="get" style="font-family:verdana;">
                        <a href="#"><span class="opcion_menu">Forgot Password ?</span></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="change-password.php"><span class="opcion_menu">Change Password</span></a>
                    </form>
                    </div>
                    </div>



                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <h7>


 
            </h7>
            <br>
            <br>
            <h7>


            </h7>

        </div>
    </div>

</div>



<!-- Contenidoooo

 <!--aqui van las cosas qu me va a ghacer falta


 -->







<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="plugins_portal/plugins/jquery-ui/jquery-ui.js"></script>
<script src="plugins_portal/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="plugins_portal/plugins/rs-plugin/js/jquery.themepunch.tools.min.js"></script>
<script src="plugins_portal/plugins/rs-plugin/js/jquery.themepunch.revolution.min.js"></script>
<script src="plugins_portal/plugins/selectbox/jquery.selectbox-0.1.3.min.js"></script>
<script src="plugins_portal/plugins/owl-carousel/owl.carousel.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
<script src="plugins_portal/plugins/counter-up/jquery.counterup.min.js"></script>
<script src="plugins_portal/plugins/isotope/isotope.min.js"></script>
<script src="plugins_portal/plugins/isotope/jquery.fancybox.pack.js"></script>
<script src="plugins_portal/plugins/isotope/isotope-triger.js"></script>
<script src="plugins_portal/plugins/countdown/jquery.syotimer.js"></script>
<script src="plugins_portal/plugins/velocity/velocity.min.js"></script>
<script src="plugins_portal/plugins/smoothscroll/SmoothScroll.js"></script>
<script src="plugins_portal/js/custom.js"></script>





</body>


</html>


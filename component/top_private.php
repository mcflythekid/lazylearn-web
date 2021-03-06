<?php function top_private(){
    global $TITLE;
    global $HEADER;
    global $HEADER2;
    global $PATHS;
    global $ASSET;
    global $CTX;
    global $ENDPOINT;
    global $lang;
    ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?=asset()?>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/bower_components/admin-lte/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/bower_components/admin-lte/dist/css/skins/_all-skins.min.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="/bower_components/morris.js/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="/bower_components/jvectormap/jquery-jvectormap.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/bower_components/bootstrap-daterangepicker/daterangepicker.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- jQuery UI 1.11.4 -->
    <script src="/bower_components/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Morris.js charts -->
    <script src="/bower_components/raphael/raphael.min.js"></script>
    <script src="/bower_components/morris.js/morris.min.js"></script>
    <!-- Sparkline -->
    <script src="/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="/bower_components/admin-lte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="/bower_components/admin-lte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="/bower_components/moment/min/moment.min.js"></script>
    <script src="/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="/bower_components/admin-lte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Slimscroll -->
    <script src="/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="/bower_components/fastclick/lib/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="/bower_components/admin-lte/dist/js/adminlte.min.js"></script>

</head>
<body class="hold-transition skin-purple-light sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="/dashboard.php" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>Lazy</b></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>LazyLearn</b></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- <img src="/bower_components/admin-lte/dist/img/avatar04.png" class="user-image" alt="User Image"> -->
                                [ <span class="hidden-xs" id="app__user_fullname"></span> ]
                            </a>
                            <ul class="dropdown-menu">

                                <!-- User image -->
                                <li class="user-header">
                                    <!-- <img src="/bower_components/admin-lte/dist/img/avatar04.png" class="img-circle" alt="User Image"> -->
                                    <p id="app__user_email"><?= $lang["layout.private.email_not_set"] ?></p>
                                </li>

                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="row">
                                        <div class="col-xs-4 text-center">
                                            <a href="/auth/security.php"><?= $lang["layout.private.security"] ?></a>
                                        </div>
                                    </div>
                                    <!-- /.row -->
                                </li>

                                <li class="user-footer">
                                    <div class="pull-left" id="app__user_changepassword">
                                        <a href="/auth/change-password.php" class="btn btn-default btn-flat "><?= $lang["layout.private.change_password"] ?></a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="#" id="logout" class="btn btn-default btn-flat"><?= $lang["layout.private.logout"] ?></a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <ul class="sidebar-menu" data-widget="tree">

                    <li class="header"><?= $lang["layout.private.menu.main.id"] ?></li>
                    <li><a href="/dashboard.php"><i class="fa fa-home"></i> <span><?= $lang["layout.private.menu.main.lazy"] ?></span></a></li>
                    <li><a href="/deck.php"><i class="fa fa-bars"></i> <span><?= $lang["layout.private.menu.main.deck"] ?></span></a></li>
                    <li><a href="/vocabulary"><i class="fa fa-language"></i> <span><?= $lang["layout.private.menu.main.vocab"] ?></span></a></li>
                    <li><a href="/minpair"><i class="fa fa-circle-o text-aqua"></i> <span><?= $lang["layout.private.menu.main.minpair"] ?></span></a></li>
                    <li><a href="/article"><i class="fa fa-circle-o text-green"></i> <span><?= $lang["layout.private.menu.main.topic"] ?></span></a></li>
                    
                    <li class="admin_menu header">Admin</li>
                    <li class="admin_menu"><a href="/minpair/admin.php"><i class="fa fa-circle-o text-aqua"></i> Manage Minpair</a></li>
                    <li class="admin_menu"><a href="/article/admin.php"><i class="fa fa-circle-o text-green"></i> Manage Topic</a></li>
                    <li class="admin_menu"><a href="/admin/user.php"><i class="fa fa-circle-o text-red"></i> Manage User</a></li>
                    <li class="admin_menu"><a href="/admin/panel.php"><i class="fa fa-circle-o text-yellow"></i> Advanced Settings</a></li>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>

        <script>
            $(document).on('click', '#logout', function(event){
                event.preventDefault();
                Auth.logout();
            });
			$(document).ready(()=>{
				Application.privatePagesCheck();
            });
            
            /**
             * Language change
             */
            $(document).on('click', 'a.lang', function(event){
                event.preventDefault();
                const lang = $(this).attr('data-lang');
                axios.get("/lang/change.php?lang=" + lang).finally(()=>{
                location.reload();
                });
            });
        </script>

            <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    <?=$HEADER?>
                    <small><?=$HEADER2?></small>
                </h1>

                <?php if(is_array($PATHS)) { ?>
                <ol class="breadcrumb">

                    <li><a href="/dashboard.php"><i class="fa fa-home"></i> LazyLearn</a></li>

                    <?php foreach($PATHS as $key=>$path) { ?>
                        <?php if ($key < sizeof($PATHS) - 1){ ?>
                        <li><a href="<?=$path[0]?>"><?=$path[1]?></a></li>
                        <?php } else { ?>
                        <li class="active"><?=$path?></li>
                        <?php } ?>
                    <?php }?>

                </ol>
                <?php }?>
            </section>

            <!-- Main content -->
            <section class="content">
<!------------------------------------------------------------------------------------------------------------------------->
<?php } ?>

<?php function top_private(){
    global $TITLE;
    global $HEADER;
    global $HEADER2;
    global $PATHS;
    global $ASSET;
    global $CTX;
    global $ENDPOINT;
    ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$TITLE?></title>
    <link id ="shorticon" rel="shortcut icon" href="<?=$ASSET?>/img/fav.png" >
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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <!-- jQuery 3 -->
    <script src="/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="/bower_components/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.7 -->
    <script src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
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
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="/bower_components/admin-lte/dist/js/pages/dashboard.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="/bower_components/admin-lte/dist/js/demo.js"></script>

    <!--    ------------------------------------------------------------------------------------------------------------------------>
    <script type="text/javascript" src="//www.gstatic.com/charts/loader.js"></script>

    <!-- axios -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/axios/0.17.1/axios.js"></script>

    <!-- Include the Quill library -->
    <link href="//cdn.quilljs.com/1.3.4/quill.snow.css" rel="stylesheet">
    <script src="//cdn.quilljs.com/1.3.4/quill.js"></script>

    <!-- bootstrap dialog -->
    <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- bootstrap table -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.1/bootstrap-table.min.js"></script>

    <!-- app -->
    <link rel="stylesheet" href="<?=$ASSET?>/app.css">

    <script>
        var ctx = '<?=$CTX?>';
        var endpoint = '<?=$ENDPOINT?>';
    </script>
    <script src="<?=$ASSET?>/tool.js"></script>
    <script src="<?=$ASSET?>/app.js"></script>
    <script src="<?=$ASSET?>/external/quill-magic-url.min.js"></script>
    <script src="<?=$ASSET?>/external/image-resize.min.js"></script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="/dashboard.php" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>Lazy</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Lazylearn</b></span>
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
                            <img src="/bower_components/admin-lte/dist/img/avatar04.png" class="user-image" alt="User Image">
                            <span class="hidden-xs" id="app__user_fullname"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="/bower_components/admin-lte/dist/img/avatar04.png" class="img-circle" alt="User Image">
                                <p id="app__user_email">[Email is not set]</p>
                            </li>

                            <li class="user-footer">
                                <div class="pull-left" id="app__user_changepassword">
                                    <a href="/change-password.php" class="btn btn-default btn-flat">Change password</a>
                                </div>
                                <div class="pull-right">
                                    <a href="#" id="logout" class="btn btn-default btn-flat">Sign out</a>
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
                <li>
                    <a href="/dashboard.php">
                        <i class="fa fa-th"></i> <span>Home</span>
                    </a>
                </li>
                <li class="">
                    <a href="/deck.php">
                        <i class="fa fa-folder-open-o"></i> <span>Deck</span>
                    </a>
                </li>
                <li id="admin_menu" style="display: none">
                    <a href="/admin.php">
                        <i class="fa fa-group"></i> <span>Admin</span>
                    </a>
                </li>
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <script>
        (()=>{
            $(document).on('click', '#logout', function(event){
                event.preventDefault();
                $app.logout();
            });

            var auth = $tool.getData('auth');
            if (auth){
                if (auth.email) {
                    $('#app__user_email').text(auth.email);
                } else {
                    $('#app__user_changepassword').hide();
                }
                $('#app__user_fullname').text(auth.fullName);
                if (auth.email === 'odopoc@gmail.com'){
                    $('#admin_menu').show();
                }
                $('.ui--in').show();
                $('.ui--out').hide();
            }else {
                $('.ui--in').hide();
                $('.ui--out').show();
            }
        })();
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

                <li><a href="/dashboard.php"><i class="fa fa-th"></i> Home</a></li>

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
<?php } ?>
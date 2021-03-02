<?php
include_once('app/Database.php');
include_once('app/App.php');
include_once('app/Auth.php');
$app = new \app\App();
$auth = new \app\Auth();
/*
if ($_SESSION['forIDNumber']!=='true') {
    header('location: index.php');
}*/

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords"
          content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="shortcut icon" href="resources/img/icons/icon-48x48.png"/>
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.23/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.7/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.css"/>
    <link rel="canonical" href="https://demo.adminkit.io/pages-blank.html"/>

    <title>MRMS | Register</title>
    <link href="resources/css/app.css" rel="stylesheet">
    <script type="text/css">
        .choices[data-type*="select-one"] select.choices__input {
            display: block !important;
            opacity: 0;
            pointer-events: none;
            position: absolute;
            left: 0;
            bottom: 0;
        }
    </script>
    <!-- BEGIN SETTINGS -->
    <!-- END SETTINGS -->
</head>
<!--
  HOW TO USE:
  data-theme: default (default), dark, light
  data-layout: fluid (default), boxed
  data-sidebar: left (default), right
-->

<body data-theme="default" data-layout="fluid" data-sidebar="left">
<main class="d-flex w-100 h-100">
    <div class="container d-flex flex-column">
        <div class="row vh-100">
            <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                <?php
                if (isset($_GET['r']) && $_GET['r'] == 'result') {
                    if (isset($_POST['id_number'])) {
                        $_SESSION['id_number'] = $_POST['id_number'];
                        if($info=$app->personInfo($_POST['id_number'])){
                            $_SESSION['avatar_path'] = $app->getImage($_POST['id_number']);
                            echo '<div class="d-table-cell align-middle">
                    <div class="text-center">
                        <h1 class="h2">Nice!</h1>
                        <p class="lead">
                            One last step, please check if this is your account.
                        </p>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                <form method="post" action="register.php?r=process&id_num='.$_POST['id_number'].'" id="submitProfileRequest">
                                <div class="text-center">
									<img src="'.$app->getImage($_POST['id_number']).'" class="img-fluid rounded-circle mb-2" width="128" height="128">
									<h3 class="mb-1 text-capitalize">'.strtolower($info['fname'].' '.$info['lname']).'</h3>
									<div class="text-muted mb-0">'.$info['office_desc'].'</div>
									<div class="text-muted mb-0">'.$info['sect_desc'].'</div>
									<div class="text-muted mb-0">'.$info['position_name'].'</div>
									<div class="badge bg-success mb-3">Active</div>
									<br/>
									<button type="submit" class="btn btn-primary mt-3 mb-3" name="processRegister">Yes, it\'s me!</button>
									<br/>
									<a href="register.php" class="">Not You?<a>
								</div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>';
                        }else{

                        }

                    }
                } elseif(isset($_GET['r']) && $_GET['r']=='process') {
                    if(isset($_SESSION['id_number'])){
                        //check existing account
                        if($app->is_idNumberExist($_SESSION['id_number'])){
                            echo '<div class="d-table-cell align-middle">

                    <div class="text-center">
                        <h1 class="h2">Get started</h1>
                        <p class="lead">
                            Enter your DSWD ID-NUMBER to connect with <strong>HIReS</strong>
                        </p>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                               
                                    <div class="text-center mt-3">
                                    Account already exist.
                                    <br/>
                                    <a href="index.php">Go back</a>
                                    </div>
                                
                                
                            </div>
                        </div>
                    </div>

                </div>';
                        }else{
                            if($app->register_sso($_SESSION['id_number'])){
                                $app->login_sso($_SESSION['sso_username']);
                                header('location: home.php');
                            }
                       }
                    }else{
                        header('location: register.php');
                    }
                }else{
                    echo '<div class="d-table-cell align-middle">

                    <div class="text-center">
                        <h1 class="h2">Get started</h1>
                        <p class="lead">
                            Enter your DSWD ID-NUMBER to connect with <strong>HIReS</strong>
                        </p>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                <form method="post" action="register.php?r=result" id="submitProfileRequest">
                                    <div class="mb-3">
                                        <label class="form-label">ID-NUMBER</label>
                                        <input class="form-control form-control-lg" type="text" name="id_number" placeholder="Ex. 16-00000" />
                                    </div>
                                    <div class="text-center mt-3">
                                        <button type="submit" class="btn btn-lg btn-primary">Check</button>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                    </div>

                </div>';
                }
                ?>
            </div>
        </div>
    </div>
</main>
</body>

<script src="resources/js/jquery-3.5.1.js"></script>
<script src="resources/js/app.js"></script>
<!-- 3rd Party Plugin-->
<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript"
        src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.23/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.7/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.js"></script>
<!--Initialization-->
<script type="text/javascript" src="vendor/PDFObject-master/pdfobject.min.js"></script>
<script type="text/javascript" src="resources/js/dqa.js"></script>
<script type="text/javascript" src="resources/js/search.js"></script>


</html>
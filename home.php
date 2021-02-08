<?php
include_once('app/Database.php');
include_once('app/App.php');
include_once('app/Auth.php');
include_once('app/User.php');
include_once('app/City.php');
include_once('app/Ceac.php');
include_once('app/Dqa.php');
$app = new \app\App();
$auth = new \app\Auth();
$user = new \app\User();
$city = new \app\City();
$ceac = new \app\Ceac();
$dqa = new \app\Dqa();

if(!$auth->loggedIn()){
    header('location: index.php');
}

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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.23/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.7/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.css"/>
    <link rel="canonical" href="https://demo.adminkit.io/pages-blank.html"/>

    <title><?php echo(isset($_GET['p']) ? ucfirst($app->p_title($_GET['p'])) : 'MRMS | Home') ?></title>
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
<div class="wrapper">
    <nav id="sidebar" class="sidebar">
        <div class="sidebar-content js-simplebar">
            <a class="sidebar-brand" href="index.php">
                <span class="align-middle">M&E | MRMS</span>
            </a>

            <ul class="sidebar-nav">
                <li class="sidebar-header">
                    Pages
                </li>
                <li class="sidebar-item <?php $app->sidebar_active('dashboards', $_GET['p']); ?>">
                    <a data-target="#dashboards" data-toggle="collapse" class="sidebar-link <?php $app->sidebar_collapsed('dashboards', $_GET['p']); ?>">
                        <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboards</span>
                    </a>
                    <ul id="dashboards"
                        class="sidebar-dropdown list-unstyled collapse <?php $app->sidebar_showList('dashboards', $_GET['p']); ?>"
                        data-parent="#sidebar">
                        <li class="sidebar-item ">
                            <a data-target="#multi-2" data-toggle="collapse"
                               class="sidebar-link <?php $app->sidebar_collapsed('mov_uploading', $_GET['m']); ?>">MOV
                                Uploading
                            </a>
                            <ul id="multi-2" class="sidebar-dropdown list-unstyled collapse <?php $app->sidebar_showList('mov_uploading', $_GET['m']); ?>">
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="home.php?p=dashboards&m=mov_uploading&year=2021">2021
                                        <span
                                                class="sidebar-badge badge bg-secondary">NYS</span></a>
                                    <a class="sidebar-link" href="home.php?p=dashboards&m=mov_uploading&year=2020">2020
                                        <span
                                                class="sidebar-badge badge bg-success">On-Going</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item <?php $app->sidebar_active('mov_reviewed', $_GET['m']); ?>"><a
                                    class="sidebar-link" href="home.php?p=dashboards&m=mov_reviewed">MOV Reviewed</a>
                        </li>
                        <li class="sidebar-item <?php $app->sidebar_active('exec_db', $_GET['m']); ?>"><a
                                    class="sidebar-link" href="home.php?p=dashboards&m=exec_db">Executive Dashboard</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item <?php $app->sidebar_active('modules', $_GET['p']); ?>">
                    <a data-target="#pages" data-toggle="collapse" class="sidebar-link collapsed">
                        <i class="align-middle" data-feather="layout"></i> <span class="align-middle">Modules</span>
                    </a>
                    <ul id="pages"
                        class="sidebar-dropdown list-unstyled collapse <?php if($_GET['m']=='dqa_conducted' || $_GET['m']=='dqa_items'){
                            echo 'show';
                        } ?>"
                        data-parent="#sidebar">
                        <a data-target="#multi-3" data-toggle="collapse"
                               class="sidebar-link <?php 
                               if($_GET['m']!=='dqa_conducted' OR $_GET['m']=='dqa_items'){
                                   echo 'collapsed';
                               }
                               $app->sidebar_collapsed('dqa_conducted', $_GET['m']); ?>">DQA
                        </a>
                        <ul id="multi-3" class="sidebar-dropdown list-unstyled collapse <?php if($_GET['m']=='dqa_conducted' || $_GET['m']=='dqa_items'){
                            echo 'show';
                        } ?>">
                                <li class="sidebar-item">   
                                    <a class="sidebar-link" href="home.php?p=modules&m=dqa_conducted&modality=af_cbrc">KC-AF CBRC</a>
                                    <a class="sidebar-link" href="home.php?p=modules&m=dqa_conducted&modality=ipcdd_drom">IPCDD DROM</a>
                                </li>
                        </ul>
                    </ul>
                </li>
                <li class="sidebar-header">
                    Modality
                </li>
                <li class="sidebar-item">
                    <a data-target="#ncddp" data-toggle="collapse" class="sidebar-link collapsed">
                        <i class="align-middle" data-feather="corner-right-down"></i> <span
                                class="align-middle">NCDDP</span>
                    </a>
                    <ul id="ncddp" class="sidebar-dropdown list-unstyled collapse" data-parent="#sidebar">
                        <li class="sidebar-item"><a class="sidebar-link" href="pages-settings.html">Search</a>
                        </li>
                        <li class="sidebar-item"><a class="sidebar-link" href="pages-settings.html">Municipality</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a data-target="#ipcdd" data-toggle="collapse" class="sidebar-link collapsed">
                        <i class="align-middle" data-feather="corner-right-down"></i> <span
                                class="align-middle">IPCDD</span>
                    </a>
                    <ul id="ipcdd" class="sidebar-dropdown list-unstyled collapse" data-parent="#sidebar">
                        <li class="sidebar-item"><a class="sidebar-link" href="pages-settings.html">Search</a>
                        </li>
                        <li class="sidebar-item"><a class="sidebar-link" href="pages-settings.html">CADT</a></li>
                    </ul>
                </li>
                <li class="sidebar-header">
                    System
                </li>
                <li class="sidebar-item">
                    <a data-target="#ui" data-toggle="collapse" class="sidebar-link collapsed">
                        <i class="align-middle" data-feather="settings"></i> <span class="align-middle">Libraries</span>
                    </a>
                    <ul id="ui" class="sidebar-dropdown list-unstyled collapse" data-parent="#sidebar">
                        <li class="sidebar-item"><a class="sidebar-link" href="ui-alerts.html">Modality</a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="ui-buttons.html">Cycle</a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="ui-cards.html">Forms</a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="ui-general.html">PIMS Meta Data</a></li>
                    </ul>
                </li>
            </ul>
            <div class="sidebar-cta">
                <div class="sidebar-cta-content">
                    <strong class="d-inline-block mb-2">Weekly Report</strong>
                    <div class="mb-3 text-sm">
                        Your weekly report is ready for download!
                    </div>
                    <a href="https://adminkit.io/" class="btn btn-outline-primary btn-block"
                       target="_blank">Click here</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="main">
        <nav class="navbar navbar-expand navbar-light navbar-bg">
            <a class="sidebar-toggle d-flex">
                <i class="hamburger align-self-center"></i>
            </a>

            <form class="d-none d-sm-inline-block">
                <div class="input-group input-group-navbar">
                    <input type="text" class="form-control" placeholder="Search…" aria-label="Search">
                    <button class="btn" type="button">
                        <i class="align-middle" data-feather="search"></i>
                    </button>
                </div>
            </form>

            <div class="navbar-collapse collapse">
                <ul class="navbar-nav navbar-align">
                    <li class="nav-item dropdown">
                        <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-toggle="dropdown">
                            <i class="align-middle" data-feather="settings"></i>
                        </a>
                        <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-toggle="dropdown">
                            <img src="resources/img/avatars/default.jpg"
                                 class="avatar img-fluid rounded mr-1" alt="userImage"/> <span
                                    class="text-dark text-capitalize"><?php echo strtolower($_SESSION['user_fullname']); ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="pages-profile.html"><i class="align-middle mr-1"
                                                                                  data-feather="user"></i> Profile</a>
                            <a class="dropdown-item" href="#"><i class="align-middle mr-1" data-feather="pie-chart"></i>
                                Analytics</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="pages-settings.html"><i class="align-middle mr-1"
                                                                                   data-feather="settings"></i> Settings
                                &
                                Privacy</a>
                            <a class="dropdown-item" href="#"><i class="align-middle mr-1"
                                                                 data-feather="help-circle"></i> Help Center</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="login/logout.php">Log out</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="content">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-12">
                        <?php
                        ($_GET['m'] == 'mov_uploading' && $_GET['year']=='2020') ? include('resources/views/movUploadingStatus.php') : '';
                        ($_GET['m'] == 'mov_uploading' && $_GET['year']=='2021') ? include('resources/views/movUploadingStatus_2021.php') : '';
                        //dqa module
                        $getModality='';
                        if(isset($_GET['modality']) && ($_GET['modality']=='ipcdd_drom')){
                            $getModality = 'IPCDD DROM';
                        }
                        if(isset($_GET['modality']) && ($_GET['modality']=='af_cbrc' OR $_GET['modality']=='ncddp_drom')){
                            $getModality = 'KC-AF CBRC';
                        }

                        if(isset($_GET['m']) && $_GET['m']=='dqa_items'){
                            $l='';
                            if(isset($_GET['modality'])){
                                $l="home.php?p=modules&m=dqa_conducted&modality=".$_GET['modality'];
                            }
                            echo '<nav aria-label="breadcrumb">
										<ol class="breadcrumb">
											<li class="breadcrumb-item"><a href="#"><strong>Module</strong></a></li>
                                            <li class="breadcrumb-item"><a href="#"><strong>Data Quality Assessment</strong></a></li>
											<li class="breadcrumb-item"><a href="'.$l.'"><strong>'.$getModality.'</strong></a></li>
											<li class="breadcrumb-item active"><strong>'.$_GET['title'].'</strong></li>
										</ol>
									</nav>';
                            include('resources/views/viewDqaItems.php');
                        }
                        if(isset($_GET['m']) && $_GET['m']=='dqa_conducted'){
                            if(isset($_GET['modality'])){
                                $l="home.php?p=modules&m=dqa_conducted&modality=".$_GET['modality'];
                            }
                            echo '<nav aria-label="breadcrumb">
										<ol class="breadcrumb">
											<li class="breadcrumb-item"><a href="index.html"><strong>Module</strong></a></li>
                                            <li class="breadcrumb-item"><a href="#"><strong>Data Quality Assessment</strong></a></li>
											<li class="breadcrumb-item active"><strong>'.$getModality.'</strong></li>
										</ol>
									</nav>';
                            include('resources/views/tblDqa.php');
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
        <footer class="footer">
            <div class="container-fluid">
                <div class="row text-muted">
                    <div class="col-6 text-left">
                        <p class="mb-0">
                            <a href="#" class="text-muted"><strong>&copy; 2021 DSWD CARAGA Kalahi-CIDSS | Monitoring and
                                    Evaluation Unit.</strong></a>
                        </p>
                    </div>
                    <div class="col-6 text-right">
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <a class="text-muted" href="#">Support</a>
                            </li>
                            <li class="list-inline-item">
                                <a class="text-muted" href="#">Help Center</a>
                            </li>
                            <li class="list-inline-item">
                                <a class="text-muted" href="#">Privacy</a>
                            </li>
                            <li class="list-inline-item">
                                <a class="text-muted" href="#">Terms</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
</body>

<script src="resources/js/jquery-3.5.1.js"></script>
<script src="resources/js/app.js"></script>
<!-- 3rd Party Plugin-->
<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.23/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.7/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.js"></script>
<!--Initialization-->
<script type="text/javascript" src="vendor/PDFObject-master/pdfobject.min.js"></script>
<script type="text/javascript" src="resources/js/dqa.js"></script>
<script>
    $(document).ready(function () {
        var m = url.searchParams.get("m");
        if(m=='dqa_conducted'){
            new Choices(document.querySelector(".choices-muni"));
            new Choices(document.querySelector(".choicesCycle"));
            new Choices(document.querySelector(".choicesAc"));
            new Choices(document.querySelector(".editChoicesAc"));            

        }
        if(m=='dqa_items'){
            new Choices(document.querySelector(".choices-dqa-level"));
            flatpickr(".flatpickr-minimum",{
                minDate:'today'
            });
            $("#dateOfCompliance").removeAttr('readonly')
            const choicesFinding = new Choices(".choices-findings", {
                shouldSort: false
            });
            const choiceTypeOfFindings = new Choices(".choices-type-of-findings", {
                shouldSort: false
            });
            const choicesStaff = new Choices(".choices-staff");
            document.getElementById("choicesFinding").addEventListener("change", function(e) {
                if(this.value=='no'){
                    choiceTypeOfFindings.disable();
                    choicesStaff.disable();
                    document.getElementById("text_findings").disabled = true;
                    document.getElementById("text_findings").value = '';
                    document.getElementById("dateOfCompliance").value = '';
                    document.getElementById("responsiblePerson").value = '';
                    $("#dateOfCompliance").prop('disabled',true);
                }if(this.value=='yes'){
                    choiceTypeOfFindings.enable();
                    choicesStaff.enable();
                    document.getElementById("text_findings").disabled = false;
                    $('.flatpickr-minimum').prop('disabled',false);
                }
                if(this.value=='ta'){
                    choiceTypeOfFindings.disable();
                    choicesStaff.disable();
                    $("#dateOfCompliance").prop('disabled',true);
                    document.getElementById("text_findings").disabled = false;
                }
            });

        }
    });
</script>
</html>
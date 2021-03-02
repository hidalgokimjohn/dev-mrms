<?php
include_once('app/Database.php');
include_once('app/App.php');
include_once('app/Auth.php');
$app = new \app\App();
$auth = new \app\Auth();

if (!$auth->loggedIn()) {
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
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.23/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.7/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.css"/>
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
    <?php
        if($_SESSION['user_lvl']=='RPMO'){
            include_once ("resources/views/rpmoSidebarMenu.php");
        }
        if($_SESSION['user_lvl']=='ACT'){
            include_once ("resources/views/actSidebarMenu.php");
        }
    ?>
    <div class="main">
        <nav class="navbar navbar-expand navbar-light navbar-bg p-3">
            <a class="sidebar-toggle d-flex">
                <i class="hamburger align-self-center"></i>
            </a>
            <img src="resources/img/logo/kclogos.jpg" height="36">
            <div class="navbar-collapse collapse">
                <ul class="navbar-nav navbar-align">
                    <li class="nav-item dropdown">
                        <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-toggle="dropdown">
                            <i class="align-middle" data-feather="settings"></i>
                        </a>

                        <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-toggle="dropdown">
                            <img src="<?php echo $app->getImage($_SESSION['id_number']); ?>" class="avatar img-fluid rounded mr-1"
                                 alt="userImage"/> <span
                                    class="text-dark text-capitalize"><?php echo strtolower($_SESSION['user_fullname']); ?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="#"><i class="align-middle mr-1" data-feather="user"></i> Profile</a>
                            <a class="dropdown-item" href="#"><i class="align-middle mr-1" data-feather="pie-chart"></i> Analytics</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#"><i class="align-middle mr-1" data-feather="settings"></i> Settings & Privacy</a>
                            <a class="dropdown-item" href="#"><i class="align-middle mr-1" data-feather="help-circle"></i> Help Center</a>
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
                    <div class="col-12 mb-2">
                       <!-- <div class="alert alert-info alert-dismissible" role="alert">
                            <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                            <div class="alert-icon">
                                <i class="far fa-fw fa-bell"></i>
                            </div>
                            <div class="alert-message">
                                <strong>NOTICE #001: </strong> Sample alert! <strong>4PM Today. </strong> Char lang
                            </div>
                        </div>-->
                    </div>
                    <div class="col-12">
                        <?php
                        if(!empty($_GET['p'])){
                            //dashboards
                            //($_GET['p'] == 'dashboards' && $_GET['modality'] == 'ncddp' && $_GET['year'] == '2020') ? include('resources/views/movUploadingStatus.php') : '';
                            ($_GET['p'] == 'dashboards' && $_GET['modality'] == 'af_cbrc') ? include('resources/views/dashboard_kcaf_cbrc.php') : '';
                            ($_GET['p'] == 'dashboards' && $_GET['modality'] == 'ipcdd_drom') ? include('resources/views/dashboard_ipcdd_drom.php') : '';
                            ($_GET['p'] == 'user_mngt') ? include('resources/views/userManagement.php') : '';
                            ($_GET['p'] == 'search' && $_GET['modality'] == 'ncddp_drom') ? include('resources/views/searchFileNcddp.php') : '';
                            ($_GET['p'] == 'search' && $_GET['modality'] == 'af_cbrc') ? include('resources/views/searchFileKcAf.php') : '';
                            ($_GET['p'] == 'search' && $_GET['modality'] == 'ipcdd_drom') ? include('resources/views/searchFileIpcdd.php') : '';

                            if($_SESSION['user_lvl']=='RPMO'){
                                ($_GET['p'] == 'mywork') ? include('resources/views/myWork.php') : '';
                                //dqa module
                                $getModality = '';
                                if (isset($_GET['modality']) && ($_GET['modality'] == 'ipcdd_drom')) {
                                    $getModality = 'IPCDD DROM';
                                }
                                if (isset($_GET['modality']) && ($_GET['modality'] == 'af_cbrc')) {
                                    $getModality = 'KC-AF CBRC';
                                }
                                if (isset($_GET['m']) && $_GET['m'] == 'dqa_items') {
                                    $l = '';
                                    if (isset($_GET['modality'])) {
                                        $l = "home.php?p=modules&m=dqa_conducted&modality=" . $_GET['modality'];
                                    }
                                    echo '<div class="row mb-2 mb-xl-3">
                                                <div class="col-auto ml-auto text-right mt-n1">
                                                    <nav aria-label="breadcrumb">
                                                        <ol class="breadcrumb bg-transparent p-0 mt-1 mb-0">
                                                        <li class="breadcrumb-item"><a href="#">Module</a></li>
                                                        <li class="breadcrumb-item"><a href="#">Data Quality Assessment</a></li>
                                                        <li class="breadcrumb-item"><a href="' . $l . '">' . $getModality . '</a></li>
                                                        <li class="breadcrumb-item active">' . $_GET['title'] . '</li>
                                                        </ol>
                                                    </nav>
                                                </div>
                                           </div>';
                                    include('resources/views/viewDqaItems.php');
                                }
                                if (isset($_GET['m']) && $_GET['m'] == 'dqa_conducted') {
                                    if (isset($_GET['modality'])) {
                                        $l = "home.php?p=modules&m=dqa_conducted&modality=" . $_GET['modality'];
                                    }
                                    echo '<div class="row mb-2 mb-xl-3">
                                                <div class="col-auto ml-auto text-right mt-n1">
                                                    <nav aria-label="breadcrumb">
                                                        <ol class="breadcrumb bg-transparent p-0 mt-1 mb-0">
                                                        <li class="breadcrumb-item"><a href="#">Module</a></li>
                                                        <li class="breadcrumb-item"><a href="#">Data Quality Assessment</a></li>
                                                        <li class="breadcrumb-item">' . $getModality . '</li>
                                                        </ol>
                                                    </nav>
                                                </div>
                                            </div>';
                                    include('resources/views/tblDqa.php');
                                }

                            }

                            if($_SESSION['user_lvl']=='ACT'){
                                ($_GET['p'] == 'upload') ? include('resources/views/upload.php') : '';
                            }

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
<script type="text/javascript"
        src="https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.23/af-2.3.5/b-1.6.5/b-colvis-1.6.5/b-flash-1.6.5/b-html5-1.6.5/b-print-1.6.5/cr-1.5.3/fc-3.3.2/fh-3.1.7/kt-2.5.3/r-2.2.7/rg-1.1.2/rr-1.2.7/sc-2.0.3/sb-1.0.1/sp-1.2.2/sl-1.3.1/datatables.min.js"></script>
<!--Initialization-->
<script type="text/javascript" src="vendor/PDFObject-master/pdfobject.min.js"></script>
<script type="text/javascript" src="resources/js/dqa.js"></script>
<script>
    $(document).ready(function () {
        var m = url.searchParams.get("m");
        var p = url.searchParams.get("p");

        if (m == 'dqa_conducted') {
            new Choices(document.querySelector(".choices-muni"));
            new Choices(document.querySelector(".choicesCycle"));
            new Choices(document.querySelector(".choicesAc"));
            new Choices(document.querySelector(".editChoicesAc"));
        }

        if(p=='upload'){
            var choiceTypeOfCadt = new Choices(".choices-of-cadt", {
                shouldSort: false
            });
        }

        if (m == 'dqa_items') {
            new Choices(document.querySelector(".choices-dqa-level"));
            flatpickr(".flatpickr-minimum", {
                minDate: 'today'
            });
            $("#dateOfCompliance").removeAttr('readonly')
            const choicesFinding = new Choices(".choices-findings", {
                shouldSort: false
            });
            const choiceTypeOfFindings = new Choices(".choices-type-of-findings", {
                shouldSort: false
            });
            const choicesStaff = new Choices(".choices-staff");
            document.getElementById("choicesFinding").addEventListener("change", function (e) {
                if (this.value == 'no') {
                    choiceTypeOfFindings.disable();
                    choicesStaff.disable();
                    document.getElementById("text_findings").disabled = true;
                    document.getElementById("text_findings").value = '';
                    document.getElementById("dateOfCompliance").value = '';
                    document.getElementById("responsiblePerson").value = '';
                    $("#dateOfCompliance").prop('disabled', true);
                }
                if (this.value == 'yes') {
                    choiceTypeOfFindings.enable();
                    choicesStaff.enable();
                    document.getElementById("text_findings").disabled = false;
                    $('.flatpickr-minimum').prop('disabled', false);
                }
                if (this.value == 'ta') {
                    choiceTypeOfFindings.disable();
                    choicesStaff.disable();
                    $("#dateOfCompliance").prop('disabled', true);
                    document.getElementById("text_findings").disabled = false;
                }
            });

        }
        $('#tbl_users thead tr').clone(true).appendTo('#tbl_users thead');
        $('#tbl_users thead tr:eq(1) th').each(function (i) {
            if (i !== 0) {
                var title = $(this).text();
                $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
                $('input', this).on('keyup change', function (e) {
                    if (tbl_users.column(i).search() !== this.value) {
                        tbl_users.column(i).search(this.value).draw();
                    }
                });
            }
        });
        var tbl_users = $('#tbl_users').DataTable({
            orderCellsTop: true,
            fixedHeader: true,
            order: [
                [1, "asc"]
            ],
            columnDefs: [{
                orderable: false,
                targets: 0
            }],
            dom: '<"html5buttons">bitpr',
            ajax: {
                url: "resources/ajax/tbl_users.php",
                type: "POST",
                processData: false,
                contentType: false,
                cache: false,
                dataType: 'json',
                error: function () {
                    $("post_list_processing").css("display", "none");
                }
            },
            language: {
                "emptyTable": "<b>No records <found class=''></found></b>"
            },
            columnDefs: [{
                "targets": 0,
                "data": null,
                "render": function (data, type, row) {
                    //<button class="btn btn-danger btn-sm">Delete</button>
                    return '<div class="btn-group">' +
                        '<button type="button" class="btn btn-pill btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>' +
                        '<div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a>' +
                        '<a class="dropdown-item" href="#">Another action</a><a class="dropdown-item" href="#">Something else here</a>' +
                        '<div class="dropdown-divider"></div><a class="dropdown-item" href="#">ID-Number: '+data['id_number']+'</a></div></div>'
                },
            },{
                "targets": 1,
                "data": null,
                "render": function (data, type, row) {
                    //<button class="btn btn-danger btn-sm">Delete</button>
                    return '<img src="resources/img/avatars/default.jpg" width="48" height="48" class="rounded-circle my-n1"></img> '+data['fname']+' '+data['lname'];
                },
            },{
                "targets": 2,
                "data": null,
                "render": function (data, type, row) {
                    //<button class="btn btn-danger btn-sm">Delete</button>
                    return data['position_desc'];
                },
            },{
                "targets": 3,
                "data": null,
                "render": function (data, type, row) {
                    //<button class="btn btn-danger btn-sm">Delete</button>
                    return data['office_name'];
                },
            },{
                "targets": 4,
                "data": null,
                "render": function (data, type, row) {
                    //<button class="btn btn-danger btn-sm">Delete</button>
                    return '<div class="badge bg-success"><span class="fa fa-check-circle"></span> '+data['status_name']+'</div>';
                },
            }
            ],
        });
    });
</script>
<script type="text/javascript" src="resources/js/search.js"></script>
</html>
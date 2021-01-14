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


$provider = new \Stevenmaguire\OAuth2\Client\Provider\Keycloak([
    'authServerUrl' => 'http://auth.caraga.dswd.gov.ph:8080/auth',
    'realm' => 'entdswd.local',
    'clientId' => 'kalahi-apps',
    'clientSecret' => '07788f27-8e6a-4729-a033-0eb5cb7c7389',
    'redirectUri' => 'http://crg-kcapps-svr/mrms/index.php'
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state, make sure HTTP sessions are enabled.');

}
else {

// Try to get an access token (using the authorization coe grant)
try {
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);
} catch (Exception $e) {
    exit('Failed to get access token: ' . $e->getMessage());
}

// Optional: Now you have a token you can look up a users profile data
try {

    // We got an access token, let's now get the user's details
    $user_sso = $provider->getResourceOwner($token);

    if ($user->sso_isExist($user_sso)) {
        $auth->redirectTo('index.php');
    } else {
        $user->register_sso($user_sso);
    }

    //1. check nya ang naka session database

    //2. pag walay user unya oauth wala nag exist, e create nya

    //3. go to urlshit

    // Use these details to create a new profile
    printf('Hello %s!', $user->getName());

} catch (Exception $e) {
    exit('Failed to get resource owner: ' . $e->getMessage());
}

// Use this to interact with an API on the users behalf
echo $token->getToken();

$auth->maintenance();
$user->info($_SESSION['username']);
$app->notif_for_compliance();
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

    <link rel="canonical" href="https://demo.adminkit.io/pages-blank.html"/>

    <title><?php echo(isset($_GET['p']) ? ucfirst($app->p_title($_GET['p'])) : 'MRMS | Home') ?></title>
    <link href="resources/css/app.css" rel="stylesheet">

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
                    <a data-target="#dashboards" data-toggle="collapse" class="sidebar-link collapsed">
                        <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboards</span>
                    </a>
                    <ul id="dashboards"
                        class="sidebar-dropdown list-unstyled collapse <?php $app->sidebar_showList('dashboards', $_GET['p']); ?>"
                        data-parent="#sidebar">
                        <li class="sidebar-item ">
                            <a data-target="#multi-2" data-toggle="collapse"
                               class="sidebar-link <?php $app->sidebar_showList('dashboards', $_GET['p']); ?>">MOV
                                Uploading</a>
                            <ul id="multi-2" class="sidebar-dropdown list-unstyled collapse show">
                                <li class="sidebar-item">
                                    <a class="sidebar-link" href="index.php?p=dashboards&m=mov_uploading_2021">2021
                                        <span
                                            class="sidebar-badge badge bg-secondary">NYS</span></a>
                                    <a class="sidebar-link" href="index.php?p=dashboards&m=mov_uploading_2020">2020
                                        <span
                                            class="sidebar-badge badge bg-success">On-Going</span></a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item <?php $app->sidebar_active('mov_reviewed', $_GET['m']); ?>"><a
                                class="sidebar-link" href="index.php?p=dashboards&m=mov_reviewed">MOV Reviewed</a>
                        </li>
                        <li class="sidebar-item <?php $app->sidebar_active('exec_db', $_GET['m']); ?>"><a
                                class="sidebar-link" href="index.php?p=dashboards&m=exec_db">Executive Dashboard</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item <?php $app->sidebar_active('modules', $_GET['p']); ?>">
                    <a data-target="#pages" data-toggle="collapse" class="sidebar-link collapsed">
                        <i class="align-middle" data-feather="layout"></i> <span class="align-middle">Modules</span>
                    </a>
                    <ul id="pages"
                        class="sidebar-dropdown list-unstyled collapse <?php $app->sidebar_showList('modules', $_GET['p']); ?>"
                        data-parent="#sidebar">
                        <li class="sidebar-item <?php $app->sidebar_active('dqa', $_GET['m']); ?>"><a
                                class="sidebar-link" href="index.php?p=modules&m=dqa">Data Quality Assessment</a>
                        </li>
                        <li class="sidebar-item <?php $app->sidebar_active('mov_checklist', $_GET['m']); ?>"><a
                                class="sidebar-link" href="index.php?p=modules&m=mov_checklist">MOV Checklist</a>
                        </li>
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
                    <strong class="d-inline-block mb-2">Weekly Uploading Report</strong>
                    <div class="mb-3 text-sm">
                        Your weekly uploading report is ready for download!
                    </div>
                    <a href="https://adminkit.io/" class="btn btn-outline-primary btn-block"
                       target="_blank">Download</a>
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
                            <img src="../../Storage/image/profile_pictures/thumbnails/<?php echo $user->pic_url; ?>"
                                 class="avatar img-fluid rounded mr-1" alt="userImage"/> <span
                                class="text-dark text-capitalize"><?php echo $user->first_name . ' ' . $user->last_name; ?></span>
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
                <h1 class="h3 mb-3 text-capitalize"><?php echo $_GET['p']; ?></h1>
                <div class="row">
                    <div class="col-12">
                        <?php
                        ($_GET['m'] == 'mov_uploading_2020') ? include('resources/views/mov_uploading.php') : '';
                        ($_GET['m'] == 'mov_uploading_2021') ? include('resources/views/mov_uploading_2021.php') : '';
                        ($_GET['m'] == 'dqa') ? include('resources/views/tblDqa.php') : '';
                        ($_GET['m'] == 'view_dqa') ? include('resources/views/viewDqaItems.php') : '';
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
<script src="resources/js/datatables.js"></script>
<!--Initialization-->
<script type="text/javascript" src="resources/js/dqa.js"></script>

<script>
        $(document).ready(function () {
        var m = url.searchParams.get("m");
        if(m=='dqa'){
            new Choices(document.querySelector(".choices-single"));
            new Choices(document.querySelector(".choicesCycle"));
            new Choices(document.querySelector(".choicesAc"));
            new Choices(document.querySelector(".editChoicesAc"));
        }
    });
/*    */
</script>
</html>
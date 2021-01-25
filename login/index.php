<?php
session_start();
include_once('../app/Database.php');
include_once('../app/App.php');
include_once('../app/Auth.php');
include_once('../app/User.php');

/*$auth = new app\Auth();
if ($auth->loggedIn()) {
    $auth->redirectTo('../index.php');
}*/

$app = new \app\App();
$user = new \app\User();
if($_SESSION['mrms_auth']){
    header('location: ../index.php');
}
var_dump($_SESSION['mrms_auth']);
//$auth->maintenance();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="../resources/img/icons/icon-48x48.png"/>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">
    <link rel="canonical" href="https://demo.adminkit.io/pages-sign-in.html"/>

    <title>Sign In | MRMS</title>

    <link href="../resources/css/app.css" rel="stylesheet">

    <!-- BEGIN SETTINGS -->
    <script src="../resources/js/settings.js"></script>
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
                <div class="d-table-cell align-middle">

                    <div class="text-center mt-4">
                        <h1 class="h2">MOV Repository & Management System</h1>
                        <p class="lead">
                            CARAGA | Kalahi-CIDSS
                        </p>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-4">
                                <form method="post">
                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <input class="form-control form-control-lg" type="text" name="username"
                                               placeholder="Enter your username"/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input class="form-control form-control-lg" type="password" name="password"
                                               placeholder="Enter your password"/>
                                        <small>
                                            <a href="pages-reset-password.html">Forgot password?</a>
                                        </small>
                                    </div>
                                    <div>
                                        <label class="form-check">
                                            <input class="form-check-input" type="checkbox" value="remember-me"
                                                   name="remember-me" checked>
                                            <span class="form-check-label">
													Remember me next time
												</span>
                                        </label>
                                    </div>
                                    <div class="text-center mt-3">
                                        <input type="submit" name="submit" class="btn btn-lg btn-primary" value="Sign in"> or
                                        <a href="../authenticate" class="btn btn-lg btn-success">Login with ISSO</a>
                                        <!-- <button type="submit" class="btn btn-lg btn-primary">Sign in</button> -->
                                    </div>
                                    <?php
                                    if (isset($_POST['submit'])) {

                                        if ($user->is_pending($_POST['username'])) {
                                            echo '<br><div class="alert alert-warning alert-dismissible" role="alert">
											<div class="alert-icon">
												<i data-feather="alert-circle"></i>
											</div>
											<div class="alert-message">
												<strong>Hey!</strong> This account is pending for activation.
											</div>
										    </div>';
                                        } else {
                                            if ($app->login($_POST['username'], $_POST['password'])) {
                                                $user->permission($_SESSION['username']);
                                                $log = $app->log($_SESSION['username'], 'login', 'has logged in', null, null);
                                                if ($_SESSION['user_lvl'] == 'user') {
                                                    $auth->redirectTo('../index.php');
                                                }
                                                if ($_SESSION['user_lvl'] == 'admin') {
                                                    $auth->redirectTo('../index.php');
                                                }
                                            } else {
                                                echo '<br><div class="alert alert-danger alert-dismissible" role="alert">
											<div class="alert-icon">
												<i data-feather="alert-circle"></i>
											</div>
											<div class="alert-message">
												<strong>Ops!</strong> Incorrect username or password. Please try again.
											</div>
										    </div>';
                                            }
                                        }
                                    }
                                    ?>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<script src="../resources/js/app.js"></script>

</body>
</html>

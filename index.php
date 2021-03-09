<?php
ob_start();

include_once('app/Database.php');
include_once('app/App.php');
include_once('app/Auth.php');
include_once('app/User.php');

$app = new \app\App();
$authen = new \app\Auth();
$user = new \app\User();

if ($authen->loggedIn()) {
    header('location: home.php?p=mywork&tab=main');
}

require 'vendor/autoload.php';

if(!$_SESSION['mrms_auth']){
    $provider = new \Stevenmaguire\OAuth2\Client\Provider\Keycloak([
            'authServerUrl' => 'https://caraga-auth.dswd.gov.ph:8443/auth',
            'realm' => 'entdswd.local',
            'clientId' => 'kalahi-apps',
            'clientSecret' => '139995d3-fa97-4772-8bb8-b8680afc1334',
            'redirectUri' => 'http://crg-kcapps-svr.entdswd.local/mrms/index.php'
    ]);

    if (!isset($_GET['code'])) {
        // If we don't have an authorization code then get one
        $authUrl = $provider->getAuthorizationUrl();
        $_SESSION['oauth2state'] = $provider->getState();

        /*header('Location: '.$authUrl);
        exit;*/

// Check given state against previously stored one to mitigate CSRF attack
    } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
        echo 'session: '.$_SESSION['oauth2state'];
        unset($_SESSION['oauth2state']);
        exit('Invalid state, make sure HTTP sessions are enabled.');
        die();
    } else {
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
            $user_sso = $user_sso->toArray();

            if ($app->sso_isExist($user_sso['sub'])) {
                //$user_sso = $user_sso->toArray();
                $_SESSION['mrms_auth'] = $user_sso['sub'];
                $app->login_sso($user_sso['preferred_username']);
                header('location: home.php?p=dashboards&modality=ipcdd_drom');
                exit;
            } else {
                $_SESSION['sso_username']=$user_sso['preferred_username'];
                $_SESSION['sso_oauth']=$user_sso['sub'];
                $_SESSION['forIDNumber']='true';
                header('location: register.php');
                exit;
            }

        } catch (Exception $e) {
            exit('Failed to get resource owner: ' . $e->getMessage());
        }
        // Use this to interact with an API on the users behalf
    }
}else{
    header('location: home.php');
}

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

    <link href="resources/css/app.css" rel="stylesheet">
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
                            CARAGA | Kalahi-CIDSS <small>Beta 3.0</small>
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
                                        <a href="<?php echo $authUrl; ?>" class="btn btn-lg btn-success">Login with ISSO</a>
                                        <!-- <button type="submit" class="btn btn-lg btn-primary">Sign in</button> -->
                                    </div>
                                    <?php
                                    if (isset($_POST['submit'])) {

                                       /* if ($app->is_pending($_POST['username'])) {
                                            echo '<br><div class="alert alert-warning alert-dismissible" role="alert">
											<div class="alert-icon">
												<i data-feather="alert-circle"></i>
											</div>
											<div class="alert-message">
												<strong>Hey!</strong> This account is pending for activation.
											</div>
										    </div>';
                                        } else {*/
                                            if ($app->login($_POST['username'], $_POST['password'])) {
                                                $app->permission($_SESSION['username']);
                                                $log = $app->log($_SESSION['username'], 'login', 'has logged in', null, null);
                                                header('location: home.php?p=dashboards&modality=ipcdd_drom');
                                                exit;
                                            } else {
                                                echo '<br><div class="alert alert-danger alert-dismissible" role="alert">
											<div class="alert-icon">
												<i data-feather="alert-circle"></i>
											</div>
											<div class="alert-message">Incorrect username or password. Please try again.</div>
										    </div>';
                                            }
                                        /*}*/
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

<script src="resources/js/app.js"></script>

</body>
</html>